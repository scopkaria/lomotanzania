<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->withCount(['unreadMessages'])
            ->orderByDesc('last_activity_at')
            ->paginate(50);

        return view('admin.chat.index', compact('sessions'));
    }

    public function show(ChatSession $chatSession)
    {
        $chatSession->load(['messages.user', 'assignedAgent', 'department', 'transferredFrom']);

        // Mark all visitor messages as read
        $chatSession->messages()
            ->where('sender_type', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $workers = User::whereIn('role', ['worker', 'admin'])
            ->with('department')
            ->where('id', '!=', Auth::id())
            ->orderBy('name')->get();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.chat.show', compact('chatSession', 'workers', 'departments'));
    }

    /**
     * API: Get active sessions for admin panel polling.
     */
    public function sessions(Request $request): JsonResponse
    {
        $sessions = ChatSession::where('status', 'active')
            ->with(['assignedAgent:id,name,department_id', 'assignedAgent.department:id,name,color', 'department:id,name,color'])
            ->withCount('unreadMessages')
            ->orderByDesc('last_activity_at')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'visitor_name' => $s->visitor_name ?: 'Visitor #' . $s->id,
                'visitor_id' => $s->visitor_id,
                'current_page' => $s->current_page,
                'unread_count' => $s->unread_messages_count,
                'last_activity' => $s->last_activity_at?->diffForHumans(),
                'is_typing' => cache()->has('chat_typing_visitor_' . $s->id),
                'is_online' => $s->last_activity_at && $s->last_activity_at->gt(now()->subMinutes(2)),
                'assigned_to_name' => $s->assignedAgent?->name,
                'department' => $s->department?->name ?? $s->assignedAgent?->department?->name,
                'department_color' => $s->department?->color ?? $s->assignedAgent?->department?->color,
            ]);

        return response()->json(['sessions' => $sessions]);
    }

    /**
     * API: Get messages for a session.
     */
    public function messages(ChatSession $chatSession, Request $request): JsonResponse
    {
        $userId = Auth::id();
        $messages = $chatSession->messages()
            ->visible($userId)
            ->when($request->last_id, fn ($q) => $q->where('id', '>', $request->last_id))
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'message' => $m->message,
                'sender_type' => $m->sender_type,
                'message_type' => $m->message_type,
                'sender_name' => $m->sender_type === 'agent'
                    ? ($m->user?->name ?? 'Agent')
                    : ($chatSession->visitor_name ?? 'Visitor'),
                'sender_department' => $m->sender_type === 'agent' ? $m->user?->department?->name : null,
                'whisper_to_name' => $m->whisper_to ? $m->whisperRecipient?->name : null,
                'created_at' => $m->created_at->toISOString(),
                'time' => $m->created_at->format('H:i'),
            ]);

        $visitorTypingText = cache()->get('chat_typing_visitor_' . $chatSession->id);

        // Fresh load for real-time visitor info
        $chatSession->refresh();

        return response()->json([
            'messages' => $messages,
            'is_visitor_typing' => $visitorTypingText !== null,
            'visitor_typing_text' => $visitorTypingText ?: '',
            'page_history' => $chatSession->page_history ?? [],
            'current_page' => $chatSession->current_page,
            'visitor_name' => $chatSession->visitor_name,
            'visitor_email' => $chatSession->visitor_email,
        ]);
    }

    /**
     * API: Send a reply from admin.
     */
    public function reply(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $message = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'user_id' => Auth::id(),
            'sender_type' => 'agent',
            'message_type' => 'normal',
            'message' => e($request->message),
        ]);

        // Assign if not assigned yet
        if (! $chatSession->assigned_to) {
            $chatSession->update(['assigned_to' => Auth::id()]);
        }

        $chatSession->update(['last_activity_at' => now()]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'sender_type' => 'agent',
            'message_type' => 'normal',
            'sender_name' => Auth::user()->name,
            'sender_department' => Auth::user()->department?->name,
            'created_at' => $message->created_at->toISOString(),
            'time' => $message->created_at->format('H:i'),
        ]);
    }

    /**
     * Send a whisper (internal message visible only to workers).
     */
    public function whisper(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'whisper_to' => 'nullable|exists:users,id',
        ]);

        // Prevent whispering directly to super_admin
        if ($request->whisper_to) {
            $recipient = User::find($request->whisper_to);
            if ($recipient && $recipient->isSuperAdmin()) {
                return response()->json(['error' => 'Cannot send whispers to super admin.'], 403);
            }
        }

        $message = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'user_id' => Auth::id(),
            'sender_type' => 'agent',
            'message_type' => 'whisper',
            'whisper_to' => $request->input('whisper_to'),
            'message' => e($request->message),
        ]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'sender_type' => 'agent',
            'message_type' => 'whisper',
            'sender_name' => Auth::user()->name,
            'sender_department' => Auth::user()->department?->name,
            'whisper_to_name' => $request->whisper_to
                ? User::find($request->whisper_to)?->name
                : null,
            'created_at' => $message->created_at->toISOString(),
            'time' => $message->created_at->format('H:i'),
        ]);
    }

    /**
     * Transfer chat to another worker/admin.
     */
    public function transfer(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'transfer_to' => 'required|exists:users,id',
            'note' => 'nullable|string|max:500',
        ]);

        $fromUser = Auth::user();
        $toUser = User::with('department')->findOrFail($request->transfer_to);

        $chatSession->update([
            'assigned_to' => $toUser->id,
            'transferred_from' => $fromUser->id,
            'transfer_note' => $request->note,
            'department_id' => $toUser->department_id,
        ]);

        // Add system message about the transfer
        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'user_id' => Auth::id(),
            'sender_type' => 'agent',
            'message_type' => 'system',
            'message' => $fromUser->name . ' transferred this chat to ' . $toUser->name
                . ($toUser->department ? ' (' . $toUser->department->name . ')' : ''),
        ]);

        return response()->json([
            'ok' => true,
            'assigned_to' => $toUser->name,
            'department' => $toUser->department?->name,
        ]);
    }

    /**
     * Get online workers for transfer/whisper UI.
     */
    public function onlineWorkers(): JsonResponse
    {
        $workers = User::whereIn('role', ['worker', 'admin'])
            ->with('department:id,name,color')
            ->select('id', 'name', 'role', 'department_id', 'profile_image')
            ->orderBy('name')
            ->get()
            ->map(fn ($w) => [
                'id' => $w->id,
                'name' => $w->name,
                'role' => $w->role,
                'department' => $w->department?->name,
                'department_color' => $w->department?->color,
                'profile_image' => $w->profile_image ? asset('storage/' . $w->profile_image) : null,
                'is_current' => $w->id === Auth::id(),
            ]);

        return response()->json(['workers' => $workers]);
    }

    /**
     * API: Set admin typing indicator.
     */
    public function typing(Request $request, ChatSession $chatSession): JsonResponse
    {
        $key = 'chat_typing_agent_' . $chatSession->id;
        if ($request->boolean('typing')) {
            cache()->put($key, Auth::user()->name, 5);
        } else {
            cache()->forget($key);
        }
        return response()->json(['ok' => true]);
    }

    /**
     * Close a chat session.
     */
    public function close(ChatSession $chatSession): JsonResponse
    {
        $chatSession->update(['status' => 'closed']);
        return response()->json(['ok' => true]);
    }

    /**
     * Missed chats page.
     */
    public function missed()
    {
        $sessions = ChatSession::where('status', 'missed')
            ->withCount('messages')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.chat.missed', compact('sessions'));
    }

    /**
     * Notifications API: unread counts for admin topbar.
     */
    public function unreadCount(): JsonResponse
    {
        $chatUnread = ChatMessage::whereHas('session', fn ($q) => $q->where('status', 'active'))
            ->where('sender_type', 'visitor')
            ->where('is_read', false)
            ->count();

        $notifications = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'chat_unread' => $chatUnread,
            'count' => $chatUnread,
            'notifications' => $notifications,
            'total' => $chatUnread + $notifications,
        ]);
    }
}
