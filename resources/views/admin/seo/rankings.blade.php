<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keyword Rankings</h1>
            <p class="text-sm text-gray-500 mt-1">Track your keyword positions in Google search results.</p>
        </div>
        <a href="{{ route('admin.seo.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
            ← SEO Dashboard
        </a>
    </div>

    {{-- Add Keyword Form --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Track New Keyword</h3>
            <button @click="open = !open" type="button" class="text-sm text-brand-gold hover:underline" x-text="open ? 'Cancel' : '+ Add Keyword'"></button>
        </div>

        <form x-show="open" x-collapse method="POST" action="{{ route('admin.seo.keywords.add') }}" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                <input type="text" name="keyword" required placeholder="e.g. Serengeti safari packages"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target URL</label>
                <input type="text" name="url" required placeholder="{{ url('/en/safaris/serengeti') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-brand-dark text-white text-sm rounded-lg hover:bg-gray-800 transition-colors">
                    Start Tracking
                </button>
            </div>
        </form>

        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700" x-show="open" x-collapse>
            <strong>Setup:</strong> For accurate ranking data, add Google Custom Search API credentials to <code>.env</code>:
            <code class="block mt-1 bg-blue-100 p-2 rounded">GOOGLE_CSE_API_KEY=your_key<br>GOOGLE_CSE_CX=your_search_engine_id</code>
            <p class="mt-1">Free tier: 100 queries/day. <a href="https://programmablesearchengine.google.com/" target="_blank" class="underline">Get API key →</a></p>
        </div>
    </div>

    {{-- Rankings Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Keyword</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">URL</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Position</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Change</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Last Checked</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rankings as $ranking)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $ranking->keyword }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs truncate max-w-[200px]">{{ $ranking->url }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($ranking->position && $ranking->position < 99)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $ranking->position <= 3 ? 'bg-green-100 text-green-700' : ($ranking->position <= 10 ? 'bg-blue-100 text-blue-700' : ($ranking->position <= 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                                    #{{ $ranking->position }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">Not ranked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($ranking->change !== null)
                                @if($ranking->change > 0)
                                    <span class="text-green-600 font-semibold">↑{{ $ranking->change }}</span>
                                @elseif($ranking->change < 0)
                                    <span class="text-red-600 font-semibold">↓{{ abs($ranking->change) }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs">New</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            {{ $ranking->last_checked_at ? $ranking->last_checked_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('admin.seo.keywords.remove', $ranking) }}"
                                  onsubmit="return confirm('Remove this keyword from tracking?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No keywords tracked yet. Add a keyword above to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($rankings->isNotEmpty())
    <div class="mt-4 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
        <strong>Tips:</strong> Run <code class="bg-white px-1 rounded border">php artisan seo:check-rankings</code> to update positions.
        Schedule it daily in your cron: <code class="bg-white px-1 rounded border">* * * * * php artisan schedule:run</code>
    </div>
    @endif
</x-app-layout>
