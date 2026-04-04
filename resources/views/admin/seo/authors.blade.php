<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Author Profiles (E-E-A-T)</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl bg-white border border-gray-200 p-6 shadow-sm mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Add Author Profile</h2>
        <form action="{{ route('admin.seo.authors.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="John Smith">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Title / Role</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Safari Expert">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Link to User (optional)</label>
                <select name="user_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">— None —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">LinkedIn URL</label>
                <input type="url" name="linkedin_url" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="https://linkedin.com/in/...">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Twitter Handle</label>
                <input type="text" name="twitter_handle" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="@handle">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Expertise (comma-separated)</label>
                <input type="text" name="expertise" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Safari, Wildlife, Kenya">
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="block text-xs text-gray-500 mb-1">Bio</label>
                <textarea name="bio" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Brief professional biography..."></textarea>
            </div>
            <div class="sm:col-span-2 lg:col-span-3 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#083321] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                    Add Author
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                <tr>
                    <th class="px-5 py-3">Author</th>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">Expertise</th>
                    <th class="px-5 py-3">Posts</th>
                    <th class="px-5 py-3">Links</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($authors as $author)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($author->photo_url)
                                    <img src="{{ $author->photo_url }}" alt="{{ $author->name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-[#083321] flex items-center justify-center text-white font-bold text-xs">{{ strtoupper(substr($author->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $author->name }}</p>
                                    @if($author->user)
                                        <p class="text-xs text-gray-400">Linked to: {{ $author->user->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $author->title ?: '—' }}</td>
                        <td class="px-5 py-3">
                            @if($author->expertise)
                                @foreach(array_slice(json_decode($author->expertise, true) ?? explode(',', $author->expertise), 0, 3) as $tag)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 mr-1">{{ trim($tag) }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $author->posts_count ?? $author->posts()->count() }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500">
                            @if($author->linkedin_url)
                                <a href="{{ $author->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline mr-2">LinkedIn</a>
                            @endif
                            @if($author->twitter_handle)
                                <span class="text-gray-400">{{ $author->twitter_handle }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <form action="{{ route('admin.seo.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Delete this author profile?')">
                                @csrf @method('DELETE')
                                <button class="text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">No author profiles yet. Add authors above to boost E-E-A-T signals.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $authors->links() }}</div>
</x-app-layout>
