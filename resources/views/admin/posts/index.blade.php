<x-app-layout>
    <x-slot name="header">Blog Posts</x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div x-data="adminTable({ ids: [{{ $posts->pluck('id')->join(',') }}], key: 'posts', columns: { category: true, author: true, status: true, date: true } })">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
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
                <form method="GET">
                    @foreach(request()->except(['category', 'page']) as $k => $v)
                        @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <select name="category" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name['en'] ?? $cat->slug }}</option>
                        @endforeach
                    </select>
                </form>
                @if(request('search') || request('status') || request('category'))
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
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
                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Post
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Post</th>
                        <th x-show="isVisible('category')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th x-show="isVisible('author')" class="hidden lg:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Author</th>
                        <th x-show="isVisible('status')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th x-show="isVisible('date')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($posts as $post)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $post->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $post->id }})" :checked="isSelected({{ $post->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $post->title['en'] ?? '—' }}</p>
                                        <p class="text-xs text-gray-400 font-mono truncate">/blog/{{ $post->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td x-show="isVisible('category')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $post->category?->name['en'] ?? '—' }}</td>
                            <td x-show="isVisible('author')" class="hidden lg:table-cell px-4 py-3 text-sm text-gray-500">{{ $post->author?->name ?? '—' }}</td>
                            <td x-show="isVisible('status')" class="px-4 py-3">
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                @endif
                            </td>
                            <td x-show="isVisible('date')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No posts yet. <a href="{{ route('admin.posts.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())<div class="mt-4">{{ $posts->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.posts.bulk-action'), 'actions' => ['publish' => 'Publish', 'draft' => 'Draft', 'delete' => 'Delete']])
    </div>
</x-app-layout>
