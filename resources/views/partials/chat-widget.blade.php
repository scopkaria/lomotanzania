{{-- Live Chat Widget --}}
@php $chatSettings = \App\Models\Setting::first(); @endphp
@if($chatSettings && $chatSettings->chat_enabled)
<div x-data="liveChatWidget()" x-cloak class="fixed bottom-6 right-6 z-[9999]">
    {{-- Chat Window --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="mb-4 w-[360px] max-w-[calc(100vw-3rem)] bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col"
         style="height: 500px; max-height: calc(100vh - 120px);">

        {{-- Header --}}
        <div class="bg-[#083321] px-5 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Lomo Safari</p>
                    <p class="text-white/60 text-xs">We typically reply instantly</p>
                </div>
            </div>
            <button @click="open = false" class="text-white/60 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Pre-chat form --}}
        <template x-if="!sessionId">
            <div class="flex-1 p-5 flex flex-col justify-center">
                <p class="text-sm text-gray-600 mb-4">{{ $chatSettings->chat_greeting ?? 'Hello! How can we help you plan your safari?' }}</p>
                <div class="space-y-3">
                    <input x-model="visitorName" type="text" placeholder="Your name" class="w-full text-sm rounded-lg border-gray-300 px-4 py-2.5 focus:border-[#FEBC11] focus:ring-[#FEBC11]">
                    <input x-model="visitorEmail" type="email" placeholder="Your email" class="w-full text-sm rounded-lg border-gray-300 px-4 py-2.5 focus:border-[#FEBC11] focus:ring-[#FEBC11]" required>
                    <button @click="startChat()" :disabled="!visitorName.trim() || !visitorEmail.trim()" class="w-full bg-[#FEBC11] text-[#131414] font-bold text-sm py-2.5 rounded-lg hover:brightness-90 transition disabled:opacity-50">
                        Start Chat
                    </button>
                </div>
                @if($chatSettings->whatsapp_number)
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="#" @click.prevent="openWhatsApp()"
                       class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat on WhatsApp
                    </a>
                </div>
                @endif
                @if($chatSettings->phone_number)
                <div class="mt-2 text-center">
                    <a href="tel:{{ $chatSettings->phone_number }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        {{ $chatSettings->phone_number }}
                    </a>
                </div>
                @endif
            </div>
        </template>

        {{-- Active chat --}}
        <template x-if="sessionId">
            <div class="flex-1 flex flex-col min-h-0">
                {{-- Agent info bar --}}
                <div x-show="agentInfo" class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-3 shrink-0">
                    <template x-if="agentInfo && agentInfo.profile_image">
                        <img :src="agentInfo.profile_image" class="w-9 h-9 rounded-full object-cover" alt="">
                    </template>
                    <template x-if="agentInfo && !agentInfo.profile_image">
                        <div class="w-9 h-9 rounded-full bg-[#083321] flex items-center justify-center text-white text-xs font-bold" x-text="agentInfo ? agentInfo.name.charAt(0).toUpperCase() : ''"></div>
                    </template>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="agentInfo ? agentInfo.name : ''"></p>
                        <p class="text-xs text-gray-500 truncate">
                            <span x-show="agentInfo && agentInfo.department" x-text="agentInfo ? agentInfo.department : ''" class="font-medium text-[#083321]"></span>
                            <span x-show="agentInfo && agentInfo.department && agentInfo.bio"> · </span>
                            <span x-show="agentInfo && agentInfo.bio" x-text="agentInfo ? agentInfo.bio : ''"></span>
                        </p>
                    </div>
                </div>
                {{-- Chatting as --}}
                <div class="px-4 py-1.5 bg-[#FEBC11]/10 text-xs text-gray-600 shrink-0">
                    Chatting as <span class="font-semibold text-gray-800" x-text="visitorName"></span>
                    <span x-show="visitorEmail" class="text-gray-400" x-text="' (' + visitorEmail + ')'"></span>
                </div>
                {{-- Messages --}}
                <div x-ref="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3">
                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.sender_type === 'visitor' ? 'flex justify-end' : 'flex justify-start'">
                            <div class="max-w-[80%]">
                                <p x-show="msg.sender_type === 'agent' && agentInfo" class="text-[10px] text-gray-400 mb-0.5 ml-1" x-text="agentInfo ? agentInfo.name : ''"></p>
                                <div :class="msg.sender_type === 'visitor' ? 'bg-[#083321] text-white' : 'bg-gray-100 text-gray-800'"
                                     class="rounded-2xl px-4 py-2.5 text-sm">
                                    <p x-text="msg.message"></p>
                                    <p :class="msg.sender_type === 'visitor' ? 'text-white/50' : 'text-gray-400'" class="text-[10px] mt-1" x-text="formatTime(msg.created_at)"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                    {{-- Typing indicator --}}
                    <div x-show="agentTyping" class="flex justify-start">
                        <div class="bg-gray-100 rounded-2xl px-4 py-3 flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
                {{-- Input --}}
                <div class="border-t border-gray-100 p-3 shrink-0">
                    <form @submit.prevent="sendMessage()" class="flex gap-2">
                        <input x-model="newMessage" @input="emitTyping()" type="text" placeholder="Type a message..." class="flex-1 text-sm rounded-lg border-gray-200 px-4 py-2.5 focus:border-[#FEBC11] focus:ring-[#FEBC11]" autocomplete="off">
                        <button type="submit" :disabled="!newMessage.trim()" class="bg-[#FEBC11] text-[#131414] p-2.5 rounded-lg hover:brightness-90 transition disabled:opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- Floating button --}}
    <button @click="open = !open" class="w-14 h-14 rounded-full bg-[#FEBC11] text-[#131414] shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center ml-auto">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

<script>
function liveChatWidget() {
    return {
        open: false,
        sessionId: null,
        visitorId: null,
        visitorName: '',
        visitorEmail: '',
        messages: [],
        newMessage: '',
        agentTyping: false,
        agentInfo: null,
        pollInterval: null,
        lastMessageId: 0,
        typingTimeout: null,
        storageKey: 'lomo-live-chat-state',

        init() {
            this.restoreState();

            if (!this.visitorId) {
                this.visitorId = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : `visitor-${Date.now()}`;
            }

            if (this.sessionId) {
                this.startPolling();
                this.poll();
                this.trackPage();
            }

            this.$watch('open', () => this.persistState());
            this.$watch('visitorName', () => this.persistState());
            this.$watch('visitorEmail', () => this.persistState());
            this.$watch('sessionId', () => this.persistState());
        },

        restoreState() {
            try {
                const saved = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
                this.open = !!saved.open;
                this.sessionId = saved.sessionId || null;
                this.visitorId = saved.visitorId || null;
                this.visitorName = saved.visitorName || '';
                this.visitorEmail = saved.visitorEmail || '';
                this.messages = Array.isArray(saved.messages) ? saved.messages : [];
                this.lastMessageId = Number(saved.lastMessageId || 0);
            } catch (error) {
                console.warn('Unable to restore chat state', error);
            }
        },

        persistState() {
            try {
                localStorage.setItem(this.storageKey, JSON.stringify({
                    open: this.open,
                    sessionId: this.sessionId,
                    visitorId: this.visitorId,
                    visitorName: this.visitorName,
                    visitorEmail: this.visitorEmail,
                    messages: this.messages.slice(-50),
                    lastMessageId: this.lastMessageId,
                }));
            } catch (error) {
                console.warn('Unable to persist chat state', error);
            }
        },

        mergeMessages(items = []) {
            items.forEach((message) => {
                if (!this.messages.find((existing) => existing.id === message.id)) {
                    this.messages.push(message);
                }
                if ((message.id || 0) > this.lastMessageId) {
                    this.lastMessageId = message.id;
                }
            });
            this.persistState();
        },

        async startChat() {
            if (!this.visitorName.trim() || !this.visitorEmail.trim()) return;
            try {
                const res = await fetch('/api/chat/start', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({
                        visitor_id: this.visitorId,
                        visitor_name: this.visitorName,
                        visitor_email: this.visitorEmail,
                        page_url: window.location.href,
                    })
                });
                const data = await res.json();
                this.sessionId = data.session_id;
                this.visitorId = data.visitor_id || this.visitorId;
                this.messages = [];
                this.lastMessageId = 0;
                this.mergeMessages(data.messages || []);
                if (data.greeting && !this.messages.length) {
                    this.messages.push({ id: 0, message: data.greeting, sender_type: 'system', created_at: new Date().toISOString() });
                }
                this.startPolling();
                this.trackPage();
                this.persistState();
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) {
                console.error('Chat start failed', e);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.sessionId) return;
            const msg = this.newMessage.trim();
            this.newMessage = '';
            this.messages.push({ id: Date.now(), message: msg, sender_type: 'visitor', created_at: new Date().toISOString() });
            this.persistState();
            this.$nextTick(() => this.scrollToBottom());
            try {
                await fetch(`/api/chat/${this.sessionId}/message`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: msg })
                });
            } catch (e) {
                console.error('Send failed', e);
            }
        },

        startPolling() {
            if (this.pollInterval) clearInterval(this.pollInterval);
            this.pollInterval = setInterval(() => this.poll(), 2000);
        },

        async poll() {
            if (!this.sessionId) return;
            try {
                const res = await fetch(`/api/chat/${this.sessionId}/poll?after=${this.lastMessageId}`);
                const data = await res.json();
                if (data.messages && data.messages.length > 0) {
                    this.mergeMessages(data.messages);
                    this.$nextTick(() => this.scrollToBottom());
                }
                this.agentTyping = !!data.agent_typing;
                if (data.agent_info) {
                    this.agentInfo = data.agent_info;
                    this.persistState();
                }
            } catch (e) {}
        },

        async emitTyping() {
            if (!this.sessionId) return;
            if (this.typingTimeout) clearTimeout(this.typingTimeout);
            this.typingTimeout = setTimeout(() => {
                fetch(`/api/chat/${this.sessionId}/typing`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ text: '' })
                });
                this.typingTimeout = null;
            }, 3000);
            try {
                await fetch(`/api/chat/${this.sessionId}/typing`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ text: this.newMessage })
                });
            } catch (e) {}
        },

        async trackPage() {
            if (!this.sessionId) return;
            try {
                await fetch(`/api/chat/${this.sessionId}/track-page`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ url: window.location.href, title: document.title, visitor_name: this.visitorName })
                });
            } catch (e) {}
        },

        openWhatsApp() {
            if (!this.visitorName.trim() || !this.visitorEmail.trim()) {
                this.open = true;
                alert('Please enter your name and email first.');
                return;
            }

            const message = `Hello, my name is ${this.visitorName} (${this.visitorEmail}). I am viewing ${document.title} - ${window.location.href} and would like help planning my safari.`;
            const url = 'https://wa.me/{{ preg_replace('/[^0-9]/', '', $chatSettings->whatsapp_number) }}?text=' + encodeURIComponent(message);
            window.open(url, '_blank', 'noopener');
        },

        scrollToBottom() {
            if (this.$refs.chatMessages) this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
        },

        formatTime(date) {
            return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}

// Track page changes for SPA-like navigation
let _chatLastUrl = location.href;
setInterval(() => {
    if (location.href !== _chatLastUrl) {
        _chatLastUrl = location.href;
        const widget = document.querySelector('[x-data*="liveChatWidget"]');
        if (widget && widget.__x) widget.__x.$data.trackPage();
    }
}, 2000);
</script>
@endif
