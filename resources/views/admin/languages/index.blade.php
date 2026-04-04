<x-app-layout>
    <div x-data="adminTable({ ids: [{{ $languages->pluck('id')->join(',') }}], key: 'languages', columns: { native: true, default: true } })">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Languages</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage the languages available on your site.</p>
            </div>
            <a href="{{ route('admin.languages.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Language
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Flag</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Language</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                        <th x-show="isVisible('native')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Native Name</th>
                        <th x-show="isVisible('default')" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Default</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Active</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($languages as $lang)
                    <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $lang->id }}) && 'bg-[#083321]/5'">
                        <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $lang->id }})" :checked="isSelected({{ $lang->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                        <td class="px-4 py-3 text-xl">{{ $lang->flag }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $lang->name }}</td>
                        <td class="px-4 py-3"><code class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $lang->code }}</code></td>
                        <td x-show="isVisible('native')" class="px-4 py-3 text-sm text-gray-600">{{ $lang->native_name }}</td>
                        <td x-show="isVisible('default')" class="px-4 py-3 text-center">
                            @if($lang->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#FEBC11]/20 text-gray-900">Default</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lang->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                {{ $lang->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.languages.edit', $lang) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                @unless($lang->is_default)
                                <form method="POST" action="{{ route('admin.languages.destroy', $lang) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.languages.bulk-action'), 'actions' => ['activate' => 'Activate', 'deactivate' => 'Deactivate', 'delete' => 'Delete']])
    </div>
</x-app-layout>
