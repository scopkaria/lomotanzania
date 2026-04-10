<x-app-layout>
<x-slot name="header">{{ $other ? $other->name . ' — Conversation' : 'Conversation' }}</x-slot>
<div class="max-w-4xl mx-auto" x-data="conversationChat()" x-init="init()">
    {{-- Header --}}
    <div class="bg-white rounded-t-xl border border-gray-200 shadow-sm px-5 py-3 flex items-center gap-4">
        <a href="{{ route('admin.conversations.index') }}" class="text-gray-400 hover:text-gray-600 transition shrink-0" title="Back">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        </a>
        <div class="relative shrink-0">
            @if($other && $other->profile_image)
                <img src="{{ asset('storage/' . $other->profile_image) }}" class="w-10 h-10 rounded-full object-cover" alt="">
            @else
                <div class="w-10 h-10 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] font-bold text-sm">
                    {{ $other ? strtoupper(substr($other->name, 0, 1)) : '?' }}
                </div>
            @endif
            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white"
                  :class="otherOnline ? 'bg-green-500' : 'bg-gray-300'"></span>
        </div>
        <div class="min-w-0 flex-1">
            <h2 class="text-sm font-semibold text-gray-900 truncate">{{ $other ? $other->name : 'Conversation' }}</h2>
            <p class="text-[11px] text-gray-400 truncate">
                @if($other)
                    {{ ucfirst(str_replace('_', ' ', $other->role)) }}
                    @if($other->department) · {{ $other->department->name }} @endif
                    <span x-text="otherOnline ? ' · Online' : ' · Offline'"></span>
                @endif
            </p>
        </div>
    </div>

    {{-- Messages --}}
    <div x-ref="messagesContainer" class="bg-[#F9F7F3] border-x border-gray-200 overflow-y-auto p-5 space-y-3" style="height: calc(100vh - 260px); min-height: 400px; scroll-behavior: smooth;">
        <template x-for="msg in messages" :key="msg.id">
            <div :class="msg.user_id == myId ? 'flex justify-end' : 'flex justify-start'">
                <div class="max-w-[70%] flex gap-2" :class="msg.user_id == myId ? 'flex-row-reverse' : ''">
                    {{-- Avatar --}}
                    <template x-if="msg.user_id != myId">
                        <div class="shrink-0 mt-1">
                            <template x-if="msg.user_image">
                                <img :src="msg.user_image" class="w-7 h-7 rounded-full object-cover" alt="">
                            </template>
                            <template x-if="!msg.user_image">
                                <div class="w-7 h-7 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] text-[10px] font-bold"
                                     x-text="msg.user_name.charAt(0).toUpperCase()"></div>
                            </template>
                        </div>
                    </template>
                    {{-- Bubble --}}
                    <div>
                        <div :class="msg.user_id == myId
                            ? 'bg-[#083321] text-white rounded-2xl rounded-br-md'
                            : 'bg-white text-gray-800 rounded-2xl rounded-tl-md border border-gray-100 shadow-sm'"
                             class="px-4 py-2.5 text-sm leading-relaxed">
                            <p x-text="msg.body"></p>
                            <template x-if="msg.attachment_path">
                                <a :href="msg.attachment_path" target="_blank" class="mt-1.5 inline-flex items-center gap-1.5 text-xs underline opacity-80">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                                    <span x-text="msg.attachment_name || 'Attachment'"></span>
                                </a>
                            </template>
                        </div>
                        <p :class="msg.user_id == myId ? 'text-right' : 'text-left'"
                           class="text-[10px] text-gray-400 mt-0.5 px-1" x-text="formatTime(msg.created_at)"></p>
                    </div>
                </div>
            </div>
        </template>

        {{-- Typing --}}
        <div x-show="otherTyping" class="flex justify-start">
            <div class="bg-white rounded-2xl px-4 py-3 border border-gray-100 flex items-center gap-1.5 shadow-sm">
                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
            </div>
        </div>
    </div>

    {{-- Input --}}
    <div class="bg-white rounded-b-xl border border-t-0 border-gray-200 shadow-sm px-5 py-3">
        <form @submit.prevent="send()" class="flex items-end gap-3" enctype="multipart/form-data">
            <label class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 transition shrink-0" title="Attach file">
                <input type="file" class="hidden" x-ref="fileInput" @change="attachFile($event)" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.mp4,.webm,.mov,video/mp4,video/webm,video/quicktime">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
            </label>
            <div class="flex-1 min-w-0">
                <div x-show="attachmentFile" class="mb-2 bg-gray-50 rounded-lg px-3 py-2 flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    <span class="truncate" x-text="attachmentFile ? attachmentFile.name : ''"></span>
                    <button type="button" @click="removeAttachment()" class="text-gray-400 hover:text-red-500 shrink-0">&times;</button>
                </div>
                <input x-model="newMessage" @input="emitTyping()" type="text" placeholder="Type a message..." autocomplete="off" class="w-full text-sm bg-gray-50 border-gray-200 focus:bg-white">
            </div>
            <button type="submit" :disabled="!newMessage.trim() && !attachmentFile"
                    class="p-2.5 bg-[#083321] text-white rounded-lg hover:bg-[#083321]/90 transition disabled:opacity-30 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            </button>
        </form>
    </div>
</div>

<script>
function conversationChat() {
    return {
        messages: @json($messages->map(fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'user_id' => $m->user_id,
            'user_name' => $m->user->name,
            'user_image' => $m->user->profile_image ? asset('storage/' . $m->user->profile_image) : null,
            'attachment_path' => $m->attachment_path ? asset('storage/' . $m->attachment_path) : null,
            'attachment_name' => $m->attachment_name,
            'created_at' => $m->created_at->toISOString(),
        ])),
        newMessage: '',
        myId: {{ auth()->id() }},
        lastMessageId: 0,
        pollInterval: null,
        otherTyping: false,
        otherOnline: {{ cache()->has('user_online_' . ($other ? $other->id : 0)) ? 'true' : 'false' }},
        attachmentFile: null,
        typingTimeout: null,
        maxAttachmentMb: {{ (int) config('uploads.max_upload_mb', 20) }},

        init() {
            this.lastMessageId = this.messages.length > 0 ? Math.max(...this.messages.map(m => m.id)) : 0;
            this.$nextTick(() => this.scrollToBottom());
            this.pollInterval = setInterval(() => this.poll(), 1500);
            // Heartbeat every 60s
            this.heartbeat();
            setInterval(() => this.heartbeat(), 60000);
        },

        async poll() {
            try {
                const res = await fetch('{{ route("admin.conversations.poll", $conversation) }}?after=' + this.lastMessageId);
                const data = await res.json();
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        if (!this.messages.find(m => m.id === msg.id)) {
                            this.messages.push(msg);
                            if (msg.id > this.lastMessageId) this.lastMessageId = msg.id;
                        }
                    });
                    this.$nextTick(() => this.scrollToBottom());
                }
                this.otherTyping = !!data.typing;
            } catch (e) {}
        },

        async send() {
            if (!this.newMessage.trim() && !this.attachmentFile) return;
            const formData = new FormData();
            if (this.newMessage.trim()) formData.append('message', this.newMessage);
            if (this.attachmentFile) formData.append('attachment', this.attachmentFile);
            const text = this.newMessage;
            this.newMessage = '';
            this.attachmentFile = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';

            try {
                const res = await fetch('{{ route("admin.conversations.send", $conversation) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();

                if (!res.ok) {
                    throw new Error(Object.values(data.errors || {}).flat()[0] || data.message || 'Failed to send message.');
                }

                if (data.id && !this.messages.find(m => m.id === data.id)) {
                    this.messages.push(data);
                    if (data.id > this.lastMessageId) this.lastMessageId = data.id;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                if (window.showLomoToast) {
                    window.showLomoToast(e.message || 'Failed to send message.', 'error');
                }
                this.newMessage = text;
            }
        },

        attachFile(event) {
            const file = event.target.files[0];
            if (file && file.size > this.maxAttachmentMb * 1024 * 1024) {
                if (window.showLomoToast) {
                    window.showLomoToast(`File too large. Maximum ${this.maxAttachmentMb}MB.`, 'warning');
                }
                event.target.value = '';
                return;
            }
            this.attachmentFile = file || null;
        },

        removeAttachment() {
            this.attachmentFile = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        async emitTyping() {
            if (this.typingTimeout) return;
            this.typingTimeout = setTimeout(() => { this.typingTimeout = null; }, 3000);
            try {
                await fetch('{{ route("admin.conversations.typing", $conversation) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: '{}'
                });
            } catch (e) {}
        },

        async heartbeat() {
            try {
                await fetch('{{ route("admin.conversations.heartbeat") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: '{}'
                });
            } catch (e) {}
        },

        scrollToBottom() {
            if (this.$refs.messagesContainer) this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
        },

        formatTime(date) {
            const d = new Date(date);
            const now = new Date();
            if (d.toDateString() === now.toDateString()) return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            return d.toLocaleDateString([], { month: 'short', day: 'numeric' }) + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
</x-app-layout>
