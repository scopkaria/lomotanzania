<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agent Portal — Lomo Tanzania Safari</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
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
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-body antialiased" x-data="{ sidebarOpen: false }">
<div class="flex h-screen bg-[#F9F7F3]">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#131414] flex flex-col
                  transform transition-transform duration-200 ease-in-out
                  lg:translate-x-0 lg:static lg:shrink-0">

        <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-brand-gold flex items-center justify-center">
                <svg class="w-4 h-4 text-[#131414]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-white font-bold text-sm tracking-wide">LOMO</span>
                <span class="text-brand-gold font-bold text-sm ml-0.5">AGENT</span>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-white/60 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">
            @php
                $cur = request()->route()->getName();
                $pendingResponses = auth()->user()->agent
                    ? auth()->user()->agent->safariRequests()->whereHas('response', fn($q) => $q->where('status','pending'))->count()
                    : 0;
            @endphp

            @foreach([
                ['route' => 'agent.dashboard',      'label' => 'Dashboard',         'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z'],
                ['route' => 'agent.bookings.index', 'label' => 'My Bookings',       'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['route' => 'agent.requests.create','label' => 'Request Safari',    'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                ['route' => 'agent.requests.index', 'label' => 'My Requests',       'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
                ['route' => 'agent.responses.index','label' => 'Proposals',         'icon' => 'M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76', 'badge' => $pendingResponses],
                ['route' => 'agent.earnings',       'label' => 'My Earnings',       'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'agent.profile.edit',   'label' => 'My Profile',        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ] as $item)
                @php $active = $cur === $item['route']; @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                          {{ $active ? 'bg-brand-gold/10 text-brand-gold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-brand-gold' : 'text-white/40' }}"
                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if(!empty($item['badge']) && $item['badge'] > 0)
                    <span class="w-5 h-5 rounded-full bg-brand-gold text-brand-dark text-xs font-bold flex items-center justify-center">
                        {{ $item['badge'] }}
                    </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 px-4 py-3 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/40 truncate">{{ Auth::user()->agent?->company_name ?? 'Agent' }}</p>
                </div>
                <form method="POST" action="{{ route('agent.logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-white/40 hover:text-red-400 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="border-b border-black/10 bg-white px-6 h-16 flex items-center shrink-0 gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            <div class="flex-1">
                <h1 class="text-sm font-semibold text-gray-900">@yield('page-title', 'Agent Portal')</h1>
            </div>
            <a href="{{ route('agent.requests.create') }}"
               class="hidden sm:flex items-center gap-2 bg-brand-gold text-brand-dark text-xs font-bold px-4 py-2 rounded-lg hover:bg-brand-gold/90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Request Safari
            </a>
        </header>

        <main class="flex-1 overflow-auto">
            <div class="max-w-7xl mx-auto px-6 py-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
