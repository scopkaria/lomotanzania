<x-app-layout>
    <x-slot name="header">Live Chat</x-slot>

    <div x-data="chatDashboard()" x-init="init()" class="flex flex-col gap-4 lg:h-[calc(100vh-10rem)] lg:flex-row">

        {{-- Sessions List --}}
        <div class="w-full shrink-0 bg-white rounded-2xl border border-gray-200 flex flex-col lg:w-80">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 text-sm">Conversations</h3>
                <div class="flex gap-2">
                    <button @click="filter = 'active'" :class="filter === 'active' ? 'bg-[#083321] text-white' : 'bg-gray-100 text-gray-600'" class="px-2.5 py-1 rounded-lg text-xs font-medium transition">Active</button>
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-[#083321] text-white' : 'bg-gray-100 text-gray-600'" class="px-2.5 py-1 rounded-lg text-xs font-medium transition">All</button>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($sessions as $session)
                    <a href="{{ route('admin.chat.show', $session) }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] text-sm font-bold">
                                {{ strtoupper(substr($session->visitor_name ?: 'V', 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white {{ $session->status === 'active' && $session->last_activity_at?->gt(now()->subMinutes(2)) ? 'bg-green-500' : ($session->status === 'missed' ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $session->visitor_name ?: 'Visitor #' . $session->id }}</p>
                                @if($session->unread_messages_count > 0)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $session->unread_messages_count }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 truncate">
                                @if($session->messages->isNotEmpty())
                                    {{ Str::limit($session->messages->first()?->message, 40) }}
                                @else
                                    No messages yet
                                @endif
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $session->last_activity_at?->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-gray-400">No conversations yet</div>
                @endforelse
            </div>
            <div class="px-3 py-2 border-t border-gray-100">
                <a href="{{ route('admin.chat.missed') }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View Missed Chats</a>
            </div>
        </div>

        {{-- Empty state when no session selected --}}
        <div class="hidden flex-1 bg-white rounded-2xl border border-gray-200 lg:flex items-center justify-center">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <p class="text-gray-500 text-sm">Select a conversation to start chatting</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function chatDashboard() {
        return {
            filter: 'active',
            init() {
                // Auto-refresh sessions list every 10 seconds
                setInterval(() => { if (this.filter === 'active') window.location.reload(); }, 30000);
            }
        };
    }
    </script>
    @endpush
</x-app-layout>
