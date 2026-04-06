<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin — Lomo Tanzania Safari</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' },
                    },
                    fontFamily: { body: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
        .admin-table th { position: sticky; top: 0; z-index: 10; }
        .bulk-bar { position: sticky; bottom: 0; z-index: 20; }
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-body antialiased" x-data="{ sidebarOpen: false }">

    {{-- Root: flex row, full viewport height --}}
    <div class="flex h-screen bg-[#F9F7F3]">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        {{-- ============ SIDEBAR ============ --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col
                      transform transition-transform duration-200 ease-in-out
                      lg:translate-x-0 lg:static lg:shrink-0">

            {{-- Logo --}}
            <div class="flex items-center justify-center px-6 py-5 border-b border-gray-100 shrink-0">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="Admin" class="h-12 w-auto object-contain">
                @else
                    <div class="w-11 h-11 rounded-xl bg-[#083321] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                    </div>
                @endif
                <button @click="sidebarOpen = false" class="absolute right-3 top-5 lg:hidden text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">
                @include('admin.partials.sidebar-nav')
            </nav>

            {{-- Footer --}}
            <div class="border-t border-gray-100 px-4 py-3 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#083321] flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ============ MAIN AREA ============ --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-gray-200 shrink-0">
                <div class="flex items-center justify-between h-full px-6">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 -ml-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        @isset($header)
                            <h1 class="text-lg font-semibold text-gray-800">{{ $header }}</h1>
                        @endisset
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Chat badge --}}
                        <a href="{{ route('admin.chat.index') }}" class="relative text-gray-400 hover:text-gray-600 transition" title="Live Chat"
                           x-data="{ chatCount: 0 }" x-init="
                               fetch('{{ route('admin.chat.unread-count') }}').then(r => r.json()).then(d => chatCount = d.count);
                               setInterval(() => fetch('{{ route('admin.chat.unread-count') }}').then(r => r.json()).then(d => chatCount = d.count), 15000);
                           ">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span x-show="chatCount > 0" x-text="chatCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                        </a>
                        {{-- Notifications bell --}}
                        <a href="{{ route('admin.notifications.index') }}" class="relative text-gray-400 hover:text-gray-600 transition" title="Notifications"
                           x-data="{ notiCount: 0 }" x-init="
                               fetch('{{ route('admin.notifications.fetch') }}').then(r => r.json()).then(d => notiCount = d.filter(n => !n.read_at).length);
                               setInterval(() => fetch('{{ route('admin.notifications.fetch') }}').then(r => r.json()).then(d => notiCount = d.filter(n => !n.read_at).length), 15000);
                           ">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            <span x-show="notiCount > 0" x-text="notiCount" class="absolute -top-1 -right-1 bg-[#FEBC11] text-[#131414] text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                        </a>
                        <span class="hidden sm:inline text-sm text-gray-500">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-brand-gold/20 flex items-center justify-center text-brand-gold text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mb-6">
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6">
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                {{-- Page content --}}
                {{ $slot }}
            </main>
        </div>

    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminTable', (config = {}) => ({
            selected: [],
            selectAll: false,
            allIds: config.ids || [],
            columns: JSON.parse(localStorage.getItem('cols_' + (config.key || 'default')) || 'null') || config.columns || {},
            showColumnMenu: false,

            toggleSelectAll() {
                this.selectAll = !this.selectAll;
                this.selected = this.selectAll ? [...this.allIds] : [];
            },

            toggleRow(id) {
                const idx = this.selected.indexOf(id);
                if (idx > -1) { this.selected.splice(idx, 1); }
                else { this.selected.push(id); }
                this.selectAll = this.selected.length === this.allIds.length && this.allIds.length > 0;
            },

            isSelected(id) {
                return this.selected.includes(id);
            },

            get selectionCount() {
                return this.selected.length;
            },

            isVisible(col) {
                return this.columns[col] !== false;
            },

            toggleColumn(col) {
                this.columns[col] = this.columns[col] === false ? true : false;
                localStorage.setItem('cols_' + (config.key || 'default'), JSON.stringify(this.columns));
            },

            submitBulk(action) {
                if (this.selected.length === 0) return;
                if (action === 'delete' && !confirm('Delete ' + this.selected.length + ' selected item(s)?')) return;

                const form = this.$refs.bulkForm;
                form.querySelector('[name="action"]').value = action;
                form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

                this.selected.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                form.submit();
            }
        }));
    });
    </script>

    {{-- ============ GLOBAL TOAST NOTIFICATIONS ============ --}}
    <div x-data x-init="$store.toast || Alpine.store('toast', { items: [], show(msg, type='success', dur=4000) { const id=Date.now(); this.items.push({id,msg,type}); setTimeout(()=>this.items=this.items.filter(t=>t.id!==id), dur); } })"
         class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none" style="max-width:380px">
        <template x-for="t in $store.toast.items" :key="t.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 :class="{
                    'bg-green-600': t.type==='success',
                    'bg-red-600': t.type==='error',
                    'bg-blue-600': t.type==='info',
                    'bg-amber-500': t.type==='warning',
                    'bg-[#083321]': t.type==='chat'
                 }"
                 class="text-white px-4 py-3 rounded-xl shadow-xl text-sm font-medium flex items-center gap-3 pointer-events-auto">
                <template x-if="t.type==='success'">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </template>
                <template x-if="t.type==='error'">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                </template>
                <template x-if="t.type==='chat'">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </template>
                <template x-if="t.type==='info' || t.type==='warning'">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                </template>
                <span x-text="t.msg" class="flex-1"></span>
                <button @click="$store.toast.items = $store.toast.items.filter(i=>i.id!==t.id)" class="text-white/60 hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    {{-- ============ GLOBAL CHAT NOTIFICATION SOUND ============ --}}
    <div x-data="{
        lastUnread: -1,
        audioCtx: null,
        init() {
            this.poll();
            setInterval(() => this.poll(), 5000);
        },
        async poll() {
            try {
                const res = await fetch('{{ route("admin.chat.unread-count") }}');
                const data = await res.json();
                const count = data.count || 0;
                if (this.lastUnread >= 0 && count > this.lastUnread) {
                    this.playSound();
                    if (typeof Alpine !== 'undefined' && Alpine.store('toast')) {
                        Alpine.store('toast').show('New chat message received!', 'chat');
                    }
                }
                this.lastUnread = count;
            } catch(e) {}
        },
        playSound() {
            try {
                if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = this.audioCtx;
                const now = ctx.currentTime;
                const gain = ctx.createGain();
                gain.connect(ctx.destination);
                gain.gain.setValueAtTime(0.15, now);
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
    }" x-init="init()" class="hidden"></div>

    @stack('scripts')
</body>
</html>
