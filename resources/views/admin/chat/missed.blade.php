<x-app-layout>
    <x-slot name="header">Missed Chats</x-slot>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">Chats where no agent responded within 15 minutes.</p>
        </div>

        @if($sessions->isEmpty())
            <div class="px-6 py-12 text-center text-gray-400 text-sm">No missed chats.</div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($sessions as $session)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-sm font-bold">
                            {{ strtoupper(substr($session->visitor_name ?: 'V', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $session->visitor_name ?: 'Visitor #' . $session->id }}</p>
                            <p class="text-xs text-gray-500">{{ $session->messages_count }} messages &middot; {{ $session->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.chat.show', $session) }}" class="px-3 py-1.5 text-xs text-[#083321] bg-[#083321]/10 rounded-lg font-medium hover:bg-[#083321]/20 transition">
                        View Chat
                    </a>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-3 border-t border-gray-100">{{ $sessions->links() }}</div>
        @endif
    </div>
</x-app-layout>
