<x-app-layout>
    <x-slot name="header">Blog Categories</x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div x-data="adminTable({ ids: [{{ $categories->pluck('id')->join(',') }}], key: 'blog-cats', columns: { slug: true, posts: true } })">

        @include('admin.partials.table-toolbar', [
            'searchPlaceholder' => 'Search categories...',
            'perPage' => request('per_page', 15),
            'createRoute' => route('admin.blog-categories.create'),
            'createLabel' => 'New Category',
            'columnList' => ['slug' => 'Slug', 'posts' => 'Posts'],
        ])

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th x-show="isVisible('slug')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th x-show="isVisible('posts')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Posts</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $cat->id }}) && 'bg-[#083321]/5'">
                            <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $cat->id }})" :checked="isSelected({{ $cat->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $cat->name['en'] ?? '—' }}</p>
                                @if(!empty($cat->name['fr']))
                                    <p class="text-xs text-gray-400">FR: {{ $cat->name['fr'] }}</p>
                                @endif
                            </td>
                            <td x-show="isVisible('slug')" class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500 font-mono">{{ $cat->slug }}</td>
                            <td x-show="isVisible('posts')" class="hidden md:table-cell px-4 py-3 text-sm text-gray-500">{{ $cat->posts_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.blog-categories.edit', $cat) }}" class="text-gray-400 hover:text-[#FEBC11] transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></a>
                                    <form action="{{ route('admin.blog-categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">No categories yet. <a href="{{ route('admin.blog-categories.create') }}" class="text-[#FEBC11] hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())<div class="mt-4">{{ $categories->links() }}</div>@endif

        @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.blog-categories.bulk-action'), 'actions' => ['delete' => 'Delete']])
    </div>
</x-app-layout>
