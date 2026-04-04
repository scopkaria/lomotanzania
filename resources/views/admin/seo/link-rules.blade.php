<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Internal Link Rules</h1>
            <p class="text-sm text-gray-500 mt-1">Define keywords that auto-link to internal pages when found in content.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.seo.link-rules.sync') }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-[#FEBC11] rounded-lg text-sm font-semibold text-[#083321] hover:bg-[#e5a90f] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Auto-Sync from Content
                </button>
            </form>
            <a href="{{ route('admin.seo.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                ← Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 py-3 px-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Add Rule Form --}}
    <div class="mb-6 rounded-xl bg-white p-6 border border-gray-200 shadow-sm">
        <form action="{{ route('admin.seo.link-rules.store') }}" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Keyword</label>
                <input type="text" name="keyword" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Serengeti">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Target URL</label>
                <input type="text" name="url" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="/en/destinations/serengeti">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Custom Anchor (optional)</label>
                <input type="text" name="anchor_text" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Serengeti National Park">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-[#083321] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                    Add Rule
                </button>
            </div>
        </form>
    </div>

    {{-- Rules Table --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b">
                    <tr>
                        <th class="px-4 py-3">Keyword</th>
                        <th class="px-4 py-3">URL</th>
                        <th class="px-4 py-3">Anchor Text</th>
                        <th class="px-4 py-3">Priority</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $rule->keyword }}</td>
                        <td class="px-4 py-3 text-blue-600 max-w-[250px] truncate">{{ $rule->url }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $rule->anchor_text ?: '(uses keyword)' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $rule->priority }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $rule->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $rule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.seo.link-rules.destroy', $rule) }}" method="POST" class="inline" onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No link rules yet. Add one above or click "Auto-Sync from Content".</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rules->hasPages())
            <div class="border-t px-4 py-3">{{ $rules->links() }}</div>
        @endif
    </div>
</x-app-layout>
