<x-app-layout>
    <x-slot name="header">TripAdvisor Reviews</x-slot>

    <div x-data="{ selected: [], selectAll: false, toggleAll() { this.selectAll = !this.selectAll; this.selected = this.selectAll ? [{{ $reviews->pluck('id')->join(',') }}] : []; }, toggle(id) { if(this.selected.includes(id)) { this.selected = this.selected.filter(i => i !== id); this.selectAll = false; } else { this.selected.push(id); } } }">

        {{-- Flash messages --}}
        @foreach(['success' => 'green', 'error' => 'red', 'info' => 'blue'] as $type => $color)
            @if(session($type))
                <div class="mb-4 px-4 py-3 rounded-lg bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-800 text-sm flex items-center gap-2">
                    @if($type === 'success')<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                    @if($type === 'error')<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>@endif
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        {{-- Top bar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Search --}}
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..."
                               class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                    </div>
                </form>

                {{-- Status filter --}}
                <form method="GET">
                    @foreach(request()->except(['status', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </form>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.tripadvisor.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tripadvisor.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Review
                </a>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            @php
                $totalReviews = \App\Models\TripadvisorReview::count();
                $publishedReviews = \App\Models\TripadvisorReview::where('published', true)->count();
                $avgRating = \App\Models\TripadvisorReview::avg('rating');
            @endphp
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Reviews</p>
                <p class="text-2xl font-bold text-[#083321] mt-1">{{ $totalReviews }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Published</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $publishedReviews }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Avg Rating</p>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-2xl font-bold text-[#FEBC11]">{{ $avgRating ? number_format($avgRating, 1) : '—' }}</p>
                    @if($avgRating)
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-[#FEBC11]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Reviews table --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reviewer</th>
                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Review</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="selected.includes({{ $review->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3">
                                <input type="checkbox" @click="toggle({{ $review->id }})" :checked="selected.includes({{ $review->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#083321]/10 flex items-center justify-center text-sm font-bold text-[#083321] shrink-0">
                                        {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $review->reviewer_name }}</p>
                                        @if($review->reviewer_location)
                                            <p class="text-xs text-gray-400">{{ $review->reviewer_location }}</p>
                                        @endif
                                        @if($review->review_date)
                                            <p class="text-xs text-gray-400">{{ $review->review_date->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-4 py-3">
                                @if($review->title)
                                    <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $review->title }}</p>
                                @endif
                                <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ Str::limit($review->review_text, 120) }}</p>
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-[#FEBC11]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($review->published)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.tripadvisor.edit', $review) }}" class="text-gray-400 hover:text-blue-500 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>

                                    {{-- Toggle publish --}}
                                    <form method="POST" action="{{ route('admin.tripadvisor.toggle', $review) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-[#083321] transition" title="{{ $review->published ? 'Unpublish' : 'Publish' }}">
                                            @if($review->published)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.tripadvisor.destroy', $review) }}" class="inline" onsubmit="return confirm('Delete this review?')">
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
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                No reviews yet. <a href="{{ route('admin.tripadvisor.create') }}" class="text-[#083321] font-semibold underline">Add your first review</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())<div class="mt-4">{{ $reviews->links() }}</div>@endif

        {{-- Bulk action bar --}}
        <div x-show="selected.length > 0" x-cloak
             class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#083321] text-white rounded-xl shadow-2xl px-6 py-3 flex items-center gap-4 z-50">
            <span class="text-sm font-medium" x-text="selected.length + ' selected'"></span>
            <form method="POST" action="{{ route('admin.tripadvisor.bulk-action') }}" class="flex items-center gap-2">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" name="action" value="publish" class="px-3 py-1.5 text-xs font-medium bg-green-500 rounded-lg hover:bg-green-600 transition">Publish</button>
                <button type="submit" name="action" value="unpublish" class="px-3 py-1.5 text-xs font-medium bg-yellow-500 text-gray-900 rounded-lg hover:bg-yellow-600 transition">Unpublish</button>
                <button type="submit" name="action" value="delete" class="px-3 py-1.5 text-xs font-medium bg-red-500 rounded-lg hover:bg-red-600 transition" onclick="return confirm('Delete selected reviews?')">Delete</button>
            </form>
        </div>
    </div>
</x-app-layout>
