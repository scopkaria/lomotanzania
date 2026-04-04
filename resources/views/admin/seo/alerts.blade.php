<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rank Alerts</h1>
        @if($alerts->count())
            <form action="{{ route('admin.seo.alerts.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Mark All Read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Unread Alerts</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $alerts->where('is_read', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Drops</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $alerts->where('type', 'drop')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Gains</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $alerts->where('type', 'gain')->count() }}</p>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($alerts as $alert)
            <div class="rounded-xl border {{ $alert->is_read ? 'bg-white border-gray-200' : 'bg-yellow-50 border-yellow-200' }} p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @if($alert->type === 'drop')
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                    @else
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $alert->ranking->keyword ?? 'Unknown Keyword' }}
                        </p>
                        <p class="text-sm text-gray-600">{{ $alert->description }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @unless($alert->is_read)
                    <form action="{{ route('admin.seo.alerts.read', $alert) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-500 hover:text-[#083321] font-medium">Mark Read</button>
                    </form>
                @endunless
            </div>
        @empty
            <div class="rounded-xl bg-white border border-gray-200 p-8 text-center text-gray-400">
                No rank alerts yet. Alerts are generated when keyword positions change significantly.
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $alerts->links() }}</div>
</x-app-layout>
