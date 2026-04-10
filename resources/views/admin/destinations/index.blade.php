<x-app-layout>
    <x-slot name="header">Destinations</x-slot>

    <div x-data="adminTable({
        ids: [{{ $destinations->pluck('id')->join(',') }}],
        key: 'destinations',
        columns: { country: true, coords: true, safaris: true },
        sortField: '{{ request('sort', '') }}',
        sortDir: '{{ request('direction', 'asc') }}'
    })">

        {{-- ADDED: Screen Options dropdown --}}
        <div class="mb-3 flex justify-end">
            <div class="relative">
                <button @click="showScreenOptions = !showScreenOptions" type="button" class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                    Screen Options
                </button>
                <div x-show="showScreenOptions" @click.away="showScreenOptions = false" x-cloak
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-3 px-4 z-30">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Show Columns</p>
                    @foreach(['country' => 'Country', 'coords' => 'Coordinates', 'safaris' => 'Safari Count'] as $ck => $cl)
                        <label class="flex items-center gap-2 py-1 cursor-pointer">
                            <input type="checkbox" :checked="isVisible('{{ $ck }}')" @change="toggleColumn('{{ $ck }}')" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            <span class="text-sm text-gray-700">{{ $cl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Toolbar with country filter --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search destinations..."
                               class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                    </div>
                </form>
                <form method="GET">
                    @foreach(request()->except(['country_id', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="country_id" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Countries</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </form>
                @if(request('search') || request('country_id'))
                    <a href="{{ route('admin.destinations.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
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
                <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Destination
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        {{-- UPDATED: Sortable column header --}}
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" @click="sortBy('name')">
                            <span class="inline-flex items-center gap-1">Destination
                                <template x-if="sortField === 'name'"><svg class="w-3 h-3" :class="sortDir === 'desc' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></template>
                            </span>
                        </th>
                        <th x-show="isVisible('country')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Country</th>
                        <th x-show="isVisible('coords')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Coordinates</th>
                        <th x-show="isVisible('safaris')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" @click="sortBy('safari_packages_count')">
                            <span class="inline-flex items-center gap-1">Safaris
                                <template x-if="sortField === 'safari_packages_count'"><svg class="w-3 h-3" :class="sortDir === 'desc' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></template>
                            </span>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($destinations as $destination)
                        {{-- UPDATED: Clickable row --}}
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors cursor-pointer"
                            :class="isSelected({{ $destination->id }}) && 'bg-[#083321]/5'"
                            @click="rowClick('{{ route('admin.destinations.edit', $destination) }}', $event)">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $destination->id }})" :checked="isSelected({{ $destination->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($destination->featured_image)
                                        <img src="{{ asset('storage/' . $destination->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                    @endif
                                    {{-- UPDATED: Title link opens edit page --}}
                                    <div>
                                        <a href="{{ route('admin.destinations.edit', $destination) }}" class="text-sm font-semibold text-gray-900 hover:text-[#FEBC11] transition-colors">{{ $destination->name }}</a>
                                        <p class="text-xs text-gray-400">{{ $destination->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td x-show="isVisible('country')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $destination->country->name ?? '—' }}</td>
                            <td x-show="isVisible('coords')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">
                                @if($destination->latitude && $destination->longitude)
                                    {{ number_format($destination->latitude, 4) }}, {{ number_format($destination->longitude, 4) }}
                                @else — @endif
                            </td>
                            <td x-show="isVisible('safaris')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $destination->safari_packages_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('destinations.show', ['locale' => app()->getLocale(), 'slug' => $destination->slug]) }}" target="_blank" class="text-gray-400 hover:text-[#083321] transition" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg></a>
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">No destinations yet. <a href="{{ route('admin.destinations.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($destinations->hasPages())<div class="mt-4">{{ $destinations->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.destinations.bulk-action'), 'actions' => ['delete' => 'Delete']])
    </div>
</x-app-layout>
