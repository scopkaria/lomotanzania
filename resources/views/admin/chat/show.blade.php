<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.chat.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            Chat with <span x-data x-text="$store.chat?.visitorName || '{{ $chatSession->visitor_name ?: 'Visitor #' . $chatSession->id }}'"></span>
        </div>
    </x-slot>

    <div x-data="adminChat({{ $chatSession->id }})" x-init="init()" class="flex flex-col gap-4 xl:h-[calc(100vh-10rem)] xl:flex-row">

        {{-- Chat Area --}}
        <div class="flex-1 bg-white rounded-2xl border border-gray-200 flex min-h-[70vh] flex-col xl:min-h-0">
            {{-- Header --}}
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] text-sm font-bold" x-text="(visitorName || 'V').charAt(0).toUpperCase()"></div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white" :class="isOnline ? 'bg-green-500' : 'bg-gray-300'"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900" x-text="visitorName || 'Unknown Visitor'"></p>
                        <p class="text-xs text-gray-500" x-show="isVisitorTyping" x-cloak>
                            <span class="text-green-600 animate-pulse">typing: </span><span class="text-gray-400 italic" x-text="visitorTypingText || '...'"></span>
                        </p>
                        <p class="text-xs text-gray-400" x-show="!isVisitorTyping">
                            <span x-text="isOnline ? 'Online' : 'Offline'"></span>
                            <span x-show="assignedDepartment" class="ml-1 text-gray-300">·</span>
                            <span x-show="assignedDepartment" x-text="assignedDepartment" class="font-medium" :style="'color: ' + (assignedDeptColor || '#083321')"></span>
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    {{-- Whisper toggle --}}
                    <button @click="whisperMode = !whisperMode; transferMode = false"
                            :class="whisperMode ? 'bg-purple-100 text-purple-700' : 'bg-gray-50 text-gray-500'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-50 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Whisper
                    </button>
                    {{-- Transfer --}}
                    <button @click="transferMode = !transferMode; whisperMode = false"
                            :class="transferMode ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-500'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-50 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        Transfer
                    </button>
                    @if($chatSession->status === 'active')
                        <button @click="closeChat()" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">Close</button>
                    @else
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg text-xs font-medium">{{ ucfirst($chatSession->status) }}</span>
                    @endif
                </div>
            </div>

            {{-- Transfer Panel --}}
            <div x-show="transferMode" x-cloak x-transition class="px-5 py-3 bg-blue-50 border-b border-blue-100 shrink-0">
                <p class="text-xs font-semibold text-blue-800 mb-2">Transfer chat to:</p>
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($workers as $w)
                    <button @click="selectedTransferTo = {{ $w->id }}; transferToName = '{{ e($w->name) }}'"
                            :class="selectedTransferTo === {{ $w->id }} ? 'ring-2 ring-blue-500 bg-white' : 'bg-white/60'"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs border border-blue-200 hover:bg-white transition">
                        <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-700">{{ strtoupper(substr($w->name, 0, 1)) }}</span>
                        <span>{{ $w->name }}</span>
                        @if($w->department)
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full" style="background-color: {{ $w->department->color }}15; color: {{ $w->department->color }}">{{ $w->department->name }}</span>
                        @endif
                    </button>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <input x-model="transferNote" type="text" placeholder="Transfer note (optional)..." class="flex-1 rounded-lg border-blue-200 text-xs focus:border-blue-400 focus:ring-blue-400">
                    <button @click="doTransfer()" :disabled="!selectedTransferTo" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 disabled:opacity-50 transition">Transfer</button>
                    <button @click="transferMode = false" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs">Cancel</button>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3" x-ref="messagesContainer" id="messages-container">
                <template x-for="msg in messages" :key="msg.id">
                    <div>
                        {{-- System messages --}}
                        <template x-if="msg.message_type === 'system'">
                            <div class="flex justify-center my-2">
                                <p class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full" x-text="msg.message"></p>
                            </div>
                        </template>
                        {{-- Whisper messages --}}
                        <template x-if="msg.message_type === 'whisper'">
                            <div class="flex justify-center my-2">
                                <div class="bg-purple-50 border border-purple-100 rounded-xl px-4 py-2 max-w-[80%]">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span class="text-[10px] font-semibold text-purple-600">Whisper from <span x-text="msg.sender_name"></span></span>
                                        <span x-show="msg.whisper_to_name" class="text-[10px] text-purple-400">→ <span x-text="msg.whisper_to_name"></span></span>
                                    </div>
                                    <p class="text-xs text-purple-800" x-text="msg.message"></p>
                                    <p class="text-[10px] text-purple-300 mt-0.5" x-text="msg.time"></p>
                                </div>
                            </div>
                        </template>
                        {{-- Normal messages --}}
                        <template x-if="msg.message_type === 'normal' || !msg.message_type">
                            <div :class="msg.sender_type === 'agent' ? 'flex justify-end' : 'flex justify-start'">
                                <div class="max-w-[70%]">
                                    {{-- Agent name + department --}}
                                    <div x-show="msg.sender_type === 'agent'" class="flex items-center justify-end gap-1.5 mb-0.5 mr-1">
                                        <span x-show="msg.sender_department" class="text-[10px] font-medium" :style="'color: ' + (assignedDeptColor || '#083321')" x-text="msg.sender_department"></span>
                                        <span class="text-[10px] text-gray-400" x-text="msg.sender_name"></span>
                                    </div>
                                    <div :class="msg.sender_type === 'agent'
                                            ? 'bg-[#083321] text-white rounded-2xl rounded-br-md'
                                            : 'bg-gray-100 text-gray-900 rounded-2xl rounded-bl-md'"
                                         class="px-4 py-2.5">
                                        <p class="text-sm whitespace-pre-wrap" x-text="msg.message"></p>
                                        <p class="text-[10px] mt-1 opacity-60" x-text="msg.time"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="isVisitorTyping" x-cloak class="flex justify-start">
                    <div class="bg-gray-100 rounded-2xl rounded-bl-md px-4 py-3">
                        <p x-show="visitorTypingText" class="text-sm text-gray-400 italic" x-text="visitorTypingText"></p>
                        <div class="flex gap-1 mt-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reply Box --}}
            @if($chatSession->status === 'active')
            <div class="px-5 py-3 border-t border-gray-100 shrink-0">
                {{-- Quick action shortcuts --}}
                <div class="flex gap-2 mb-2" x-data="{ qaOpen: null }">
                    {{-- Packages --}}
                    <div class="relative">
                        <button @click="qaOpen = qaOpen === 'safaris' ? null : 'safaris'" type="button"
                                class="px-2.5 py-1 text-[11px] font-medium rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            Packages
                        </button>
                        <div x-show="qaOpen === 'safaris'" @click.away="qaOpen = null" x-cloak x-transition
                             class="absolute bottom-full left-0 mb-1 w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-1 max-h-48 overflow-y-auto z-20">
                            @foreach($quickSafaris as $qs)
                                <button @click="newMessage += '{{ url('/safaris/' . $qs->slug) }}'; qaOpen = null" type="button"
                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 truncate">{{ $qs->title }}</button>
                            @endforeach
                        </div>
                    </div>
                    {{-- Destinations --}}
                    <div class="relative">
                        <button @click="qaOpen = qaOpen === 'dest' ? null : 'dest'" type="button"
                                class="px-2.5 py-1 text-[11px] font-medium rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            Destinations
                        </button>
                        <div x-show="qaOpen === 'dest'" @click.away="qaOpen = null" x-cloak x-transition
                             class="absolute bottom-full left-0 mb-1 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-1 max-h-48 overflow-y-auto z-20">
                            @foreach($quickDestinations as $qd)
                                <button @click="newMessage += '{{ url('/destinations/' . $qd->slug) }}'; qaOpen = null" type="button"
                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 truncate">{{ $qd->name }}</button>
                            @endforeach
                        </div>
                    </div>
                    {{-- Pages --}}
                    <div class="relative">
                        <button @click="qaOpen = qaOpen === 'pages' ? null : 'pages'" type="button"
                                class="px-2.5 py-1 text-[11px] font-medium rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            Pages
                        </button>
                        <div x-show="qaOpen === 'pages'" @click.away="qaOpen = null" x-cloak x-transition
                             class="absolute bottom-full left-0 mb-1 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-1 max-h-48 overflow-y-auto z-20">
                            @foreach($quickPages as $qp)
                                <button @click="newMessage += '{{ url('/' . $qp->slug) }}'; qaOpen = null" type="button"
                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 truncate">{{ $qp->title }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Whisper mode indicator --}}
                <div x-show="whisperMode" x-cloak class="flex items-center gap-2 mb-2 px-3 py-1.5 bg-purple-50 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-xs text-purple-700 font-medium">Whisper mode — only workers see this</span>
                    <select x-model="whisperTo" class="ml-auto text-xs border-purple-200 rounded-lg focus:border-purple-400 focus:ring-purple-400 py-1">
                        <option value="">All workers</option>
                        @foreach($workers as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}{{ $w->department ? ' ('.$w->department->name.')' : '' }}</option>
                        @endforeach
                    </select>
                    <button @click="whisperMode = false" class="text-purple-400 hover:text-purple-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form @submit.prevent="sendMessage()" class="flex gap-3">
                    <input x-model="newMessage" type="text"
                           :placeholder="whisperMode ? 'Whisper to workers...' : 'Type your reply...'"
                           :class="whisperMode ? 'border-purple-200 bg-purple-50/30 focus:border-purple-400 focus:ring-purple-400' : 'border-gray-300 focus:border-[#083321] focus:ring-[#083321]'"
                           @input="handleTyping()"
                           class="flex-1 rounded-xl shadow-sm text-sm">
                    <button type="submit" :disabled="!newMessage.trim()"
                            :class="whisperMode ? 'bg-purple-600 hover:bg-purple-700' : 'bg-[#083321] hover:bg-[#083321]/90'"
                            class="px-5 py-2.5 text-white rounded-xl text-sm font-medium disabled:opacity-50 transition">
                        <span x-text="whisperMode ? 'Whisper' : 'Send'"></span>
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Visitor Info Panel --}}
        <div class="w-full shrink-0 bg-white rounded-2xl border border-gray-200 flex flex-col xl:w-72">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Visitor Info</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Name</p>
                    <p class="text-sm text-gray-700" x-text="visitorName || 'Unknown'"></p>
                </div>
                <div x-show="visitorEmail">
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Email</p>
                    <p class="text-sm text-gray-700" x-text="visitorEmail"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">IP Address</p>
                    <p class="text-sm text-gray-700">{{ $chatSession->visitor_ip ?: 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Current Page</p>
                    <p class="text-sm text-[#083321] font-medium truncate" x-text="parsePageTitle(currentPage)"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">User Journey</p>
                    <div class="space-y-1 max-h-48 overflow-y-auto">
                        <template x-for="(page, index) in pageHistory" :key="index">
                            <div class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FEBC11] shrink-0 mt-1.5"></span>
                                <a :href="parsePageUrl(page)" target="_blank" class="text-xs text-[#083321] hover:underline truncate block" :title="page" x-text="parsePageTitle(page)"></a>
                            </div>
                            </div>
                        </template>
                        <p x-show="pageHistory.length === 0" class="text-xs text-gray-400">No pages tracked</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Session Started</p>
                    <p class="text-sm text-gray-700">{{ $chatSession->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Assigned To</p>
                    <p class="text-sm text-gray-700" x-text="assignedTo || 'Unassigned'"></p>
                    <p x-show="assignedDepartment" class="text-xs font-medium mt-0.5" :style="'color: ' + (assignedDeptColor || '#083321')" x-text="assignedDepartment"></p>
                </div>
                @if($chatSession->transferred_from)
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium mb-1">Transferred From</p>
                    <p class="text-sm text-gray-700">{{ $chatSession->transferredFrom->name }}</p>
                    @if($chatSession->transfer_note)
                        <p class="text-xs text-gray-500 mt-0.5 italic">"{{ $chatSession->transfer_note }}"</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <template x-if="showConfirm">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="confirmNo()">
                <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 animate-in">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Confirm</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-6" x-text="confirmMessage"></p>
                    <div class="flex justify-end gap-3">
                        <button @click="confirmNo()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                        <button @click="confirmYes()" class="px-4 py-2 text-sm font-medium text-white bg-[#083321] hover:bg-[#0a4a2e] rounded-lg transition">Confirm</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    @php
        $chatMessages = $chatSession->messages->map(fn($m) => [
            'id' => $m->id,
            'message' => $m->message,
            'sender_type' => $m->sender_type,
            'message_type' => $m->message_type ?? 'normal',
            'sender_name' => $m->sender_type === 'agent' ? ($m->user?->name ?? 'Agent') : ($chatSession->visitor_name ?? 'Visitor'),
            'sender_department' => $m->sender_type === 'agent' ? $m->user?->department?->name : null,
            'whisper_to_name' => $m->whisper_to ? $m->whisperRecipient?->name : null,
            'time' => $m->created_at->format('H:i'),
        ]);
        $isOnline = $chatSession->last_activity_at?->gt(now()->subMinutes(2)) ? 'true' : 'false';
        $lastMsgId = $chatSession->messages->last()?->id ?? 0;
    @endphp
    <script>
    function adminChat(sessionId) {
        return {
            sessionId: sessionId,
            messages: @json($chatMessages),
            newMessage: '',
            isVisitorTyping: false,
            visitorTypingText: '',
            isOnline: {{ $isOnline }},
            lastId: {{ $lastMsgId }},
            pageHistory: @json($chatSession->page_history ?? []),
            currentPage: @json($chatSession->current_page),
            visitorName: @json($chatSession->visitor_name ?: 'Visitor #' . $chatSession->id),
            visitorEmail: @json($chatSession->visitor_email),
            assignedTo: @json($chatSession->assignedAgent?->name),
            assignedDepartment: @json($chatSession->department?->name ?? $chatSession->assignedAgent?->department?->name),
            assignedDeptColor: @json($chatSession->department?->color ?? $chatSession->assignedAgent?->department?->color),
            pollInterval: null,
            typingTimeout: null,
            audioCtx: null,
            notificationPermission: Notification.permission || 'default',

            // Whisper mode
            whisperMode: false,
            whisperTo: '',

            // Transfer mode
            transferMode: false,
            selectedTransferTo: null,
            transferToName: '',
            transferNote: '',

            // Confirmation modal
            showConfirm: false,
            confirmMessage: '',
            confirmCallback: null,

            init() {
                this.$nextTick(() => this.scrollToBottom());
                this.pollInterval = setInterval(() => this.poll(), 1000);
                // Request browser notification permission
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(p => this.notificationPermission = p);
                }
            },

            async poll() {
                try {
                    const res = await fetch(`/admin/chat/${this.sessionId}/messages?last_id=${this.lastId}`, {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.messages.length > 0) {
                        const hasVisitorMsg = data.messages.some(m => m.sender_type === 'visitor');
                        data.messages.forEach(m => {
                            if (!this.messages.find(x => x.id === m.id)) {
                                this.messages.push(m);
                                this.lastId = m.id;
                            }
                        });
                        this.$nextTick(() => this.scrollToBottom());
                        if (hasVisitorMsg) {
                            this.playNotificationSound();
                            this.showBrowserNotification(data.messages.filter(m => m.sender_type === 'visitor'));
                        }
                    }
                    this.isVisitorTyping = data.is_visitor_typing;
                    this.visitorTypingText = data.visitor_typing_text || '';
                    this.pageHistory = data.page_history || [];
                    this.currentPage = data.current_page;
                    if (data.visitor_name) this.visitorName = data.visitor_name;
                    if (data.visitor_email) this.visitorEmail = data.visitor_email;
                } catch (e) {}
            },

            showBrowserNotification(visitorMessages) {
                if (!document.hasFocus() && 'Notification' in window && Notification.permission === 'granted') {
                    const last = visitorMessages[visitorMessages.length - 1];
                    new Notification('New message from ' + this.visitorName, {
                        body: last.message.substring(0, 100),
                        icon: '{{ asset("favicon.png") }}',
                        tag: 'chat-' + this.sessionId,
                    });
                }
            },

            async sendMessage() {
                if (!this.newMessage.trim()) return;
                const msg = this.newMessage;
                this.newMessage = '';

                if (this.whisperMode) {
                    await this.sendWhisper(msg);
                    return;
                }

                try {
                    const res = await fetch(`/admin/chat/${this.sessionId}/reply`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ message: msg })
                    });
                    const data = await res.json();
                    this.messages.push(data);
                    this.lastId = data.id;
                    if (data.sender_name && !this.assignedTo) this.assignedTo = data.sender_name;
                    this.$nextTick(() => this.scrollToBottom());
                } catch (e) { this.newMessage = msg; }
            },

            async sendWhisper(msg) {
                try {
                    const res = await fetch(`/admin/chat/${this.sessionId}/whisper`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ message: msg, whisper_to: this.whisperTo || null })
                    });
                    const data = await res.json();
                    this.messages.push(data);
                    this.lastId = data.id;
                    this.$nextTick(() => this.scrollToBottom());
                } catch (e) { this.newMessage = msg; }
            },

            askConfirm(msg, cb) {
                this.confirmMessage = msg;
                this.confirmCallback = cb;
                this.showConfirm = true;
            },

            confirmYes() {
                this.showConfirm = false;
                if (this.confirmCallback) this.confirmCallback();
                this.confirmCallback = null;
            },

            confirmNo() {
                this.showConfirm = false;
                this.confirmCallback = null;
            },

            async doTransfer() {
                if (!this.selectedTransferTo) return;
                this.askConfirm(`Transfer this chat to ${this.transferToName}?`, () => this._executeTransfer());
            },

            async _executeTransfer() {
                try {
                    const res = await fetch(`/admin/chat/${this.sessionId}/transfer`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ transfer_to: this.selectedTransferTo, note: this.transferNote })
                    });
                    const data = await res.json();
                    if (data.ok) {
                        this.assignedTo = data.assigned_to;
                        this.assignedDepartment = data.department;
                        this.transferMode = false;
                        this.selectedTransferTo = null;
                        this.transferNote = '';
                        // System message will appear via polling
                    }
                } catch (e) { if (Alpine.store('toast')) Alpine.store('toast').show('Transfer failed', 'error'); }
            },

            handleTyping() {
                if (this.whisperMode) return; // Don't send typing for whispers
                clearTimeout(this.typingTimeout);
                fetch(`/admin/chat/${this.sessionId}/typing`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ typing: true })
                });
                this.typingTimeout = setTimeout(() => {
                    fetch(`/admin/chat/${this.sessionId}/typing`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                        body: JSON.stringify({ typing: false })
                    });
                }, 3000);
            },

            async closeChat() {
                this.askConfirm('Close this chat session?', async () => {
                    await fetch(`/admin/chat/${this.sessionId}/close`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                    });
                    window.location.href = '{{ route("admin.chat.index") }}';
                });
            },

            scrollToBottom() {
                const el = this.$refs.messagesContainer;
                if (el) el.scrollTop = el.scrollHeight;
            },

            parsePageTitle(entry) {
                if (!entry) return 'Unknown';
                // Format: "Page Title — https://example.com/path"
                const sep = entry.indexOf(' — ');
                if (sep > 0) return entry.substring(0, sep);
                // Fallback: extract path from URL
                try {
                    const url = new URL(entry);
                    return url.pathname === '/' ? 'Home' : decodeURIComponent(url.pathname.replace(/^\/|\/$/g, '').replace(/[-_]/g, ' '));
                } catch(e) { return entry; }
            },

            parsePageUrl(entry) {
                if (!entry) return '#';
                const sep = entry.indexOf(' — ');
                if (sep > 0) return entry.substring(sep + 3);
                return entry.startsWith('http') ? entry : '#';
            },

            playNotificationSound() {
                if (!{{ json_encode($soundSettings?->notification_sound_enabled ?? true) }}) return;
                try {
                    if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const ctx = this.audioCtx;
                    const now = ctx.currentTime;
                    const volMap = { low: 0.06, medium: 0.15, high: 0.3 };
                    const vol = volMap[@json($soundSettings?->notification_sound_volume ?? 'medium')] || 0.15;
                    // Warm three-note safari chime
                    const gain = ctx.createGain();
                    gain.connect(ctx.destination);
                    gain.gain.setValueAtTime(vol, now);
                    gain.gain.exponentialRampToValueAtTime(0.01, now + 0.8);

                    [523.25, 659.25, 783.99].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        osc.type = 'sine';
                        osc.frequency.value = freq;
                        osc.connect(gain);
                        osc.start(now + i * 0.12);
                        osc.stop(now + i * 0.12 + 0.25);
                    });
                } catch(e) {}
            }
        };
    }
    </script>
    @endpush
</x-app-layout>
