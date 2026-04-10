<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Department;
use App\Models\Destination;
use App\Models\SafariPackage;
use App\Models\User;
use App\Notifications\LiveSupportRequested;
use App\Support\LocalizedPublicUrl;
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

        // Always close any existing active session so each visit starts fresh
        if ($session) {
            $session->update(['status' => 'closed']);
            $session = null;
        }

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
            $agent = User::with('department:id,name,color')->select('id', 'name', 'profile_image', 'bio', 'department_id')->find($chatSession->assigned_to);
            if ($agent) {
                $agentInfo = [
                    'name' => $agent->name,
                    'profile_image' => $agent->profile_image ? asset('storage/' . $agent->profile_image) : null,
                    'bio' => $agent->bio,
                    'department' => $agent->department?->name,
                    'department_color' => $agent->department?->color,
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
     * Submit a lead/offline message (visitor-side).
     */
    public function lead(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Save as a system message so admins can see it
        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'sender_type' => 'visitor',
            'message_type' => 'system',
            'message' => '[Lead Capture] ' . e($request->input('name', 'Visitor')) . ' (' . e($request->email) . '): ' . e($request->message),
        ]);

        // Update session with lead info
        $chatSession->update([
            'visitor_name' => $request->input('name', $chatSession->visitor_name),
            'visitor_email' => $request->input('email', $chatSession->visitor_email),
            'last_activity_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Generate a smart AI response based on database content.
     * Uses OpenAI when configured, falls back to keyword matching.
     */
    public function aiResponse(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $locale = $chatSession->inferredLocale();

        // Try AI service first (Gemini / OpenAI)
        $aiService = app(\App\Services\AiChatService::class);
        if ($aiService->isEnabled()) {
            $aiReply = $aiService->respond($request->input('message'), $chatSession);
            if ($aiReply) {
                return response()->json([
                    'response' => $aiReply,
                    'provider' => $aiService->getProvider(),
                ]);
            }
        }

        // Fallback to keyword-based responses
        $text = mb_strtolower(trim($request->input('message')));
        $searchText = str_replace(['%', '_'], ['\\%', '\\_'], $text);
        $response = null;

        // 1. Check for greeting patterns
        if (preg_match('/^(hi|hello|hey|good\s*(morning|afternoon|evening)|howdy|hola|greetings)/i', $text)) {
            $greetings = [
                "Hello! 👋 Welcome to Lomo Tanzania Safari! How can I help you plan an unforgettable adventure?",
                "Hi there! 🌍 Great to have you here. Are you looking to explore Tanzania's amazing wildlife?",
                "Welcome! 😊 I'm the AI Safari Agent. I can help with packages, pricing, and destinations. What interests you?",
            ];
            return response()->json(['response' => $greetings[array_rand($greetings)]]);
        }

        // 2. Check for thanks patterns
        if (preg_match('/\b(thank|thanks|thx|appreciate|grateful)\b/i', $text)) {
            $thanks = [
                "You're welcome! 😊 Is there anything else I can help with?",
                "Happy to help! 🎉 Don't hesitate to ask more questions.",
            ];
            return response()->json(['response' => $thanks[array_rand($thanks)]]);
        }

        // 3. Search safari packages by keyword
        $safaris = SafariPackage::where('status', 'published')
            ->where(function ($q) use ($searchText) {
                $q->where('title', 'like', "%{$searchText}%")
                  ->orWhere('short_description', 'like', "%{$searchText}%")
                  ->orWhere('description', 'like', "%{$searchText}%");
            })
            ->select('title', 'slug', 'duration', 'price')
            ->take(3)
            ->get();

        if ($safaris->isNotEmpty()) {
            $links = $safaris->map(function ($s) use ($locale) {
                $url = LocalizedPublicUrl::route('safaris.show', ['slug' => $s->slug], $locale);

                return "<a href='" . e($url) . "' target='_blank' class='underline font-medium'>" . e($s->title) . "</a>" . ($s->duration ? " (" . e($s->duration) . ")" : '');
            })->join(', ');

            return response()->json([
                'response' => "🦁 I found some matching safaris: {$links}. Would you like more details on any of these?",
            ]);
        }

        // 4. Search destinations by keyword
        $destinations = Destination::where('name', 'like', "%{$searchText}%")
            ->orWhere('description', 'like', "%{$searchText}%")
            ->select('name', 'slug')
            ->take(3)
            ->get();

        if ($destinations->isNotEmpty()) {
            $links = $destinations->map(function ($d) use ($locale) {
                $url = LocalizedPublicUrl::route('destinations.show', ['slug' => $d->slug], $locale);

                return "<a href='" . e($url) . "' target='_blank' class='underline font-medium'>" . e($d->name) . "</a>";
            })->join(', ');

            return response()->json([
                'response' => "🌅 Check out these destinations: {$links}. They offer incredible wildlife experiences!",
            ]);
        }

        // 5. Topic-based responses from common keywords
        if (preg_match('/\b(price|pricing|cost|how much|budget|expensive|cheap|afford|money|rate|rates|per person)\b/i', $text)) {
            $cheapest = SafariPackage::where('status', 'published')->whereNotNull('price')->where('price', '>', 0)->orderBy('price')->first();
            $priceNote = $cheapest ? " Starting from \${$cheapest->price} per person." : '';
            return response()->json([
                'response' => "💰 Our safari packages vary by duration and accommodation type.{$priceNote} Would you like a custom quote? An agent will be with you shortly!",
            ]);
        }

        if (preg_match('/\b(book|booking|reserve|reservation|confirm|availability|available|when)\b/i', $text)) {
            $customTourUrl = LocalizedPublicUrl::route('custom-tour', [], $locale);

            return response()->json([
                'response' => "📋 To book, we need travel dates, group size, and preferred destinations. An agent will assist shortly, or <a href='" . e($customTourUrl) . "' target='_blank' class='underline font-medium'>plan your custom tour here →</a>",
            ]);
        }

        if (preg_match('/\b(package|safari|tour|trip|itinerary)\b/i', $text)) {
            $count = SafariPackage::where('status', 'published')->count();
            $safarisUrl = LocalizedPublicUrl::route('safaris.index', [], $locale);

            return response()->json([
                'response' => "🦁 We have {$count} safari packages available! <a href='" . e($safarisUrl) . "' target='_blank' class='underline font-medium'>Browse all packages →</a> or tell me your preferences (duration, budget, interests).",
            ]);
        }

        if (preg_match('/\b(serengeti|ngorongoro|tarangire|kilimanjaro|zanzibar|manyara|selous|ruaha|destination)\b/i', $text)) {
            $destinationsUrl = LocalizedPublicUrl::route('destinations.index', [], $locale);

            return response()->json([
                'response' => "🌅 Tanzania has world-class destinations! Top picks: Serengeti (migration), Ngorongoro Crater (Big Five), Kilimanjaro, and Zanzibar. <a href='" . e($destinationsUrl) . "' target='_blank' class='underline font-medium'>Explore all destinations →</a>",
            ]);
        }

        // 6. Fallback — ask what they need and offer live support
        return response()->json([
            'response' => "I'm not sure I fully understand your question. 🤔 Could you tell me more about what you're looking for? I can help with safari packages, destinations, pricing, and bookings. Or if you'd prefer, I can connect you with our live support team!",
            'offer_support' => true,
        ]);
    }

    /**
     * Return active departments for live support selection.
     */
    public function departments(): JsonResponse
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        return response()->json(['departments' => $departments]);
    }

    /**
     * Request live support — assigns department, notifies admin agents.
     */
    public function requestSupport(Request $request, ChatSession $chatSession): JsonResponse
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $department = Department::find($request->department_id);

        $chatSession->update([
            'department_id' => $department->id,
            'last_activity_at' => now(),
        ]);

        // Add system message so admins see the request
        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'sender_type' => 'visitor',
            'message_type' => 'system',
            'message' => '🔔 ' . ($chatSession->visitor_name ?: 'Visitor') . ' requested live support from ' . $department->name . ' department.',
        ]);

        // Notify all admin agents in this department (or all admins if none in dept)
        $agents = User::where('department_id', $department->id)->get();
        if ($agents->isEmpty()) {
            $agents = User::whereNotNull('email')->get();
        }
        foreach ($agents as $agent) {
            $agent->notify(new LiveSupportRequested($chatSession, $department));
        }

        return response()->json([
            'ok' => true,
            'department' => [
                'id' => $department->id,
                'name' => $department->name,
                'color' => $department->color,
            ],
        ]);
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
