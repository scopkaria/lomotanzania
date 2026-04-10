<x-app-layout>
    <x-slot name="header">Safari Packages</x-slot>

    <div x-data="adminTable({
        ids: [{{ $safaris->pluck('id')->join(',') }}],
        key: 'safaris',
        columns: { destinations: true, duration: true, price: true, status: true },
        sortField: '{{ request('sort', '') }}',
        sortDir: '{{ request('direction', 'asc') }}'
    })">

        {{-- Screen Options --}}
        <div class="mb-3 flex justify-end">
            <div class="relative">
                <button @click="showScreenOptions = !showScreenOptions" type="button" class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                    Screen Options
                </button>
                <div x-show="showScreenOptions" @click.away="showScreenOptions = false" x-cloak
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-3 px-4 z-30">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Show Columns</p>
                    @foreach(['destinations' => 'Destinations', 'duration' => 'Duration', 'price' => 'Price', 'status' => 'Status'] as $ck => $cl)
                        <label class="flex items-center gap-2 py-1 cursor-pointer">
                            <input type="checkbox" :checked="isVisible('{{ $ck }}')" @change="toggleColumn('{{ $ck }}')" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            <span class="text-sm text-gray-700">{{ $cl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search safaris..."
                               class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                    </div>
                </form>
                <form method="GET">
                    @foreach(request()->except(['status', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </form>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.safaris.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <form method="GET">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="per_page" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        @foreach([10, 25, 50, 100] as $pp)
                            <option value="{{ $pp }}" {{ (int)request('per_page', 15) === $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.safaris.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Package
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3">
                            <input type="checkbox" @click="toggleSelectAll()" :checked="selectAll"
                                   class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" @click="sortBy('title')">
                            <span class="inline-flex items-center gap-1">Package
                                <template x-if="sortField === 'title'"><svg class="w-3 h-3" :class="sortDir === 'desc' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></template>
                            </span>
                        </th>
                        <th x-show="isVisible('destinations')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destinations</th>
                        <th x-show="isVisible('duration')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                        <th x-show="isVisible('price')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th x-show="isVisible('status')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" @click="sortBy('status')">
                            <span class="inline-flex items-center gap-1">Status
                                <template x-if="sortField === 'status'"><svg class="w-3 h-3" :class="sortDir === 'desc' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></template>
                            </span>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($safaris as $safari)
                        @php
                            $seasonalValues = collect($safari->seasonal_pricing ?? [])
                                ->flatMap(fn ($season) => collect($season)->filter(fn ($value) => filled($value)));
                            $startingRate = $seasonalValues->isNotEmpty() ? (float) $seasonalValues->min() : null;
                        @endphp
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors cursor-pointer"
                            :class="isSelected({{ $safari->id }}) && 'bg-[#083321]/5'"
                            @click="rowClick('{{ route('admin.safaris.edit', $safari) }}', $event)">
                            <td class="w-10 px-4 py-3">
                                <input type="checkbox" value="{{ $safari->id }}" @click="toggleRow({{ $safari->id }})" :checked="isSelected({{ $safari->id }})"
                                       class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($safari->featured_image)
                                        <img src="{{ asset('storage/' . $safari->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.safaris.edit', $safari) }}" class="text-sm font-semibold text-gray-900 hover:text-[#FEBC11] transition-colors truncate block">{{ $safari->title }}</a>
                                        <p class="text-xs text-gray-400 truncate">{{ Str::limit($safari->short_description, 50) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td x-show="isVisible('destinations')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $safari->destinations->pluck('name')->join(', ') ?: '—' }}</td>
                            <td x-show="isVisible('duration')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $safari->duration ?? '—' }}</td>
                            <td x-show="isVisible('price')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">
                                @if($startingRate !== null)
                                    {{ $safari->currency ?? 'USD' }} {{ number_format($startingRate, 0) }}
                                @else — @endif
                            </td>
                            <td x-show="isVisible('status')" class="px-4 py-3">
                                @if($safari->status === 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}" target="_blank" class="text-gray-400 hover:text-[#083321] transition" title="View on site">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.safaris.edit', $safari) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.safaris.destroy', $safari) }}" method="POST" class="inline" onsubmit="return confirm('Delete this package?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                No safari packages yet.
                                <a href="{{ route('admin.safaris.create') }}" class="text-[#FEBC11] hover:underline ml-1">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($safaris->hasPages())
            <div class="mt-4">{{ $safaris->links() }}</div>
        @endif

        @include('admin.partials.bulk-bar', [
            'bulkRoute' => route('admin.safaris.bulk-action'),
            'actions' => ['publish' => 'Publish', 'draft' => 'Set Draft', 'delete' => 'Delete'],
        ])
    </div>
</x-app-layout>
