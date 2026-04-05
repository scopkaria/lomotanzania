<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SEO Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor content scores, keyword rankings, and optimization opportunities.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.seo.analyze-all') }}" method="POST" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<svg class=\'animate-spin w-4 h-4\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z\'></path></svg> Analyzing…';">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white rounded-lg text-sm hover:bg-[#0a4a30] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/></svg>
                    Analyze All Content
                </button>
            </form>
            <a href="{{ route('admin.seo.rankings') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Rankings
            </a>
            <a href="{{ route('admin.seo.search-engines') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                Search Engines
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Overall Score Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @php
            $totalAnalyzed = collect($scoreOverview)->sum('analyzed');
            $totalGood = collect($scoreOverview)->sum('good');
            $totalNeedsWork = collect($scoreOverview)->sum('needs_work');
            $totalPoor = collect($scoreOverview)->sum('poor');
            $overallAvg = $totalAnalyzed > 0 ? round(collect($scoreOverview)->avg('avg_score')) : 0;
        @endphp

        {{-- Overall score ring --}}
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-6">
            <div class="relative w-20 h-20 flex-shrink-0">
                <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e5e7eb" stroke-width="2.5"/>
                    <circle cx="18" cy="18" r="15.5" fill="none"
                        stroke="{{ $overallAvg >= 71 ? '#22c55e' : ($overallAvg >= 41 ? '#eab308' : '#ef4444') }}"
                        stroke-width="2.5" stroke-dasharray="97.4"
                        stroke-dashoffset="{{ 97.4 - (97.4 * $overallAvg / 100) }}" stroke-linecap="round"/>
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-xl font-bold {{ $overallAvg >= 71 ? 'text-green-600' : ($overallAvg >= 41 ? 'text-yellow-600' : 'text-red-600') }}">{{ $overallAvg }}</span>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Overall SEO Score</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $totalAnalyzed }} pages analyzed</p>
            </div>
        </div>

        {{-- Score distribution --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Score Distribution</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width:{{ $totalAnalyzed ? ($totalGood / $totalAnalyzed * 100) : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-700 w-16 text-right">{{ $totalGood }} good</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 flex-shrink-0"></span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 rounded-full" style="width:{{ $totalAnalyzed ? ($totalNeedsWork / $totalAnalyzed * 100) : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-700 w-16 text-right">{{ $totalNeedsWork }} OK</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 rounded-full" style="width:{{ $totalAnalyzed ? ($totalPoor / $totalAnalyzed * 100) : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-700 w-16 text-right">{{ $totalPoor }} poor</span>
                </div>
            </div>
        </div>

        {{-- Keyword Rankings Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Keyword Rankings</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $topKeywords->count() }}</p>
                    <p class="text-xs text-gray-500">Top 10</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ $improvingKeywords->count() }}</p>
                    <p class="text-xs text-gray-500">Rising</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $droppingKeywords->count() }}</p>
                    <p class="text-xs text-gray-500">Dropping</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Scores by Type --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Content SEO Scores</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Content Type</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Total</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Analyzed</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Avg Score</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Good</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Needs Work</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Poor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($scoreOverview as $type => $data)
                    <tr>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $type }}</td>
                        <td class="px-6 py-3 text-center text-gray-600">{{ $data['total'] }}</td>
                        <td class="px-6 py-3 text-center text-gray-600">{{ $data['analyzed'] }}</td>
                        <td class="px-6 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                {{ $data['avg_score'] >= 71 ? 'bg-green-100 text-green-700' : ($data['avg_score'] >= 41 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $data['avg_score'] }}%
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center text-green-600 font-medium">{{ $data['good'] }}</td>
                        <td class="px-6 py-3 text-center text-yellow-600 font-medium">{{ $data['needs_work'] }}</td>
                        <td class="px-6 py-3 text-center text-red-600 font-medium">{{ $data['poor'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Items needing attention --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <h3 class="font-semibold text-gray-900">Needs Attention</h3>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($lowScoreItems as $item)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            @php
                                $seoable = $item->seoable;
                                $name = '(Deleted)';
                                if ($seoable) {
                                    if (is_array($seoable->title ?? null)) {
                                        $name = $seoable->title['en'] ?? 'Untitled';
                                    } else {
                                        $name = $seoable->title ?? $seoable->name ?? 'Untitled';
                                    }
                                }
                            @endphp
                            {{ $name }}
                        </p>
                        <p class="text-xs text-gray-500">{{ class_basename($item->seoable_type) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                        {{ $item->seo_score >= 41 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700' }}">
                        {{ $item->seo_score }}%
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-gray-400 mb-3">No low-score items found.</p>
                    <form action="{{ route('admin.seo.analyze-all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white rounded-lg text-sm hover:bg-[#0a4a30] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/></svg>
                            Analyze All Content
                        </button>
                    </form>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Keyword Rankings --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    <h3 class="font-semibold text-gray-900">Tracked Keywords</h3>
                </div>
                <a href="{{ route('admin.seo.rankings') }}" class="text-xs text-brand-gold hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($rankings->take(10) as $rank)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $rank->keyword }}</p>
                        <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $rank->url }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($rank->position)
                            <span class="text-sm font-bold {{ $rank->position <= 10 ? 'text-green-600' : ($rank->position <= 20 ? 'text-yellow-600' : 'text-gray-500') }}">
                                #{{ $rank->position }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">N/A</span>
                        @endif
                        @if($rank->change)
                            <span class="text-xs font-semibold {{ $rank->change > 0 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $rank->change > 0 ? '↑' : '↓' }}{{ abs($rank->change) }}
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    No keywords tracked yet. <a href="{{ route('admin.seo.rankings') }}" class="text-brand-gold hover:underline">Add keywords</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
