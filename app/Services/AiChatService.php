<?php

namespace App\Services;

use App\Models\ChatSession;
use App\Models\Destination;
use App\Models\SafariPackage;
use App\Support\LocalizedPublicUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    protected ?string $openaiKey;
    protected ?string $geminiKey;
    protected string $openaiModel;
    protected string $geminiModel;
    protected string $provider; // 'gemini', 'openai', or 'none'

    public function __construct()
    {
        $this->openaiKey   = config('services.openai.api_key');
        $this->openaiModel = config('services.openai.model', 'gpt-4o-mini');
        $this->geminiKey   = config('services.gemini.api_key');
        $this->geminiModel = config('services.gemini.model', 'gemini-2.0-flash');

        // Prefer Gemini (free tier), fall back to OpenAI
        if (!empty($this->geminiKey)) {
            $this->provider = 'gemini';
        } elseif (!empty($this->openaiKey)) {
            $this->provider = 'openai';
        } else {
            $this->provider = 'none';
        }
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function isEnabled(): bool
    {
        return $this->provider !== 'none';
    }

    /**
     * Create an HTTP client that works in both local (WAMP) and production.
     */
    protected function http(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout(15);

        // WAMP / local environments often lack a CA bundle for cURL
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    public function respond(string $visitorMessage, ChatSession $chatSession): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        try {
            $locale = $chatSession->inferredLocale();
            $context = $this->buildContext($visitorMessage, $locale);
            $history = $this->getConversationHistory($chatSession);

            $response = match ($this->provider) {
                'gemini' => $this->respondViaGemini($visitorMessage, $context, $history, $locale),
                'openai' => $this->respondViaOpenAI($visitorMessage, $context, $history, $locale),
                default  => null,
            };

            return $this->normalizeInternalLinks($response, $locale);
        } catch (\Throwable $e) {
            Log::warning('AI chat error: ' . $e->getMessage());
            return null;
        }
    }

    protected function respondViaOpenAI(string $message, string $context, array $history, string $locale): ?string
    {
        $messages = array_merge(
            [['role' => 'system', 'content' => $this->systemPrompt($context, $locale)]],
            $history,
            [['role' => 'user', 'content' => $message]]
        );

        $response = $this->http()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiKey,
                'Content-Type'  => 'application/json',
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'       => $this->openaiModel,
                'messages'    => $messages,
                'max_tokens'  => 300,
                'temperature' => 0.7,
            ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        Log::warning('OpenAI API error', ['status' => $response->status(), 'body' => $response->body()]);
        return null;
    }

    protected function respondViaGemini(string $message, string $context, array $history, string $locale): ?string
    {
        // Build Gemini contents array
        $contents = [];

        foreach ($history as $msg) {
            $contents[] = [
                'role'  => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $message]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $this->systemPrompt($context, $locale)]],
            ],
            'contents'           => $contents,
            'generationConfig'   => [
                'maxOutputTokens' => 300,
                'temperature'     => 0.7,
            ],
        ];

        // Try primary model, then fallback to lite model on rate limit
        $models = [$this->geminiModel];
        if ($this->geminiModel !== 'gemini-2.0-flash-lite') {
            $models[] = 'gemini-2.0-flash-lite';
        }

        foreach ($models as $model) {
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
                 . $model . ':generateContent?key=' . $this->geminiKey;

            $response = $this->http()->post($url, $payload);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            // If not a rate limit error, don't try next model
            if ($response->status() !== 429) {
                Log::warning('Gemini API error', ['model' => $model, 'status' => $response->status(), 'body' => substr($response->body(), 0, 300)]);
                return null;
            }

            Log::info("Gemini rate limited on {$model}, trying next model...");
        }

        Log::warning('Gemini API: all models rate limited');
        return null;
    }

    protected function systemPrompt(string $context, string $locale): string
    {
        $homeUrl = LocalizedPublicUrl::route('home', [], $locale);
        $safarisUrl = LocalizedPublicUrl::route('safaris.index', [], $locale);
        $destinationsUrl = LocalizedPublicUrl::route('destinations.index', [], $locale);
        $customTourUrl = LocalizedPublicUrl::route('custom-tour', [], $locale);
        $planSafariUrl = LocalizedPublicUrl::route('plan-safari', [], $locale);
        $contactUrl = LocalizedPublicUrl::route('contact', [], $locale);

        return <<<PROMPT
You are a friendly safari concierge for Lomo Tanzania Safari, a premium tour operator specializing in Tanzania safaris, trekking, and beach holidays.

Your role:
- Help visitors find safari packages, answer questions about destinations, pricing, and travel planning
- Be warm, enthusiastic, and knowledgeable about Tanzania wildlife and nature
- Keep responses concise (2-4 sentences max) and conversational
- When relevant, suggest specific packages or destinations from the context below
- For booking requests, guide them to the plan-safari page or suggest they ask for a custom quote
- Use emojis sparingly (1-2 per message max)
- Never make up information not in the context. If unsure, say an agent will follow up
- Always keep the visitor's locale prefix on internal links. Use only these localized paths for internal links:
    Home={$homeUrl}
    Safaris={$safarisUrl}
    Destinations={$destinationsUrl}
    Custom Tour={$customTourUrl}
    Plan Safari={$planSafariUrl}
    Contact={$contactUrl}
- Format internal links as HTML: <a href="{$safarisUrl}" target="_blank" class="underline font-medium">text</a>

Available safari and destination information:
{$context}
PROMPT;
    }

        protected function buildContext(string $message, string $locale): string
    {
        $searchText = str_replace(['%', '_'], ['\\%', '\\_'], mb_strtolower(trim($message)));

        $safaris = SafariPackage::where('status', 'published')
            ->where(function ($q) use ($searchText) {
                $q->where('title', 'like', "%{$searchText}%")
                  ->orWhere('short_description', 'like', "%{$searchText}%");
            })
            ->select('title', 'slug', 'duration', 'price', 'short_description')
            ->take(5)
            ->get();

        $destinations = Destination::where('name', 'like', "%{$searchText}%")
            ->select('name', 'slug', 'description')
            ->take(3)
            ->get();

        $totalSafaris = SafariPackage::where('status', 'published')->count();
        $cheapest = SafariPackage::where('status', 'published')
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->orderBy('price')
            ->first();

        $context = "Total published safaris: {$totalSafaris}\n";
        if ($cheapest) {
            $context .= "Starting price: \${$cheapest->price} per person\n";
        }
        $context .= 'Website links: '
            . 'Home=' . LocalizedPublicUrl::route('home', [], $locale)
            . ', Safaris=' . LocalizedPublicUrl::route('safaris.index', [], $locale)
            . ', Custom Tour=' . LocalizedPublicUrl::route('custom-tour', [], $locale)
            . ', Destinations=' . LocalizedPublicUrl::route('destinations.index', [], $locale)
            . ', Contact=' . LocalizedPublicUrl::route('contact', [], $locale)
            . ", Plan Safari=" . LocalizedPublicUrl::route('plan-safari', [], $locale)
            . "\n\n";

        if ($safaris->isNotEmpty()) {
            $context .= "Matching safaris:\n";
            foreach ($safaris as $s) {
                $context .= "- {$s->title} (slug: {$s->slug}, duration: {$s->duration}, price: \${$s->price})\n";
            }
        }

        if ($destinations->isNotEmpty()) {
            $context .= "\nMatching destinations:\n";
            foreach ($destinations as $d) {
                $context .= "- {$d->name} (slug: {$d->slug})\n";
            }
        }

        return $context;
    }

    protected function getConversationHistory(ChatSession $chatSession): array
    {
        // Get last 10 messages for context
        $messages = $chatSession->messages()
            ->where('message_type', 'normal')
            ->whereIn('sender_type', ['visitor', 'bot'])
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->values();

        return $messages->map(fn ($m) => [
            'role'    => $m->sender_type === 'visitor' ? 'user' : 'assistant',
            'content' => $m->message,
        ])->toArray();
    }

    protected function normalizeInternalLinks(?string $response, string $locale): ?string
    {
        if (! is_string($response) || trim($response) === '') {
            return $response;
        }

        return preg_replace_callback(
            '/href=("|\')(\/(?!\/)[^"\']*)\1/i',
            fn (array $matches) => 'href=' . $matches[1] . LocalizedPublicUrl::path($matches[2], $locale) . $matches[1],
            $response
        );
    }
}
