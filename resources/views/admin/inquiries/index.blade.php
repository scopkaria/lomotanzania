<x-app-layout>
    <x-slot name="header">Inquiries</x-slot>

    <div x-data="adminTable({ ids: [{{ $inquiries->pluck('id')->join(',') }}], key: 'inquiries', columns: { safari: true, travel: true, received: true } })">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                               class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                    </div>
                </form>
                @php $currentStatus = request('status'); @endphp
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.inquiries.index', request()->except(['status', 'page'])) }}"
                       class="px-3 py-1.5 text-sm rounded-lg transition {{ !$currentStatus ? 'bg-[#083321] text-white font-semibold' : 'text-gray-500 hover:bg-gray-100' }}">
                        All
                    </a>
                    @foreach(['new' => 'New', 'contacted' => 'Contacted', 'booked' => 'Booked'] as $key => $label)
                        <a href="{{ route('admin.inquiries.index', array_merge(request()->except(['status', 'page']), ['status' => $key])) }}"
                           class="px-3 py-1.5 text-sm rounded-lg transition {{ $currentStatus === $key ? 'bg-[#083321] text-white font-semibold' : 'text-gray-500 hover:bg-gray-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear all</a>
                @endif
            </div>
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
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th x-show="isVisible('safari')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Safari</th>
                        <th x-show="isVisible('travel')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Travel Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th x-show="isVisible('received')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Received</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors {{ $inquiry->status === 'new' ? 'bg-yellow-50/40' : '' }}" :class="isSelected({{ $inquiry->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $inquiry->id }})" :checked="isSelected({{ $inquiry->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $inquiry->name }}</p>
                                    @if($inquiry->inquiry_type)
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $inquiry->inquiry_type === 'booking' ? 'bg-[#FEBC11]/20 text-gray-900' : 'bg-blue-50 text-blue-600' }}">
                                            {{ ucfirst($inquiry->inquiry_type) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400">{{ $inquiry->email }}</p>
                                @if($inquiry->phone)
                                    <p class="text-xs text-gray-400">{{ $inquiry->phone }}</p>
                                @endif
                            </td>
                            <td x-show="isVisible('safari')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-600">
                                {{ $inquiry->safariPackage?->title ?? '—' }}
                            </td>
                            <td x-show="isVisible('travel')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">
                                {{ $inquiry->travel_date?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'new'       => 'bg-yellow-100 text-yellow-800',
                                        'contacted' => 'bg-blue-100 text-blue-800',
                                        'booked'    => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$inquiry->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </td>
                            <td x-show="isVisible('received')" class="px-4 py-3 text-xs text-gray-400">
                                {{ $inquiry->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-gray-400 hover:text-[#083321] transition" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></a>
                                    <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No inquiries yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())<div class="mt-4">{{ $inquiries->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.inquiries.bulk-action'), 'actions' => ['delete' => 'Delete']])
    </div>
</x-app-layout>
