<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Start or resume a chat session (visitor-side).
     */
    public function startSession(Request $request): JsonResponse
    {
        $visitorId = $request->input('visitor_id') ?: Str::uuid()->toString();

        $session = ChatSession::where('visitor_id', $visitorId)
            ->where('status', 'active')
            ->first();

        if (! $session) {
            $pageUrl = $request->input('page_url');
            $session = ChatSession::create([
                'visitor_id' => $visitorId,
                'visitor_name' => $request->input('visitor_name'),
                'visitor_email' => $request->input('visitor_email'),
                'visitor_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'active',
                'current_page' => $pageUrl,
                'page_history' => $pageUrl ? [$pageUrl] : [],
                'last_activity_at' => now(),
            ]);
        }

        $greeting = \App\Models\Setting::first()?->chat_greeting;

        $messages = $session->messages()->orderBy('created_at')->get()->map(fn ($m) => [
            'id' => $m->id,
            'message' => $m->message,
            'sender_type' => $m->sender_type,
            'created_at' => $m->created_at->toISOString(),
        ]);

        return response()->json([
            'session_id' => $session->id,
            'visitor_id' => $session->visitor_id,
            'messages' => $messages,
            'greeting' => $greeting,
        ]);
    }

    /**
     * Send a message (visitor-side).
     */
    public function sendMessage(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'sender_type' => 'visitor',
            'message' => e($request->message),
        ]);

        $chatSession->update(['last_activity_at' => now()]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'sender_type' => $message->sender_type,
            'created_at' => $message->created_at->toISOString(),
        ]);
    }

    /**
     * Poll for new messages (visitor-side).
     */
    public function pollMessages(Request $request, ChatSession $chatSession): JsonResponse
    {
        $afterId = $request->integer('after', 0);

        $messages = ChatMessage::where('chat_session_id', $chatSession->id)
            ->where('sender_type', 'agent')
            ->whereIn('message_type', ['normal', 'system'])
            ->when($afterId, fn ($q) => $q->where('id', '>', $afterId))
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'message' => $m->message,
                'sender_type' => $m->sender_type,
                'message_type' => $m->message_type ?? 'normal',
                'created_at' => $m->created_at->toISOString(),
            ]);

        $agentTyping = cache()->has('chat_typing_agent_' . $chatSession->id);

        // Get assigned agent info for visitor-side display
        $agentInfo = null;
        if ($chatSession->assigned_to) {
            $agent = User::with('department:id,name')->select('id', 'name', 'profile_image', 'bio', 'department_id')->find($chatSession->assigned_to);
            if ($agent) {
                $agentInfo = [
                    'name' => $agent->name,
                    'profile_image' => $agent->profile_image ? asset('storage/' . $agent->profile_image) : null,
                    'bio' => $agent->bio,
                    'department' => $agent->department?->name,
                ];
            }
        }

        return response()->json([
            'messages' => $messages,
            'agent_typing' => $agentTyping,
            'agent_info' => $agentInfo,
        ]);
    }

    /**
     * Update page tracking (visitor-side).
     */
    public function trackPage(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'url' => 'required|string|max:500',
            'title' => 'nullable|string|max:255',
            'visitor_name' => 'nullable|string|max:255',
        ]);

        $url = $request->input('url');
        $title = $request->input('title');
        $entry = $title ? ($title . ' — ' . $url) : $url;

        $history = $chatSession->page_history ?? [];
        // Only add if different from the last entry
        $lastEntry = end($history) ?: '';
        if ($url !== $lastEntry && $entry !== $lastEntry) {
            $history[] = $entry;
        }

        $updates = [
            'current_page' => $url,
            'page_history' => array_slice($history, -20),
            'last_activity_at' => now(),
        ];

        // Allow updating visitor name during the session
        if ($request->filled('visitor_name') && $request->input('visitor_name') !== $chatSession->visitor_name) {
            $updates['visitor_name'] = $request->input('visitor_name');
        }

        $chatSession->update($updates);

        return response()->json(['ok' => true]);
    }

    /**
     * Set typing status (visitor-side).
     */
    public function typing(Request $request, ChatSession $chatSession): JsonResponse
    {
        // Store typing text in cache (expires in 3 seconds)
        $key = 'chat_typing_visitor_' . $chatSession->id;
        $text = $request->input('text', '');
        cache()->put($key, $text, 3);

        return response()->json(['ok' => true]);
    }

    /**
     * Close/end session (visitor-side).
     */
    public function endSession(Request $request, ChatSession $chatSession): JsonResponse
    {
        $chatSession->update(['status' => 'closed']);

        return response()->json(['ok' => true]);
    }
}
