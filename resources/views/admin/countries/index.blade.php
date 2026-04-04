<x-app-layout>
    <x-slot name="header">Countries</x-slot>

    <div x-data="adminTable({ ids: [{{ $countries->pluck('id')->join(',') }}], key: 'countries', columns: { destinations: true, safaris: true } })">

        @include('admin.partials.table-toolbar', [
            'searchPlaceholder' => 'Search countries...',
            'createRoute' => route('admin.countries.create'),
            'createLabel' => 'New Country',
            'columnList' => ['destinations' => 'Destinations', 'safaris' => 'Safari Packages'],
        ])

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Country</th>
                        <th x-show="isVisible('destinations')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destinations</th>
                        <th x-show="isVisible('safaris')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Safari Packages</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($countries as $country)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $country->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $country->id }})" :checked="isSelected({{ $country->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($country->featured_image)
                                        <img src="{{ asset('storage/' . $country->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                    @endif
                                    <div><p class="text-sm font-semibold text-gray-900">{{ $country->name }}</p><p class="text-xs text-gray-400">{{ $country->slug }}</p></div>
                                </div>
                            </td>
                            <td x-show="isVisible('destinations')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $country->destinations_count }}</td>
                            <td x-show="isVisible('safaris')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $country->safari_packages_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('countries.show', ['slug' => $country->slug]) }}" target="_blank" class="text-gray-400 hover:text-[#083321] transition" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg></a>
                                    <a href="{{ route('admin.countries.edit', $country) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">No countries yet. <a href="{{ route('admin.countries.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($countries->hasPages())<div class="mt-4">{{ $countries->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.countries.bulk-action'), 'actions' => ['delete' => 'Delete']])
    </div>
</x-app-layout>
