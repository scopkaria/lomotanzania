<x-app-layout>
    <x-slot name="header">Testimonials</x-slot>

    <div x-data="adminTable({ ids: [{{ $testimonials->pluck('id')->join(',') }}], key: 'testimonials', columns: { safari: true, rating: true, status: true } })">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                               class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                    </div>
                </form>
                <form method="GET">
                    @foreach(request()->except(['status', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Status</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </form>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.testimonials.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
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
                <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Testimonial
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Guest</th>
                        <th x-show="isVisible('safari')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Safari Package</th>
                        <th x-show="isVisible('rating')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                        <th x-show="isVisible('status')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($testimonials as $testimonial)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $testimonial->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $testimonial->id }})" :checked="isSelected({{ $testimonial->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $testimonial->name }}</p>
                                <p class="text-xs text-gray-400 line-clamp-1">{{ Str::limit($testimonial->message, 60) }}</p>
                            </td>
                            <td x-show="isVisible('safari')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">{{ $testimonial->safariPackage->title ?? '—' }}</td>
                            <td x-show="isVisible('rating')" class="hidden md:table-cell px-4 py-3">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-[#FEBC11]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </td>
                            <td x-show="isVisible('status')" class="px-4 py-3">
                                @if($testimonial->approved)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">No testimonials yet. <a href="{{ route('admin.testimonials.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())<div class="mt-4">{{ $testimonials->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.testimonials.bulk-action'), 'actions' => ['approve' => 'Approve', 'delete' => 'Delete']])
    </div>
</x-app-layout>
