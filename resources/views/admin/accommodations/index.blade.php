<x-app-layout>
    <x-slot name="header">Accommodations</x-slot>

    <div x-data="adminTable({ ids: [{{ $accommodations->pluck('id')->join(',') }}], key: 'accommodations', columns: { country: true, destination: true, gallery: true, used: true } })">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search accommodations..."
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
                <form method="GET">
                    @foreach(request()->except(['category', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="category" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Categories</option>
                        @foreach(['luxury', 'high-end', 'mid-range', 'budget'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </form>
                @if(request('search') || request('country_id') || request('category'))
                    <a href="{{ route('admin.accommodations.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
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
                <a href="{{ route('admin.accommodations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Accommodation
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Accommodation</th>
                        <th x-show="isVisible('country')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Country</th>
                        <th x-show="isVisible('destination')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destination</th>
                        <th x-show="isVisible('gallery')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gallery</th>
                        <th x-show="isVisible('used')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Used In</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($accommodations as $accommodation)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $accommodation->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $accommodation->id }})" :checked="isSelected({{ $accommodation->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($accommodation->images->first())
                                        <img src="{{ asset('storage/' . $accommodation->images->first()->image_path) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5M4.5 18V9.75m0 8.25h15m-15 0v-3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V18m-15-8.25 4.816-4.012a1.125 1.125 0 011.44 0l4.819 4.012m-6.259 0V21m6-11.25V21"/></svg></div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $accommodation->name }}</p>
                                        <span class="inline-flex items-center rounded-full bg-[#FEBC11]/15 px-2 py-0.5 text-xs font-medium text-[#131414]">{{ ucfirst($accommodation->category) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td x-show="isVisible('country')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $accommodation->country?->name ?? '—' }}</td>
                            <td x-show="isVisible('destination')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $accommodation->destination?->name ?? '—' }}</td>
                            <td x-show="isVisible('gallery')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $accommodation->images_count }}</td>
                            <td x-show="isVisible('used')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $accommodation->itineraries_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.accommodations.edit', $accommodation) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.accommodations.destroy', $accommodation) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No accommodations yet. <a href="{{ route('admin.accommodations.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accommodations->hasPages())<div class="mt-4">{{ $accommodations->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.accommodations.bulk-action'), 'actions' => ['delete' => 'Delete']])
    </div>
</x-app-layout>