<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keyword Strategy</h1>
            <p class="text-sm text-gray-500 mt-1">Research and track target keywords by intent, volume, and difficulty.</p>
        </div>
        <a href="{{ route('admin.seo.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
            ← SEO Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 py-3 px-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Add Keyword Form --}}
    <div class="mb-6 rounded-xl bg-white p-6 border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Add Keyword</h2>
        <form action="{{ route('admin.seo.keywords.store') }}" method="POST" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Keyword</label>
                <input type="text" name="keyword" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="e.g. serengeti safari">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Intent</label>
                <select name="intent" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    @foreach($intents as $intent)
                        <option value="{{ $intent }}">{{ ucfirst($intent) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Volume (est.)</label>
                <input type="number" name="volume" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="1000">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Difficulty (0-100)</label>
                <input type="number" name="difficulty" min="0" max="100" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="45">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Target URL</label>
                <input type="text" name="target_url" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="/en/safaris">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Country</label>
                <input type="text" name="country" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Tanzania">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Group</label>
                <input type="text" name="group" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="Serengeti Keywords">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-[#083321] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                    Add Keyword
                </button>
            </div>
        </form>
    </div>

    {{-- Keywords Table --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b">
                    <tr>
                        <th class="px-4 py-3">Keyword</th>
                        <th class="px-4 py-3">Intent</th>
                        <th class="px-4 py-3">Volume</th>
                        <th class="px-4 py-3">Difficulty</th>
                        <th class="px-4 py-3">Target URL</th>
                        <th class="px-4 py-3">Group</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($keywords as $kw)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $kw->keyword }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                {{ match($kw->intent) {
                                    'transactional' => 'bg-green-100 text-green-700',
                                    'informational' => 'bg-blue-100 text-blue-700',
                                    'local' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-gray-100 text-gray-700',
                                } }}">
                                {{ ucfirst($kw->intent) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $kw->volume ? number_format($kw->volume) : '—' }}</td>
                        <td class="px-4 py-3">
                            @if($kw->difficulty !== null)
                                <span class="{{ $kw->difficulty <= 30 ? 'text-green-600' : ($kw->difficulty <= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $kw->difficulty }}/100
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">{{ $kw->target_url ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $kw->group ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.seo.keywords.destroy', $kw) }}" method="POST" class="inline" onsubmit="return confirm('Delete this keyword?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No keywords added yet. Start building your keyword strategy above.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($keywords->hasPages())
            <div class="border-t px-4 py-3">{{ $keywords->links() }}</div>
        @endif
    </div>
</x-app-layout>
