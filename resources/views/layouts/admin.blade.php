<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin — Lomo Tanzania Safari</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Great+Vibes&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' },
                    },
                    fontFamily: { heading: ['"Cormorant Garamond"', 'Georgia', 'serif'], body: ['Lato', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Lato', sans-serif; font-size: 0.9375rem; line-height: 1.6; color: #2D2D2D; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #131414; letter-spacing: 0.01em; line-height: 1.2; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
        .admin-table th { position: sticky; top: 0; z-index: 10; }
        .bulk-bar { position: sticky; bottom: 0; z-index: 20; }

        /* ── GLOBAL FORM INPUT STYLES ── */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="url"],
        input[type="tel"],
        input[type="search"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="time"],
        input[type="month"],
        input[type="week"],
        select,
        textarea {
            display: block;
            width: 100%;
            min-height: 44px !important;
            padding: 0.625rem 0.875rem !important;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #1f2937;
            background-color: #ffffff;
            border: 1.5px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: border-color 150ms ease, box-shadow 150ms ease;
            -webkit-appearance: none;
            appearance: none;
        }
        input[type="text"]:hover,
        input[type="email"]:hover,
        input[type="password"]:hover,
        input[type="number"]:hover,
        input[type="url"]:hover,
        input[type="tel"]:hover,
        input[type="search"]:hover,
        input[type="date"]:hover,
        input[type="datetime-local"]:hover,
        input[type="time"]:hover,
        input[type="month"]:hover,
        input[type="week"]:hover,
        select:hover,
        textarea:hover {
            border-color: #9ca3af;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        input[type="url"]:focus,
        input[type="tel"]:focus,
        input[type="search"]:focus,
        input[type="date"]:focus,
        input[type="datetime-local"]:focus,
        input[type="time"]:focus,
        input[type="month"]:focus,
        input[type="week"]:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            border-color: #FEBC11 !important;
            box-shadow: 0 0 0 3px rgba(254, 188, 17, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        }
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }
        input:disabled,
        select:disabled,
        textarea:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
            opacity: 0.7;
        }
        input[type="checkbox"],
        input[type="radio"] {
            min-height: auto;
            padding: 0;
            border-width: 1.5px;
            box-shadow: none;
        }
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
                {{-- Page content --}}
                {{ $slot }}
            </main>
        </div>

    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        // UPDATED: Enhanced adminTable with sorting, screen options, clickable rows
        Alpine.data('adminTable', (config = {}) => ({
            selected: [],
            selectAll: false,
            allIds: config.ids || [],
            columns: JSON.parse(localStorage.getItem('cols_' + (config.key || 'default')) || 'null') || config.columns || {},
            showColumnMenu: false,
            // ADDED: sorting
            sortField: config.sortField || '',
            sortDir: config.sortDir || 'asc',
            // ADDED: screen options
            showScreenOptions: false,

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

            // ADDED: sorting via URL params
            sortBy(field) {
                const url = new URL(window.location);
                if (this.sortField === field) {
                    this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDir = 'asc';
                }
                url.searchParams.set('sort', this.sortField);
                url.searchParams.set('direction', this.sortDir);
                url.searchParams.delete('page');
                window.location = url.toString();
            },

            // ADDED: clickable row navigation
            rowClick(editUrl, event) {
                if (event.target.closest('a, button, input, form, [x-on\\:click], [\\@click]')) return;
                window.location = editUrl;
            },

            async submitBulk(action) {
                if (this.selected.length === 0) return;
                if (action === 'delete') {
                    const confirmed = await window.showLomoConfirm({
                        title: 'Delete selected items',
                        message: 'Delete ' + this.selected.length + ' selected item(s)?',
                        confirmText: 'Delete items',
                        tone: 'danger',
                    });

                    if (!confirmed) return;
                }

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

    @include('partials.global-toast')

    @if(session('success'))
        <div x-data x-init="setTimeout(() => window.showLomoToast(@js(session('success')), 'success'), 30)" class="hidden"></div>
    @endif

    @if(session('error'))
        <div x-data x-init="setTimeout(() => window.showLomoToast(@js(session('error')), 'error'), 30)" class="hidden"></div>
    @endif

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
