<x-app-layout>
<x-slot name="header">Conversations</x-slot>
<div class="max-w-5xl mx-auto" x-data="conversationList()">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Conversations</h1>
            <p class="text-sm text-gray-500 mt-1">Internal team messaging</p>
        </div>
        <button @click="showNewModal = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Conversation
        </button>
    </div>

    {{-- Conversations list --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @forelse($conversations as $conv)
            @php
                $other = $conv->otherParticipant(auth()->id());
                $unread = $conv->unreadCountFor(auth()->id());
                $latest = $conv->latestMessage->first();
                $isOnline = $other ? cache()->has('user_online_' . $other->id) : false;
            @endphp
            <a href="{{ route('admin.conversations.show', $conv) }}"
               class="flex items-center gap-4 px-5 py-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition group {{ $unread > 0 ? 'bg-[#FEBC11]/5' : '' }}">
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    @if($other && $other->profile_image)
                        <img src="{{ asset('storage/' . $other->profile_image) }}" class="w-11 h-11 rounded-full object-cover" alt="">
                    @else
                        <div class="w-11 h-11 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] font-bold text-sm">
                            {{ $other ? strtoupper(substr($other->name, 0, 1)) : '?' }}
                        </div>
                    @endif
                    @if($isOnline)
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900 truncate {{ $unread > 0 ? 'text-gray-900' : 'text-gray-700' }}">
                            {{ $other ? $other->name : 'Unknown' }}
                        </h3>
                        <span class="text-[11px] text-gray-400 shrink-0 ml-2">
                            {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(short: true) : '' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mt-0.5">
                        <p class="text-xs text-gray-500 truncate {{ $unread > 0 ? 'font-medium text-gray-700' : '' }}">
                            @if($latest)
                                {{ $latest->user_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($latest->body ?: '📎 Attachment', 60) }}
                            @else
                                No messages yet
                            @endif
                        </p>
                        @if($unread > 0)
                            <span class="bg-[#FEBC11] text-[#131414] text-[10px] font-bold min-w-[20px] h-5 px-1.5 rounded-full flex items-center justify-center ml-2 shrink-0">{{ $unread }}</span>
                        @endif
                    </div>
                    @if($other)
                        <span class="inline-flex items-center text-[10px] text-gray-400 mt-0.5">
                            {{ ucfirst(str_replace('_', ' ', $other->role)) }}
                            @if($other->department) · {{ $other->department->name }} @endif
                        </span>
                    @endif
                </div>
            </a>
        @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">No conversations yet</h3>
                <p class="text-xs text-gray-400 mt-1">Start a new conversation with a team member</p>
            </div>
        @endforelse

        @if($conversations->hasPages())
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                {{ $conversations->links() }}
            </div>
        @endif
    </div>

    {{-- New Conversation Modal --}}
    <div x-show="showNewModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showNewModal = false">
        <div x-show="showNewModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">New Conversation</h2>
                <button @click="showNewModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Send to</label>
                    <select x-model="newTarget" class="w-full text-sm">
                        <option value="">Select a team member...</option>
                        @foreach($availableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst(str_replace('_', ' ', $u->role)) }}{{ $u->department ? ' · ' . $u->department->name : '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea x-model="newMessageText" rows="3" placeholder="Type your message..." class="w-full text-sm"></textarea>
                </div>
                <button @click="startConversation()" :disabled="!newTarget || !newMessageText.trim() || sending"
                        class="w-full bg-[#083321] text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-[#083321]/90 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    <span x-show="!sending">Send Message</span>
                    <span x-show="sending">Sending...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function conversationList() {
    return {
        showNewModal: false,
        newTarget: '',
        newMessageText: '',
        sending: false,

        async startConversation() {
            if (!this.newTarget || !this.newMessageText.trim() || this.sending) return;
            this.sending = true;
            try {
                const res = await fetch('{{ route("admin.conversations.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ user_id: this.newTarget, message: this.newMessageText })
                });
                const data = await res.json();
                if (data.redirect) window.location.href = data.redirect;
                else if (data.error && window.showLomoToast) window.showLomoToast(data.error, 'error');
            } catch (e) {
                if (window.showLomoToast) {
                    window.showLomoToast('Failed to start conversation.', 'error');
                }
            }
            this.sending = false;
        }
    }
}
</script>
</x-app-layout>
