<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Blog Comments</h1>
        @if($pendingCount > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                {{ $pendingCount }} pending
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.blog-comments.index') }}"
           class="px-4 py-2 text-sm rounded-lg transition {{ !request('status') ? 'bg-[#083321] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            All
        </a>
        @foreach(['pending','approved','rejected'] as $s)
            <a href="{{ route('admin.blog-comments.index', ['status' => $s]) }}"
               class="px-4 py-2 text-sm rounded-lg transition {{ request('status') === $s ? 'bg-[#083321] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ ucfirst($s) }}
            </a>
        @endforeach
    </div>

    {{-- Bulk Actions --}}
    <form method="POST" action="{{ route('admin.blog-comments.bulk-action') }}" x-data="{ selected: [], allChecked: false }" id="bulkForm">
        @csrf
        <div x-show="selected.length > 0" class="mb-4 flex items-center gap-3 rounded-lg bg-blue-50 border border-blue-200 p-3">
            <span class="text-sm text-blue-800 font-medium" x-text="selected.length + ' selected'"></span>
            <button type="submit" name="action" value="approve" class="px-3 py-1 text-xs font-semibold rounded bg-green-600 text-white hover:bg-green-700 transition">Approve</button>
            <button type="submit" name="action" value="reject" class="px-3 py-1 text-xs font-semibold rounded bg-yellow-500 text-white hover:bg-yellow-600 transition">Reject</button>
            <button type="submit" name="action" value="delete" class="px-3 py-1 text-xs font-semibold rounded bg-red-600 text-white hover:bg-red-700 transition"
                    onclick="return confirm('Delete selected comments?')">Delete</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-10 px-4 py-3">
                            <input type="checkbox" @change="allChecked = $event.target.checked; selected = allChecked ? {{ $comments->pluck('id') }} : []"
                                   class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Commenter</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Comment</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Post</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($comments as $comment)
                        <tr class="hover:bg-gray-50 transition {{ $comment->status === 'pending' ? 'bg-yellow-50/50' : '' }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="ids[]" value="{{ $comment->id }}"
                                       x-model.number="selected"
                                       class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $comment->name }}</div>
                                <div class="text-xs text-gray-400">{{ $comment->email }}</div>
                                @if($comment->phone)
                                    <div class="text-xs text-gray-400">{{ $comment->phone }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $comment->body }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if($comment->post)
                                    <a href="{{ route('admin.posts.edit', $comment->post) }}" class="text-sm text-[#083321] hover:underline">
                                        {{ \Illuminate\Support\Str::limit($comment->post->translatedTitle(), 40) }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400">Deleted post</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = ['pending' => 'bg-yellow-100 text-yellow-800', 'approved' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-800'];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $colors[$comment->status] ?? '' }}">
                                    {{ ucfirst($comment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                {{ $comment->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    @if($comment->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.blog-comments.approve', $comment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded hover:bg-green-100 transition" title="Approve">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($comment->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.blog-comments.reject', $comment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded hover:bg-yellow-100 transition" title="Reject">
                                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.blog-comments.destroy', $comment) }}" class="inline"
                                          onsubmit="return confirm('Delete this comment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded hover:bg-red-100 transition" title="Delete">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">No comments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-6">{{ $comments->links() }}</div>
</div>
</x-app-layout>
