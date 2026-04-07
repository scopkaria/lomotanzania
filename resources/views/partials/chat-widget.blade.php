{{-- Live Chat Widget — AI auto-responder, quick actions, emoji, lead capture --}}
@php $chatSettings = \App\Models\Setting::first(); @endphp
@if($chatSettings && $chatSettings->chat_enabled)
<div x-data="liveChatWidget()" x-cloak class="fixed bottom-6 right-6 z-[9999]" style="font-family: 'Inter', sans-serif;">
    {{-- Chat Window --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="mb-4 w-[380px] max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col"
         style="height: 520px; max-height: calc(100vh - 120px);">

        {{-- Header — dynamic color based on department --}}
        <div class="px-5 py-4 flex items-center justify-between shrink-0 transition-colors duration-300"
             :style="'background-color:' + headerColor">
            <div class="flex items-center gap-3">
                <template x-if="agentInfo && agentInfo.profile_image">
                    <div class="relative">
                        <img :src="agentInfo.profile_image" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/30" alt="">
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white bg-green-400"></span>
                    </div>
                </template>
                <template x-if="!agentInfo || !agentInfo.profile_image">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        @if(optional($siteSetting ?? null)->logo_path)
                            <img src="{{ asset('storage/' . ($siteSetting->logo_path ?? '')) }}" class="w-7 h-7 object-contain" alt="">
                        @else
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/></svg>
                        @endif
                    </div>
                </template>
                <div class="min-w-0">
                    <p class="text-white font-semibold text-sm truncate" x-text="agentInfo ? agentInfo.name : '{{ optional($siteSetting ?? null)->site_name ?? 'Lomo Safari' }}'"></p>
                    <p class="text-white/70 text-xs truncate">
                        <template x-if="agentInfo && agentInfo.department">
                            <span x-text="agentInfo.department + ' · Online'"></span>
                        </template>
                        <template x-if="!agentInfo || !agentInfo.department">
                            <span x-text="sessionId ? (agentJoined ? 'Agent connected' : 'Connecting you...') : 'We reply instantly'"></span>
                        </template>
                    </p>
                </div>
            </div>
            <button @click="if(sessionId && confirm('End this chat?')) endChat(); else if(!sessionId) open=false;"
                    class="p-1.5 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition" title="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Pre-chat form --}}
        <template x-if="!sessionId">
            <div class="flex-1 p-5 flex flex-col">
                <div class="flex-1 flex flex-col justify-center">
                    <div class="text-center mb-5">
                        <div class="w-14 h-14 rounded-full bg-[#083321]/10 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-[#083321]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                        </div>
                        <p class="text-gray-700 text-sm font-medium">{{ $chatSettings->chat_greeting ?? 'Hello! How can we help you plan your safari?' }}</p>
                    </div>
                    <div class="space-y-3">
                        <input x-model="visitorName" type="text" placeholder="Your name" class="w-full text-sm bg-gray-50 border-gray-200 focus:bg-white">
                        <input x-model="visitorEmail" type="email" placeholder="Your email" class="w-full text-sm bg-gray-50 border-gray-200 focus:bg-white" required>
                        <button @click="startChat()" :disabled="!visitorName.trim() || !visitorEmail.trim()"
                                class="w-full bg-[#FEBC11] text-[#131414] font-bold text-sm py-3 rounded-lg hover:brightness-95 transition disabled:opacity-40 disabled:cursor-not-allowed shadow-sm">
                            Start Conversation
                        </button>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 flex items-center justify-center gap-4 mt-3">
                    @if($chatSettings->whatsapp_number)
                    <a href="#" @click.prevent="openWhatsApp()" class="text-green-600 hover:text-green-700 transition" title="WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    @endif
                    @if($chatSettings->phone_number)
                    <a href="tel:{{ $chatSettings->phone_number }}" class="text-gray-400 hover:text-gray-600 transition" title="Call us">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </template>

        {{-- Active chat --}}
        <template x-if="sessionId">
            <div class="flex-1 flex flex-col min-h-0">
                {{-- Messages --}}
                <div x-ref="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3" style="scroll-behavior: smooth;">
                    <template x-for="msg in messages" :key="msg.id">
                        <div>
                            {{-- System messages --}}
                            <template x-if="msg.message_type === 'system' || msg.sender_type === 'system'">
                                <div class="flex justify-center my-2">
                                    <div class="bg-gray-50 border border-gray-100 rounded-full px-3 py-1 max-w-[90%]">
                                        <p class="text-[11px] text-gray-500 font-medium text-center" x-text="msg.message"></p>
                                    </div>
                                </div>
                            </template>
                            {{-- Bot messages --}}
                            <template x-if="msg.sender_type === 'bot'">
                                <div class="flex justify-start">
                                    <div class="max-w-[85%]">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="w-5 h-5 rounded-full bg-[#FEBC11]/20 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium">Safari Assistant</span>
                                        </div>
                                        <div class="bg-[#FEBC11]/10 text-gray-800 rounded-2xl rounded-tl-md px-4 py-2.5 text-sm leading-relaxed">
                                            <p x-html="msg.message"></p>
                                            <p class="text-gray-400 text-[10px] mt-1" x-text="formatTime(msg.created_at)"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            {{-- Normal messages --}}
                            <template x-if="msg.message_type !== 'system' && msg.sender_type !== 'system' && msg.sender_type !== 'bot'">
                                <div :class="msg.sender_type === 'visitor' ? 'flex justify-end' : 'flex justify-start'">
                                    <div class="max-w-[85%]">
                                        <div :class="msg.sender_type === 'visitor'
                                            ? 'bg-[#083321] text-white rounded-2xl rounded-br-md'
                                            : 'bg-gray-100 text-gray-800 rounded-2xl rounded-tl-md'"
                                             class="px-4 py-2.5 text-sm leading-relaxed">
                                            <p x-text="msg.message"></p>
                                            <p :class="msg.sender_type === 'visitor' ? 'text-white/40' : 'text-gray-400'"
                                               class="text-[10px] mt-1" x-text="formatTime(msg.created_at)"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Quick action pills --}}
                    <div x-show="showQuickActions && !agentJoined && messages.length <= 3" x-transition class="flex flex-wrap gap-2 pt-2">
                        <template x-for="action in quickActions" :key="action.label">
                            <button @click="handleQuickAction(action)"
                                    class="text-xs px-3 py-1.5 rounded-full border border-[#083321]/20 text-[#083321] bg-[#083321]/5 hover:bg-[#083321]/10 transition font-medium whitespace-nowrap">
                                <span x-text="action.icon + ' ' + action.label"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Typing indicator --}}
                    <div x-show="agentTyping" class="flex justify-start">
                        <div class="bg-gray-100 rounded-2xl px-4 py-3 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                    </div>

                    {{-- Offline fallback form --}}
                    <div x-show="showOfflineForm" x-transition class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-2">
                        <p class="text-sm text-amber-800 font-medium mb-2">Our team is currently unavailable</p>
                        <p class="text-xs text-amber-600 mb-3">Leave your details and we'll get back to you shortly.</p>
                        <div class="space-y-2">
                            <input x-model="leadName" type="text" placeholder="Your name" class="w-full text-xs bg-white border-amber-200" style="min-height:36px !important; padding:0.5rem 0.75rem !important;">
                            <input x-model="leadEmail" type="email" placeholder="Your email" class="w-full text-xs bg-white border-amber-200" style="min-height:36px !important; padding:0.5rem 0.75rem !important;">
                            <textarea x-model="leadMessage" placeholder="Your message..." rows="2" class="w-full text-xs bg-white border-amber-200" style="min-height:60px !important;"></textarea>
                            <button @click="submitLead()" :disabled="!leadEmail.trim() || !leadMessage.trim()"
                                    class="w-full bg-[#FEBC11] text-[#131414] text-xs font-bold py-2 rounded-lg hover:brightness-95 transition disabled:opacity-40">
                                Send Message
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Input area with emoji --}}
                <div class="border-t border-gray-100 p-3 shrink-0 bg-white">
                    <div x-show="showEmoji" x-transition class="mb-2 bg-gray-50 rounded-lg p-2 max-h-32 overflow-y-auto">
                        <div class="flex flex-wrap gap-1">
                            <template x-for="emoji in emojis" :key="emoji">
                                <button @click="insertEmoji(emoji)" type="button" class="w-8 h-8 flex items-center justify-center text-lg hover:bg-white rounded transition" x-text="emoji"></button>
                            </template>
                        </div>
                    </div>
                    <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                        <button type="button" @click="showEmoji = !showEmoji" class="p-2 text-gray-400 hover:text-gray-600 transition shrink-0" title="Emoji">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                        </button>
                        <input x-model="newMessage" @input="emitTyping()" @keydown.escape="showEmoji = false"
                               type="text" placeholder="Type a message..."
                               class="flex-1 text-sm bg-gray-50 border-gray-200 focus:bg-white" autocomplete="off">
                        <button type="submit" :disabled="!newMessage.trim()"
                                class="p-2.5 rounded-lg transition shrink-0 disabled:opacity-30"
                                :style="newMessage.trim() ? 'background-color:' + headerColor : 'background-color:#d1d5db'"
                                :class="newMessage.trim() ? 'text-white hover:brightness-90' : 'text-gray-400'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- Floating button --}}
    <button @click="toggleChat()"
            class="relative w-14 h-14 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center ml-auto"
            :style="'background-color:' + headerColor">
        <svg x-show="!open" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg x-show="open" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        <span x-show="unreadCount > 0 && !open" x-text="unreadCount" x-transition
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 px-1.5 rounded-full flex items-center justify-center shadow-md animate-bounce"></span>
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
        agentJoined: false,
        pollInterval: null,
        lastMessageId: 0,
        typingTimeout: null,
        storageKey: 'lomo-live-chat-state',
        audioCtx: null,
        hasInteracted: false,
        unreadCount: 0,
        showEmoji: false,
        showQuickActions: true,
        showOfflineForm: false,
        offlineTimer: null,
        headerColor: '#083321',
        leadName: '',
        leadEmail: '',
        leadMessage: '',
        leadSubmitted: false,

        emojis: ['😊','😂','🤣','❤️','👍','🙏','😍','🎉','✨','🌍','🦁','🐘','🦒','🌅','🏕️','✈️','🗺️','📸','💬','👋','🙂','😃','🤔','👏','🔥','💯','🎊','⭐','🌟','💪'],

        quickActions: [
            { label: 'View Packages', icon: '📦', url: '/safaris', message: "I'd like to see your safari packages" },
            { label: '5 Days Safari', icon: '🦁', url: '/safaris?days=5', message: "I'm interested in a 5-day safari" },
            { label: 'Contact Us', icon: '📞', url: '/contact', message: 'I need to contact your team' },
            { label: 'About Us', icon: 'ℹ️', url: '/about', message: 'Tell me about Lomo Safari' },
            { label: 'Home Page', icon: '🏠', url: '/', message: 'Take me to the home page' },
        ],

        aiResponses: {},

        async getAiResponse(text) {
            if (this.agentJoined) return null;
            try {
                const res = await fetch('/api/chat/' + this.sessionId + '/ai-response', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: text })
                });
                const data = await res.json();
                return data.response || null;
            } catch (e) {
                return null;
            }
        },

        init() {
            this.restoreState();
            ['click', 'keydown', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, () => { this.hasInteracted = true; }, { once: true });
            });
            if (!this.visitorId) {
                this.visitorId = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'visitor-' + Date.now();
            }
            if (this.sessionId) {
                this.startPolling();
                this.poll();
                this.trackPage();
                this.startOfflineMonitor();
            }
            this.$watch('open', () => this.persistState());
            this.$watch('visitorName', () => this.persistState());
            this.$watch('visitorEmail', () => this.persistState());
            this.$watch('sessionId', () => this.persistState());
        },

        toggleChat() {
            this.open = !this.open;
            if (this.open) {
                this.unreadCount = 0;
                this.showEmoji = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        restoreState() {
            try {
                const saved = JSON.parse(localStorage.getItem(this.storageKey) || '{}');

                // Check 20-minute session timeout
                if (saved.sessionId && saved.lastActivityAt) {
                    const elapsed = Date.now() - saved.lastActivityAt;
                    if (elapsed > 20 * 60 * 1000) {
                        // Session expired — clear and start fresh
                        localStorage.removeItem(this.storageKey);
                        return;
                    }
                }

                this.open = !!saved.open;
                this.sessionId = saved.sessionId || null;
                this.visitorId = saved.visitorId || null;
                this.visitorName = saved.visitorName || '';
                this.visitorEmail = saved.visitorEmail || '';
                this.messages = Array.isArray(saved.messages) ? saved.messages : [];
                this.lastMessageId = Number(saved.lastMessageId || 0);
                this.agentJoined = !!saved.agentJoined;
                this.agentInfo = saved.agentInfo || null;
                if (this.agentInfo) this.updateHeaderColor();
            } catch (e) {}
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
                    agentJoined: this.agentJoined,
                    agentInfo: this.agentInfo,
                    lastActivityAt: Date.now(),
                }));
            } catch (e) {}
        },

        mergeMessages(items = []) {
            items.forEach((msg) => {
                if (!this.messages.find(m => m.id === msg.id)) {
                    this.messages.push(msg);
                }
                if ((msg.id || 0) > this.lastMessageId) {
                    this.lastMessageId = msg.id;
                }
            });
            this.persistState();
        },

        async startChat() {
            if (!this.visitorName.trim() || !this.visitorEmail.trim()) return;
            this.leadName = this.visitorName;
            this.leadEmail = this.visitorEmail;
            try {
                const res = await fetch('/api/chat/start', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ visitor_id: this.visitorId, visitor_name: this.visitorName, visitor_email: this.visitorEmail, page_url: window.location.href })
                });
                const data = await res.json();
                this.sessionId = data.session_id;
                this.visitorId = data.visitor_id || this.visitorId;
                this.messages = [];
                this.lastMessageId = 0;
                this.agentJoined = false;
                this.mergeMessages(data.messages || []);
                if (data.greeting && !this.messages.length) {
                    this.messages.push({ id: 'g0', message: data.greeting, sender_type: 'bot', created_at: new Date().toISOString() });
                }
                this.showQuickActions = true;
                this.startPolling();
                this.trackPage();
                this.startOfflineMonitor();
                this.persistState();
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) { console.error('Chat start failed', e); }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.sessionId) return;
            const msg = this.newMessage.trim();
            this.newMessage = '';
            this.showEmoji = false;
            this.messages.push({ id: 'v' + Date.now(), message: msg, sender_type: 'visitor', created_at: new Date().toISOString() });
            this.persistState();
            this.$nextTick(() => this.scrollToBottom());

            // AI auto-respond before agent joins
            if (!this.agentJoined) {
                this.getAiResponse(msg).then(aiReply => {
                    if (aiReply && !this.agentJoined) {
                        this.messages.push({ id: 'bot' + Date.now(), message: aiReply, sender_type: 'bot', created_at: new Date().toISOString() });
                        this.persistState();
                        this.$nextTick(() => this.scrollToBottom());
                    }
                });
            }

            try {
                await fetch('/api/chat/' + this.sessionId + '/message', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: msg })
                });
            } catch (e) { console.error('Send failed', e); }
        },

        handleQuickAction(action) {
            this.newMessage = action.message;
            this.sendMessage();
            if (this.sessionId) {
                fetch('/api/chat/' + this.sessionId + '/track-page', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ url: action.url, title: 'Quick Action: ' + action.label })
                }).catch(() => {});
            }
        },

        insertEmoji(emoji) {
            this.newMessage += emoji;
            this.showEmoji = false;
        },

        startPolling() {
            if (this.pollInterval) clearInterval(this.pollInterval);
            this.pollInterval = setInterval(() => this.poll(), 2000);
        },

        async poll() {
            if (!this.sessionId) return;
            try {
                const res = await fetch('/api/chat/' + this.sessionId + '/poll?after=' + this.lastMessageId);
                const data = await res.json();
                if (data.messages && data.messages.length > 0) {
                    const agentMsgs = data.messages.filter(m => m.sender_type === 'agent');
                    if (agentMsgs.length > 0 && !this.agentJoined) {
                        this.agentJoined = true;
                        this.showOfflineForm = false;
                        this.clearOfflineTimer();
                        // Add system message for agent joining
                        if (this.agentInfo) {
                            this.messages.push({ id: 'sys' + Date.now(), message: (this.agentInfo.name || 'An agent') + ' has joined the chat', sender_type: 'system', message_type: 'system', created_at: new Date().toISOString() });
                        }
                    }
                    this.mergeMessages(data.messages);
                    this.$nextTick(() => this.scrollToBottom());
                    if (agentMsgs.length > 0 && !this.open) {
                        this.unreadCount += agentMsgs.length;
                        this.playNotificationSound();
                    } else if (agentMsgs.length > 0 && !document.hasFocus()) {
                        this.playNotificationSound();
                    }
                }
                this.agentTyping = !!data.agent_typing;
                if (data.agent_info) {
                    this.agentInfo = data.agent_info;
                    this.updateHeaderColor();
                    this.persistState();
                }
            } catch (e) {}
        },

        updateHeaderColor() {
            if (!this.agentInfo) return;
            if (this.agentInfo.department_color) {
                this.headerColor = this.agentInfo.department_color;
            }
        },

        startOfflineMonitor() {
            this.clearOfflineTimer();
            this.offlineTimer = setInterval(() => {
                if (this.agentJoined || this.showOfflineForm || this.leadSubmitted) return;
                const visitorMsgs = this.messages.filter(m => m.sender_type === 'visitor');
                if (visitorMsgs.length > 0) {
                    const lastV = visitorMsgs[visitorMsgs.length - 1];
                    if ((Date.now() - new Date(lastV.created_at).getTime()) > 90000) {
                        this.showOfflineForm = true;
                    }
                }
            }, 10000);
        },

        clearOfflineTimer() {
            if (this.offlineTimer) { clearInterval(this.offlineTimer); this.offlineTimer = null; }
        },

        async submitLead() {
            if (!this.leadEmail.trim() || !this.leadMessage.trim()) return;
            try {
                await fetch('/api/chat/' + this.sessionId + '/lead', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ name: this.leadName || this.visitorName, email: this.leadEmail || this.visitorEmail, message: this.leadMessage })
                });
                this.leadSubmitted = true;
                this.showOfflineForm = false;
                this.messages.push({ id: 'lead' + Date.now(), message: 'Your message has been saved. Our team will reach out via email shortly! 📧', sender_type: 'bot', created_at: new Date().toISOString() });
                this.persistState();
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) { alert('Failed to send. Please try again.'); }
        },

        async endChat() {
            if (!this.sessionId) return;
            try {
                await fetch('/api/chat/' + this.sessionId + '/end', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
            } catch (e) {}
            this.sessionId = null;
            this.messages = [];
            this.agentInfo = null;
            this.agentJoined = false;
            this.lastMessageId = 0;
            this.showOfflineForm = false;
            this.leadSubmitted = false;
            this.showQuickActions = true;
            this.headerColor = '#083321';
            this.clearOfflineTimer();
            if (this.pollInterval) { clearInterval(this.pollInterval); this.pollInterval = null; }
            this.open = false;
            this.persistState();
        },

        playNotificationSound() {
            if (!this.hasInteracted) return;
            try {
                if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = this.audioCtx, now = ctx.currentTime;
                const gain = ctx.createGain();
                gain.connect(ctx.destination);
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.6);
                const o1 = ctx.createOscillator();
                o1.type = 'sine'; o1.frequency.setValueAtTime(880, now);
                o1.frequency.exponentialRampToValueAtTime(1100, now + 0.15);
                o1.connect(gain); o1.start(now); o1.stop(now + 0.3);
                const o2 = ctx.createOscillator();
                o2.type = 'sine'; o2.frequency.setValueAtTime(1320, now + 0.15);
                o2.frequency.exponentialRampToValueAtTime(1100, now + 0.4);
                o2.connect(gain); o2.start(now + 0.15); o2.stop(now + 0.6);
            } catch (e) {}
        },

        async emitTyping() {
            if (!this.sessionId) return;
            if (this.typingTimeout) clearTimeout(this.typingTimeout);
            this.typingTimeout = setTimeout(() => {
                fetch('/api/chat/' + this.sessionId + '/typing', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ text: '' })
                }); this.typingTimeout = null;
            }, 3000);
            try {
                await fetch('/api/chat/' + this.sessionId + '/typing', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ text: this.newMessage })
                });
            } catch (e) {}
        },

        async trackPage() {
            if (!this.sessionId) return;
            try {
                await fetch('/api/chat/' + this.sessionId + '/track-page', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ url: window.location.href, title: document.title, visitor_name: this.visitorName })
                });
            } catch (e) {}
        },

        openWhatsApp() {
            if (!this.visitorName.trim() || !this.visitorEmail.trim()) {
                this.open = true; alert('Please enter your name and email first.'); return;
            }
            const msg = 'Hello, my name is ' + this.visitorName + ' (' + this.visitorEmail + '). I am viewing ' + document.title + ' - ' + window.location.href + ' and would like help planning my safari.';
            window.open('https://wa.me/{{ preg_replace("/[^0-9]/", "", $chatSettings->whatsapp_number ?? "") }}?text=' + encodeURIComponent(msg), '_blank', 'noopener');
        },

        scrollToBottom() {
            if (this.$refs.chatMessages) this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
        },

        formatTime(date) {
            return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}

let _chatLastUrl = location.href;
setInterval(() => {
    if (location.href !== _chatLastUrl) {
        _chatLastUrl = location.href;
        const w = document.querySelector('[x-data*="liveChatWidget"]');
        if (w && w.__x) w.__x.$data.trackPage();
    }
}, 2000);
</script>
@endif
