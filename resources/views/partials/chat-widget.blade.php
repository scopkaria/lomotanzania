{{-- Live Chat Widget — AI auto-responder, quick actions, emoji, lead capture --}}
@php
    $chatSettings = \App\Models\Setting::first();
    $chatLocale = app()->getLocale();
    $chatSupportedLocales = \App\Http\Middleware\SetLocale::SUPPORTED;
    $chatQuickActionUrls = [
        'packages' => route('safaris.index', ['locale' => $chatLocale], false),
        'five_day_packages' => route('safaris.index', ['locale' => $chatLocale, 'days' => 5], false),
        'contact' => route('contact', ['locale' => $chatLocale], false),
        'about' => route('page.show', ['locale' => $chatLocale, 'slug' => 'about-us'], false),
        'home' => route('home', ['locale' => $chatLocale], false),
    ];
@endphp
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
                    <p class="text-white font-semibold text-sm truncate" x-text="agentInfo ? agentInfo.name : (sessionId ? 'AI Safari Agent' : '{{ optional($siteSetting ?? null)->site_name ?? 'Lomo Safari' }}')"></p>
                    <p class="text-white/70 text-xs truncate">
                        <template x-if="agentInfo && agentInfo.department">
                            <span x-text="agentInfo.department + ' · Online'"></span>
                        </template>
                        <template x-if="!agentInfo || !agentInfo.department">
                            <span x-text="sessionId ? (agentJoined ? 'Live agent connected' : (connectingToSupport ? 'Connecting to support...' : 'AI-powered assistant')) : 'We reply instantly'"></span>
                        </template>
                    </p>
                </div>
            </div>
                <button @click="if(sessionId) { window.showLomoConfirm({ title: 'End chat', message: 'End this chat?', confirmText: 'End chat', tone: 'danger' }).then(confirmed => { if (confirmed) endChat(); }); } else { open=false; }"
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
            <div class="flex-1 flex flex-col min-h-0 relative">
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
                            {{-- Bot / AI messages --}}
                            <template x-if="msg.sender_type === 'bot'">
                                <div class="flex justify-start">
                                    <div class="max-w-[85%]">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center" :class="msg.aiProvider === 'gemini' ? 'bg-gradient-to-br from-blue-500 to-purple-500' : 'bg-[#FEBC11]/20'">
                                                <template x-if="msg.aiProvider === 'gemini'">
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM4.343 4.343a.75.75 0 011.06 0l1.061 1.06a.75.75 0 01-1.06 1.061l-1.06-1.06a.75.75 0 010-1.06zM13.536 13.536a.75.75 0 011.06 0l1.061 1.06a.75.75 0 01-1.06 1.061l-1.06-1.06a.75.75 0 010-1.06zM2 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 012 10zM15 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 0115 10zM4.343 15.657a.75.75 0 010-1.06l1.061-1.061a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM13.536 6.464a.75.75 0 010-1.06l1.061-1.061a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0z"/><circle cx="10" cy="10" r="3"/></svg>
                                                </template>
                                                <template x-if="msg.aiProvider !== 'gemini'">
                                                    <svg class="w-3 h-3 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                </template>
                                            </span>
                                            <span class="text-[10px] font-medium" :class="msg.aiProvider === 'gemini' ? 'text-purple-500' : 'text-gray-400'" x-text="msg.aiProvider === 'gemini' ? 'AI Safari Agent' : 'Safari Assistant'"></span>
                                        </div>
                                        <div class="text-gray-800 rounded-2xl rounded-tl-md px-4 py-2.5 text-sm leading-relaxed" :class="msg.aiProvider === 'gemini' ? 'bg-gradient-to-br from-blue-50 to-purple-50 border border-purple-100' : 'bg-[#FEBC11]/10'">
                                            <p x-html="normalizeMessageHtml(msg.message)"></p>
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
                                            <p x-html="linkify(msg.message)"></p>
                                            <p :class="msg.sender_type === 'visitor' ? 'text-white/40' : 'text-gray-400'"
                                               class="text-[10px] mt-1" x-text="formatTime(msg.created_at)"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Quick action pills --}}
                    <div x-show="showQuickActions && !agentJoined && messages.length <= 5" x-transition class="flex flex-wrap gap-2 pt-2">
                        <template x-for="action in quickActions" :key="action.label">
                            <button @click="handleQuickAction(action)"
                                    class="text-xs px-3 py-1.5 rounded-full border border-[#083321]/20 text-[#083321] bg-[#083321]/5 hover:bg-[#083321]/10 transition font-medium whitespace-nowrap">
                                <span x-text="action.icon + ' ' + action.label"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Department picker pills --}}
                    <div x-show="showDepartments && !agentJoined && !connectingToSupport" x-transition class="pt-2">
                        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-1">💬 Connect to Live Support</p>
                            <p class="text-[11px] text-gray-500 mb-3">Choose a department to speak with our team:</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="dept in departments" :key="dept.id">
                                    <button @click="connectToSupport(dept)"
                                            class="text-xs px-4 py-2 rounded-full font-semibold text-white transition hover:brightness-110 hover:scale-105 shadow-sm"
                                            :style="'background-color:' + (dept.color || '#083321')">
                                        <span x-text="dept.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Connect to support button (shows after failed AI responses) --}}
                    <div x-show="failedAiCount >= 1 && !showDepartments && !agentJoined && !connectingToSupport" x-transition class="flex justify-center pt-2">
                        <button @click="showDepartmentPicker()"
                                class="text-xs px-4 py-2 rounded-full bg-[#083321] text-white font-semibold hover:bg-[#083321]/90 transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                            Talk to Live Support
                        </button>
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

                {{-- Chat expired overlay --}}
                <div x-show="chatExpired" x-transition class="absolute inset-0 bg-white/95 backdrop-blur-sm z-10 flex items-center justify-center p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-gray-800 font-semibold text-sm mb-1">Chat Session Expired</h3>
                        <p class="text-gray-500 text-xs mb-4">This session has timed out for your security. Start a new conversation to continue.</p>
                        <button @click="startNewChat()" class="bg-[#083321] text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-[#083321]/90 transition">
                            Start New Chat
                        </button>
                    </div>
                </div>

                {{-- Input area with emoji --}}
                <div class="border-t border-gray-100 p-3 shrink-0 bg-white" x-show="!chatExpired">
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

    {{-- Welcome Notification Popup --}}
    <div x-show="showNotification && !open" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="mb-3 ml-auto bg-white rounded-2xl shadow-xl border border-gray-100 p-4 max-w-[320px] relative">
        <button @click.stop="showNotification = false; notificationDismissed = true;"
                class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center text-gray-300 hover:text-gray-500 transition rounded-full" title="Dismiss">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM4.343 4.343a.75.75 0 011.06 0l1.061 1.06a.75.75 0 01-1.06 1.061l-1.06-1.06a.75.75 0 010-1.06zM13.536 13.536a.75.75 0 011.06 0l1.061 1.06a.75.75 0 01-1.06 1.061l-1.06-1.06a.75.75 0 010-1.06z"/><circle cx="10" cy="10" r="3"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800">AI Safari Agent</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $chatSettings->chat_greeting ?? 'Hello! 👋 Need help planning your safari?' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <button @click="showNotification = false; notificationDismissed = true; open = true;"
                    class="flex-1 text-xs font-semibold py-2 rounded-lg transition flex items-center justify-center gap-1.5 bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:brightness-110 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2z"/><circle cx="10" cy="10" r="3"/></svg>
                Chat with AI
            </button>
            <button @click="showNotification = false; notificationDismissed = true; open = true; $nextTick(() => { if(!sessionId) return; showDepartmentPicker(); });"
                    class="flex-1 text-xs font-semibold py-2 rounded-lg transition flex items-center justify-center gap-1.5 bg-[#083321] text-white hover:bg-[#083321]/90 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                Live Support
            </button>
        </div>
        <div class="mt-2 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
            <span class="text-[10px] text-green-600 font-medium">Online now</span>
        </div>
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

        publicLocale: @json($chatLocale),
        supportedLocales: @json($chatSupportedLocales),
        quickActions: [
            { label: 'View Packages', icon: '📦', url: @json($chatQuickActionUrls['packages']), message: "I'd like to see your safari packages" },
            { label: '5 Days Safari', icon: '🦁', url: @json($chatQuickActionUrls['five_day_packages']), message: "I'm interested in a 5-day safari" },
            { label: 'Contact Us', icon: '📞', url: @json($chatQuickActionUrls['contact']), message: 'I need to contact your team' },
            { label: 'About Us', icon: 'ℹ️', url: @json($chatQuickActionUrls['about']), message: 'Tell me about Lomo Safari' },
            { label: 'Home Page', icon: '🏠', url: @json($chatQuickActionUrls['home']), message: 'Take me to the home page' },
        ],

        aiResponses: {},
        chatExpired: false,
        showNotification: false,
        notificationDismissed: false,
        sessionExpiresAt: null,
        expiryCheckInterval: null,
        SESSION_DURATION: 20 * 60 * 1000, // 20 minutes
        departments: [],
        showDepartments: false,
        connectingToSupport: false,
        failedAiCount: 0,

        async getAiResponse(text) {
            if (this.agentJoined) return null;
            try {
                const res = await fetch('/api/chat/' + this.sessionId + '/ai-response', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: text })
                });
                const data = await res.json();
                if (data.response) {
                    // If the AI offered support or we've had too many fallbacks, show department pills
                    if (data.offer_support) {
                        this.failedAiCount++;
                        if (this.failedAiCount >= 2) {
                            // After 2 failed attempts, auto-show departments
                            setTimeout(() => this.showDepartmentPicker(), 500);
                        }
                    } else {
                        this.failedAiCount = 0;
                    }
                    return { text: data.response, provider: data.provider || 'keyword' };
                }
                return null;
            } catch (e) {
                return null;
            }
        },

        async loadDepartments() {
            if (this.departments.length > 0) return;
            try {
                const res = await fetch('/api/chat/departments');
                const data = await res.json();
                this.departments = data.departments || [];
            } catch (e) {}
        },

        showDepartmentPicker() {
            this.loadDepartments();
            this.showDepartments = true;
            this.$nextTick(() => this.scrollToBottom());
        },

        async connectToSupport(dept) {
            this.showDepartments = false;
            this.connectingToSupport = true;
            this.messages.push({
                id: 'sys-dept-' + Date.now(),
                message: 'Connecting you to ' + dept.name + ' department...',
                sender_type: 'system',
                message_type: 'system',
                created_at: new Date().toISOString()
            });
            this.persistState();
            this.$nextTick(() => this.scrollToBottom());

            try {
                const res = await fetch('/api/chat/' + this.sessionId + '/request-support', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ department_id: dept.id })
                });
                const data = await res.json();
                if (data.ok) {
                    this.messages.push({
                        id: 'bot-connect-' + Date.now(),
                        message: '✅ You\'ve been connected to the <strong>' + dept.name + '</strong> department. A team member will be with you shortly! Please wait while we get someone to help you.',
                        sender_type: 'bot',
                        aiProvider: 'keyword',
                        created_at: new Date().toISOString()
                    });
                }
            } catch (e) {
                this.messages.push({
                    id: 'bot-err-' + Date.now(),
                    message: 'Sorry, I couldn\'t connect you right now. Please try again or use our <a href="' + this.normalizePublicHref(@json($chatQuickActionUrls['contact'])) + '" target="_blank" class="underline font-medium">contact page</a>.',
                    sender_type: 'bot',
                    aiProvider: 'keyword',
                    created_at: new Date().toISOString()
                });
            }
            this.connectingToSupport = false;
            this.persistState();
            this.$nextTick(() => this.scrollToBottom());
        },

        init() {
            // Always start a fresh session — clear any previous state
            localStorage.removeItem(this.storageKey);
            this.sessionId = null;
            this.messages = [];
            this.lastMessageId = 0;
            this.agentJoined = false;
            this.agentInfo = null;
            this.chatExpired = false;

            ['click', 'keydown', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, () => { this.hasInteracted = true; }, { once: true });
            });
            if (!this.visitorId) {
                this.visitorId = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'visitor-' + Date.now();
            }

            // Show a welcome notification after 3 seconds
            setTimeout(() => {
                if (!this.open && !this.notificationDismissed) {
                    this.showNotification = true;
                    // Auto-hide after 10 seconds
                    setTimeout(() => { this.showNotification = false; }, 10000);
                }
            }, 3000);

            this.$watch('open', () => {
                this.persistState();
                if (this.open) this.showNotification = false;
            });
            this.$watch('visitorName', () => this.persistState());
            this.$watch('visitorEmail', () => this.persistState());
            this.$watch('sessionId', () => this.persistState());
        },

        toggleChat() {
            this.open = !this.open;
            if (this.open) {
                this.unreadCount = 0;
                this.showEmoji = false;
                this.showNotification = false;
                this.notificationDismissed = true;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        restoreState() {
            // State is only used within a tab session (via persistState).
            // Fresh sessions are enforced by init() clearing localStorage.
            try {
                const saved = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
                if (!saved.sessionId) return;

                this.open = !!saved.open;
                this.sessionId = saved.sessionId || null;
                this.visitorId = saved.visitorId || null;
                this.visitorName = saved.visitorName || '';
                this.visitorEmail = saved.visitorEmail || '';
                this.messages = Array.isArray(saved.messages) ? saved.messages : [];
                this.lastMessageId = Number(saved.lastMessageId || 0);
                this.agentJoined = !!saved.agentJoined;
                this.agentInfo = saved.agentInfo || null;
                this.chatExpired = !!saved.chatExpired;
                this.sessionExpiresAt = saved.sessionExpiresAt || null;
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
                    chatExpired: this.chatExpired,
                    sessionExpiresAt: this.sessionExpiresAt,
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
                    this.messages.push({ id: 'g0', message: data.greeting, sender_type: 'bot', aiProvider: 'keyword', created_at: new Date().toISOString() });
                }
                this.showQuickActions = true;
                this.startPolling();
                this.trackPage();
                this.startOfflineMonitor();
                this.startExpiryTimer();
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
                this.getAiResponse(msg).then(aiResult => {
                    if (aiResult && !this.agentJoined) {
                        this.messages.push({
                            id: 'bot' + Date.now(),
                            message: aiResult.text,
                            sender_type: 'bot',
                            aiProvider: aiResult.provider || 'keyword',
                            created_at: new Date().toISOString()
                        });
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
                    body: JSON.stringify({ url: this.normalizePublicHref(action.url), title: 'Quick Action: ' + action.label })
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
                this.messages.push({ id: 'lead' + Date.now(), message: 'Your message has been saved. Our team will reach out via email shortly! 📧', sender_type: 'bot', aiProvider: 'keyword', created_at: new Date().toISOString() });
                this.persistState();
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) {
                if (window.showLomoToast) {
                    window.showLomoToast('Failed to send. Please try again.', 'error');
                }
            }
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
            if (this.expiryCheckInterval) { clearInterval(this.expiryCheckInterval); this.expiryCheckInterval = null; }
            this.open = false;
            this.persistState();
        },

        startExpiryTimer() {
            this.sessionExpiresAt = Date.now() + this.SESSION_DURATION;
            this.chatExpired = false;
            if (this.expiryCheckInterval) clearInterval(this.expiryCheckInterval);
            this.expiryCheckInterval = setInterval(() => {
                if (Date.now() >= this.sessionExpiresAt) {
                    this.expireChat();
                }
            }, 5000);
        },

        expireChat() {
            if (this.expiryCheckInterval) { clearInterval(this.expiryCheckInterval); this.expiryCheckInterval = null; }
            if (this.pollInterval) { clearInterval(this.pollInterval); this.pollInterval = null; }
            this.clearOfflineTimer();
            this.chatExpired = true;
            // Close session on server
            if (this.sessionId) {
                fetch('/api/chat/' + this.sessionId + '/end', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).catch(() => {});
            }
            this.persistState();
        },

        startNewChat() {
            this.sessionId = null;
            this.messages = [];
            this.agentInfo = null;
            this.agentJoined = false;
            this.lastMessageId = 0;
            this.showOfflineForm = false;
            this.leadSubmitted = false;
            this.showQuickActions = true;
            this.headerColor = '#083321';
            this.chatExpired = false;
            this.clearOfflineTimer();
            if (this.pollInterval) { clearInterval(this.pollInterval); this.pollInterval = null; }
            if (this.expiryCheckInterval) { clearInterval(this.expiryCheckInterval); this.expiryCheckInterval = null; }
            localStorage.removeItem(this.storageKey);
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
                this.open = true;
                if (window.showLomoToast) {
                    window.showLomoToast('Please enter your name and email first.', 'warning');
                }
                return;
            }
            const msg = 'Hello, my name is ' + this.visitorName + ' (' + this.visitorEmail + '). I am viewing ' + document.title + ' - ' + window.location.href + ' and would like help planning my safari.';
            window.open('https://wa.me/{{ preg_replace("/[^0-9]/", "", $chatSettings->whatsapp_number ?? "") }}?text=' + encodeURIComponent(msg), '_blank', 'noopener');
        },

        scrollToBottom() {
            if (this.$refs.chatMessages) this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
        },

        normalizeMessageHtml(html) {
            if (!html) return '';

            return html.replace(/href=(["'])([^"']+)\1/gi, (match, quote, href) => {
                return 'href=' + quote + this.normalizePublicHref(href) + quote;
            });
        },

        getActiveLocale() {
            const firstSegment = window.location.pathname.replace(/^\/+/, '').split('/')[0] || '';
            return this.supportedLocales.includes(firstSegment) ? firstSegment : this.publicLocale;
        },

        normalizePublicHref(url) {
            if (!url) return '#';

            const candidate = url.replace(/&amp;/g, '&');
            if (/^(mailto:|tel:|#|javascript:)/i.test(candidate)) {
                return candidate;
            }

            try {
                const parsed = new URL(candidate, window.location.origin);
                if (!/^https?:$/i.test(parsed.protocol)) {
                    return candidate;
                }

                if (parsed.origin !== window.location.origin) {
                    return parsed.toString();
                }

                const locale = this.getActiveLocale();
                const segments = parsed.pathname.replace(/^\/+|\/+$/g, '').split('/').filter(Boolean);
                const firstSegment = segments[0] || '';

                if (segments.length === 0) {
                    parsed.pathname = '/' + locale;
                } else if (!['admin', 'api', 'storage', 'build'].includes(firstSegment)) {
                    if (this.supportedLocales.includes(firstSegment)) {
                        segments.shift();
                    }

                    parsed.pathname = '/' + locale + (segments.length ? '/' + segments.join('/') : '');
                }

                if (/^https?:\/\//i.test(candidate)) {
                    return parsed.toString();
                }

                return parsed.pathname + parsed.search + parsed.hash;
            } catch (e) {
                return candidate;
            }
        },

        linkify(text) {
            if (!text) return '';

            const escaped = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

            return escaped.replace(
                /((?:https?:\/\/[^\s<"']+)|(?:\/(?!\/)[^\s<"']+))/gi,
                (match) => '<a href="' + this.normalizePublicHref(match) + '" target="_blank" rel="noopener" class="underline font-medium hover:opacity-80 break-all">' + match + '</a>'
            );
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
