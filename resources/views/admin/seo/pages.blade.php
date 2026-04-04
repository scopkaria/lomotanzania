<x-app-layout>
    <div x-data="{
        selected: [],
        selectAll: false,
        toggleAll() {
            if (this.selectAll) {
                this.selected = @json($pages->pluck('id'));
            } else {
                this.selected = [];
            }
        },
        get hasSelected() { return this.selected.length > 0; }
    }">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Programmatic SEO Pages</h1>
            <p class="text-sm text-gray-500 mt-1">Auto-generated landing pages targeting safari keyword combinations.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.seo.pages.generate') }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-[#FEBC11] rounded-lg text-sm font-semibold text-[#083321] hover:bg-[#e5a90f] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                    Generate Pages
                </button>
            </form>
            <a href="{{ route('admin.seo.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                ← Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 py-3 px-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl bg-white border border-gray-200 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-[#083321]">{{ $pages->total() }}</p>
            <p class="text-xs text-gray-500">Total Pages</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-green-600">{{ $pages->where('is_published', true)->count() }}</p>
            <p class="text-xs text-gray-500">Published</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-blue-600">{{ $pages->sum('views') }}</p>
            <p class="text-xs text-gray-500">Total Views</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-[#FEBC11]">{{ $pages->where('is_auto_generated', true)->count() }}</p>
            <p class="text-xs text-gray-500">Auto-Generated</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('admin.seo.pages') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Type</label>
                <select name="type" class="rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
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
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title…" class="w-full rounded-lg border-gray-300 text-sm py-1.5 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
            </div>
            <button type="submit" class="inline-flex items-center gap-1 px-4 py-1.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#0a4a30] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['type', 'status', 'search']))
                <a href="{{ route('admin.seo.pages') }}" class="text-xs text-gray-500 hover:text-red-600 underline">Clear</a>
            @endif
        </form>
    </div>

    {{-- Bulk Actions Bar --}}
    <div x-show="hasSelected" x-cloak x-transition class="rounded-xl bg-red-50 border border-red-200 p-3 mb-4 flex items-center justify-between">
        <span class="text-sm font-medium text-red-700" x-text="selected.length + ' page(s) selected'"></span>
        <form action="{{ route('admin.seo.pages.bulk-destroy') }}" method="POST" onsubmit="return confirm('Delete all selected pages?')">
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

    {{-- Pages Table --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b">
                    <tr>
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-[#083321] focus:ring-[#FEBC11]">
                        </th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Views</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pages as $page)
                    <tr class="hover:bg-gray-50" :class="selected.includes({{ $page->id }}) && 'bg-yellow-50'">
                        <td class="px-4 py-3">
                            <input type="checkbox" value="{{ $page->id }}" x-model.number="selected" class="rounded border-gray-300 text-[#083321] focus:ring-[#FEBC11]">
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 max-w-[250px] truncate">{{ $page->title }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                {{ match($page->type) {
                                    'country' => 'bg-blue-100 text-blue-700',
                                    'destination' => 'bg-green-100 text-green-700',
                                    'combo' => 'bg-purple-100 text-purple-700',
                                    'duration' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-gray-100 text-gray-700',
                                } }}">
                                {{ ucfirst($page->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">{{ $page->slug }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($page->views) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $page->is_published ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex items-center gap-2">
                            <a href="{{ route('seo.page', ['locale' => 'en', 'slug' => $page->slug]) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs" title="View in new tab">View</a>
                            <a href="{{ route('admin.seo.pages.edit', $page) }}" class="text-[#083321] hover:text-green-700 text-xs">Edit</a>
                            <form action="{{ route('admin.seo.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No SEO pages found. {{ request()->hasAny(['type','status','search']) ? 'Try adjusting your filters.' : 'Click "Generate Pages" to create them.' }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="border-t px-4 py-3">{{ $pages->links() }}</div>
        @endif
    </div>

    </div>
</x-app-layout>
