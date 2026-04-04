<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Image SEO</h1>
        <form action="{{ route('admin.seo.image-seo.process') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#083321] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Process Untagged Images
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Images</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $images->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">With Alt Text</p>
            <p class="text-2xl font-bold text-[#083321] mt-1">{{ $images->where('alt_text', '!=', null)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Avg Savings</p>
            <p class="text-2xl font-bold text-[#FEBC11] mt-1">{{ $images->avg('original_size') && $images->avg('optimized_size') ? round((1 - $images->avg('optimized_size') / max($images->avg('original_size'), 1)) * 100) : 0 }}%</p>
        </div>
    </div>

    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                <tr>
                    <th class="px-5 py-3">Image Path</th>
                    <th class="px-5 py-3">Alt Text</th>
                    <th class="px-5 py-3">SEO Filename</th>
                    <th class="px-5 py-3">Size</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($images as $image)
                    <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                        <td class="px-5 py-3 text-gray-600 font-mono text-xs max-w-[200px] truncate" title="{{ $image->image_path }}">{{ basename($image->image_path) }}</td>
                        <td class="px-5 py-3">
                            <template x-if="!editing">
                                <span class="text-gray-700">{{ $image->alt_text ?: '—' }}</span>
                            </template>
                            <template x-if="editing">
                                <form action="{{ route('admin.seo.image-seo.update', $image) }}" method="POST" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="alt_text" value="{{ $image->alt_text }}" class="rounded-lg border-gray-300 text-xs flex-1 focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Alt text">
                                    <input type="text" name="caption" value="{{ $image->caption }}" class="rounded-lg border-gray-300 text-xs flex-1 focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Caption">
                                    <button type="submit" class="text-[#083321] hover:text-green-700 font-semibold text-xs">Save</button>
                                    <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600 text-xs">Cancel</button>
                                </form>
                            </template>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $image->seo_filename ?: '—' }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">
                            @if($image->original_size)
                                {{ round($image->original_size / 1024) }}KB
                                @if($image->optimized_size)
                                    → {{ round($image->optimized_size / 1024) }}KB
                                    <span class="text-green-600">({{ $image->savings }}%)</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <button @click="editing = !editing" class="text-gray-400 hover:text-[#FEBC11]" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No images processed yet. Click "Process Untagged Images" to scan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $images->links() }}</div>
</x-app-layout>
