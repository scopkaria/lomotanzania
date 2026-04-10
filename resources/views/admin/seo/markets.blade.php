<x-app-layout>
    <div x-data="{
        selected: [],
        selectAll: false,
        toggleAll() {
            if (this.selectAll) {
                this.selected = @json($markets->pluck('id'));
            } else {
                this.selected = [];
            }
        },
        get hasSelected() { return this.selected.length > 0; }
    }">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">GEO Market Pages</h1>
        <form action="{{ route('admin.seo.markets.generate') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#083321] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Generate Market Pages
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Markets</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $markets->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Published</p>
            <p class="text-2xl font-bold text-[#083321] mt-1">{{ $markets->where('is_published', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Views</p>
            <p class="text-2xl font-bold text-[#FEBC11] mt-1">{{ number_format($markets->sum('views')) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('admin.seo.markets') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Source Market</label>
                <select name="source" class="rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">All Sources</option>
                    @foreach($sources as $source)
                        <option value="{{ $source }}" {{ request('source') === $source ? 'selected' : '' }}>{{ $source }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Target Country</label>
                <select name="target" class="rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">All Countries</option>
                    @foreach($targets as $target)
                        <option value="{{ $target }}" {{ request('target') === $target ? 'selected' : '' }}>{{ $target }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">All</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title…" class="w-full rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
            </div>
            <button type="submit" class="inline-flex items-center gap-1 px-4 py-1.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#0a4a30] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['source', 'target', 'status', 'search']))
                <a href="{{ route('admin.seo.markets') }}" class="text-xs text-gray-500 hover:text-red-600 underline">Clear</a>
            @endif
        </form>
    </div>

    {{-- Bulk Actions Bar --}}
    <div x-show="hasSelected" x-cloak x-transition class="rounded-xl bg-red-50 border border-red-200 p-3 mb-4 flex items-center justify-between">
        <span class="text-sm font-medium text-red-700" x-text="selected.length + ' market(s) selected'"></span>
        <form action="{{ route('admin.seo.markets.bulk-destroy') }}" method="POST" onsubmit="return confirm('Delete all selected market pages?')">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" class="inline-flex items-center gap-1 px-4 py-1.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Selected
            </button>
        </form>
    </div>

    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                <tr>
                    <th class="px-5 py-3 w-10">
                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-[#083321] focus:ring-[#FEBC11]">
                    </th>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">Source → Target</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Views</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($markets as $market)
                    <tr class="hover:bg-gray-50" :class="selected.includes({{ $market->id }}) && 'bg-yellow-50'">
                        <td class="px-5 py-3">
                            <input type="checkbox" value="{{ $market->id }}" x-model.number="selected" class="rounded border-gray-300 text-[#083321] focus:ring-[#FEBC11]">
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ Str::limit($market->title, 50) }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $market->source_market }} → {{ $market->target_country }}</td>
                        <td class="px-5 py-3">
                            @if($market->is_published)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Published</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ number_format($market->views) }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('seo.market', ['locale' => app()->getLocale(), 'slug' => $market->slug]) }}" target="_blank" class="text-gray-400 hover:text-blue-600" title="View in new tab">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.seo.markets.edit', $market) }}" class="text-gray-400 hover:text-[#FEBC11]" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.seo.markets.destroy', $market) }}" method="POST" onsubmit="return confirm('Delete this market page?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No market pages found. {{ request()->hasAny(['source','target','status','search']) ? 'Try adjusting your filters.' : 'Click "Generate Market Pages" to create them.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($markets->hasPages())
        <div class="mt-4">{{ $markets->links() }}</div>
    @endif

    </div>
</x-app-layout>
