<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    /**
     * List conversations for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->with(['participants' => fn ($q) => $q->select('users.id', 'users.name', 'users.profile_image', 'users.role', 'users.department_id')->with('department:id,name')])
            ->with(['latestMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(50);

        // Get users this person can message (role-based access)
        $availableUsers = $this->getAvailableUsers($user);

        return view('admin.conversations.index', compact('conversations', 'availableUsers'));
    }

    /**
     * Show a specific conversation.
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Verify participation
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with(['user:id,name,profile_image,role'])
            ->orderBy('created_at')
            ->get();

        // Mark as read
        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);

        $other = $conversation->otherParticipant($user->id);

        return view('admin.conversations.show', compact('conversation', 'messages', 'other'));
    }

    /**
     * Start a new conversation (or return existing 1-to-1).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();
        $targetId = (int) $request->user_id;

        if ($targetId === $user->id) {
            return response()->json(['error' => 'Cannot message yourself'], 422);
        }

        // Verify role-based access
        $target = User::findOrFail($targetId);
        if (!$this->canMessageUser($user, $target)) {
            return response()->json(['error' => 'Not authorized to message this user'], 403);
        }

        // Find existing 1-to-1 conversation
        $conversation = Conversation::where('is_group', false)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $targetId))
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'created_by' => $user->id,
                'is_group' => false,
                'last_message_at' => now(),
            ]);
            $conversation->participants()->attach([$user->id, $targetId]);
        }

        ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => e($request->message),
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'redirect' => route('admin.conversations.show', $conversation),
        ]);
    }

    /**
     * Send a message within a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $user = Auth::user();

        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        $request->validate([
            'message' => 'required_without:attachment|string|max:5000',
            'attachment' => 'nullable|file|max:'.config('uploads.max_upload_kb', 20480).'|mimes:'.implode(',', config('uploads.conversation_attachment_mimes', [])),
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('conversation-attachments', 'public');
        }

        $message = ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => e($request->input('message', '')),
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'user_id' => $message->user_id,
            'user_name' => $user->name,
            'user_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
            'attachment_path' => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
            'attachment_name' => $attachmentName,
            'created_at' => $message->created_at->toISOString(),
        ]);
    }

    /**
     * Poll for new messages within a conversation.
     */
    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $user = Auth::user();

        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        $afterId = $request->integer('after', 0);

        $messages = $conversation->messages()
            ->with('user:id,name,profile_image,role')
            ->when($afterId, fn ($q) => $q->where('id', '>', $afterId))
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'user_id' => $m->user_id,
                'user_name' => $m->user->name,
                'user_image' => $m->user->profile_image ? asset('storage/' . $m->user->profile_image) : null,
                'attachment_path' => $m->attachment_path ? asset('storage/' . $m->attachment_path) : null,
                'attachment_name' => $m->attachment_name,
                'created_at' => $m->created_at->toISOString(),
            ]);

        // Update last_read_at
        if ($messages->isNotEmpty()) {
            $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);
        }

        // Typing indicator
        $typing = cache()->has('conv_typing_' . $conversation->id . '_other_' . $user->id);

        return response()->json([
            'messages' => $messages,
            'typing' => $typing,
        ]);
    }

    /**
     * Set typing indicator.
     */
    public function typing(Conversation $conversation): JsonResponse
    {
        $user = Auth::user();

        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        // Store typing for all other participants
        $conversation->participants()->where('user_id', '!=', $user->id)->get()->each(function ($participant) use ($conversation) {
            cache()->put('conv_typing_' . $conversation->id . '_other_' . $participant->id, true, 5);
        });

        return response()->json(['ok' => true]);
    }

    /**
     * Get total unread count across all conversations.
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        $total = 0;

        $conversations = Conversation::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))->get();
        foreach ($conversations as $conv) {
            $total += $conv->unreadCountFor($user->id);
        }

        return response()->json(['count' => $total]);
    }

    /**
     * Online status — cache-based.
     */
    public function heartbeat(): JsonResponse
    {
        cache()->put('user_online_' . Auth::id(), true, 120);
        return response()->json(['ok' => true]);
    }

    /**
     * Role-based access: which users can this person message?
     */
    private function getAvailableUsers(User $user)
    {
        $query = User::where('id', '!=', $user->id)
            ->select('id', 'name', 'role', 'profile_image', 'department_id')
            ->with('department:id,name');

        if ($user->isSuperAdmin()) {
            // Can message everyone
        } elseif ($user->role === 'admin') {
            $query->whereIn('role', ['super_admin', 'admin', 'worker', 'agent']);
        } elseif ($user->isAgent()) {
            $query->whereIn('role', ['super_admin', 'admin', 'worker']);
        } elseif ($user->isWorker()) {
            $query->whereIn('role', ['super_admin', 'admin']);
        }

        return $query->orderBy('name')->get();
    }

    private function canMessageUser(User $sender, User $target): bool
    {
        if ($sender->isSuperAdmin()) return true;
        if ($sender->role === 'admin') return in_array($target->role, ['super_admin', 'admin', 'worker', 'agent']);
        if ($sender->isAgent()) return in_array($target->role, ['super_admin', 'admin', 'worker']);
        if ($sender->isWorker()) return in_array($target->role, ['super_admin', 'admin']);
        return false;
    }
}
