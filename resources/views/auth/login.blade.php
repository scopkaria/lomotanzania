<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — {{ optional($siteSetting ?? null)->site_name ?: 'Lomo Tanzania Safari' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' },
                    },
                    fontFamily: {
                        body: ['Lato', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        .login-hero {
            background: linear-gradient(135deg, rgba(19,20,20,0.7) 0%, rgba(8,51,33,0.6) 100%),
                        url('{{ asset("storage/safaris/gallery/" . (optional(optional($siteSetting ?? null))->id ? \App\Models\SafariImage::inRandomOrder()->value("image_path") : "")) }}') center/cover no-repeat;
        }

        .role-glow {
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .input-focus:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px var(--accent-ring);
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: var(--accent-text);
        }
        .btn-accent:hover {
            background-color: var(--accent-hover);
        }

        .tab-active {
            background-color: var(--accent-color);
            color: var(--accent-text);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out both;
        }
        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
        .animate-delay-300 { animation-delay: 0.3s; }
        .animate-delay-400 { animation-delay: 0.4s; }
    </style>
</head>
<body class="font-body antialiased bg-brand-light" x-data="loginPage()" x-init="init()">

    <div class="min-h-screen flex">
        {{-- Left Hero Panel --}}
        <div class="hidden lg:flex lg:w-1/2 login-hero relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-dark/80 via-brand-green/60 to-brand-dark/70"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                {{-- Logo on hero --}}
                <div class="animate-fade-in-up">
                    @if(optional($siteSetting ?? null)->logo_path)
                        <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ optional($siteSetting)->site_name }}" class="h-14 w-auto object-contain brightness-0 invert">
                    @else
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-brand-gold rounded-lg flex items-center justify-center">
                                <span class="text-brand-dark font-bold text-xl">L</span>
                            </div>
                            <span class="text-white font-serif text-2xl font-semibold tracking-wide">Lomo Safari</span>
                        </div>
                    @endif
                </div>

                {{-- Hero Text --}}
                <div class="max-w-lg">
                    <h1 class="font-serif text-5xl font-bold text-white leading-tight animate-fade-in-up animate-delay-100">
                        Welcome to the<br>
                        <span class="text-brand-gold">Wild Side</span>
                    </h1>
                    <p class="mt-6 text-white/80 text-lg leading-relaxed animate-fade-in-up animate-delay-200">
                        Manage extraordinary safari experiences across Tanzania's most breathtaking landscapes.
                    </p>
                </div>

                {{-- Decorative bottom --}}
                <div class="animate-fade-in-up animate-delay-300">
                    <div class="flex items-center gap-4 text-white/60 text-sm">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-brand-gold/30 border-2 border-white/20 flex items-center justify-center text-xs text-white">🦁</div>
                            <div class="w-8 h-8 rounded-full bg-brand-green/30 border-2 border-white/20 flex items-center justify-center text-xs text-white">🐘</div>
                            <div class="w-8 h-8 rounded-full bg-brand-gold/30 border-2 border-white/20 flex items-center justify-center text-xs text-white">🦒</div>
                        </div>
                        <span>Tanzania's Premier Safari Platform</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Login Panel --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
            {{-- Background pattern --}}
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23131414&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <div class="w-full max-w-md relative z-10">
                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8 animate-fade-in-up">
                    @if(optional($siteSetting ?? null)->logo_path)
                        <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ optional($siteSetting)->site_name }}" class="h-12 w-auto object-contain mx-auto">
                    @else
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-10 h-10 bg-brand-gold rounded-lg flex items-center justify-center">
                                <span class="text-brand-dark font-bold text-lg">L</span>
                            </div>
                            <span class="text-brand-dark font-serif text-xl font-semibold">Lomo Safari</span>
                        </div>
                    @endif
                </div>

                {{-- Header --}}
                <div class="mb-8 animate-fade-in-up animate-delay-100">
                    <h2 class="font-serif text-3xl font-bold text-brand-dark">Sign In</h2>
                    <p class="mt-2 text-gray-500">Select your role and enter your credentials</p>
                </div>

                {{-- Role Selector Tabs --}}
                <div class="mb-8 animate-fade-in-up animate-delay-200">
                    <div class="grid grid-cols-4 gap-1 p-1 bg-gray-100 rounded-xl">
                        <template x-for="r in roles" :key="r.key">
                            <button
                                type="button"
                                @click="selectRole(r.key)"
                                :class="role === r.key ? 'tab-active shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-white/60'"
                                class="relative px-2 py-2.5 rounded-lg text-xs font-semibold tracking-wide transition-all duration-300 text-center"
                            >
                                <span class="block text-base mb-0.5" x-text="r.icon"></span>
                                <span x-text="r.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm animate-fade-in-up">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm animate-fade-in-up">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form :action="role === 'agent' ? '{{ route('agent.login.store') }}' : '{{ route('login') }}'" method="POST" class="space-y-5 animate-fade-in-up animate-delay-300">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input
                                id="email" name="email" type="email"
                                value="{{ old('email') }}"
                                required autofocus autocomplete="username"
                                class="input-focus block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white transition-all duration-200 outline-none"
                                placeholder="you@example.com"
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password" name="password"
                                :type="show ? 'text' : 'password'"
                                required autocomplete="current-password"
                                class="input-focus block w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white transition-all duration-200 outline-none"
                                placeholder="••••••••"
                            >
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-gray-300 transition-colors cursor-pointer"
                                :style="'color: var(--accent-color)'"
                            >
                            <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium transition-colors" :style="'color: var(--accent-color)'">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn-accent w-full py-3.5 rounded-xl font-semibold text-sm tracking-wide shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span x-text="'Sign in as ' + roles.find(r => r.key === role).label"></span>
                        </span>
                    </button>
                </form>

                {{-- Agent Register Link --}}
                <div x-show="role === 'agent'" x-cloak class="mt-6 text-center animate-fade-in-up">
                    <p class="text-sm text-gray-500">
                        Don't have an agent account?
                        <a href="{{ route('agent.register') }}" class="font-semibold transition-colors" :style="'color: var(--accent-color)'">
                            Register here
                        </a>
                    </p>
                </div>

                {{-- Footer --}}
                <div class="mt-10 text-center animate-fade-in-up animate-delay-400">
                    <p class="text-xs text-gray-400">
                        &copy; {{ date('Y') }} {{ optional($siteSetting ?? null)->site_name ?: 'Lomo Tanzania Safari' }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loginPage() {
            return {
                role: 'super_admin',
                roles: [
                    { key: 'super_admin', label: 'Super Admin', icon: '👑', color: '#FEBC11', hover: '#E5A90F', text: '#131414', ring: 'rgba(254,188,17,0.25)', glow: 'rgba(254,188,17,0.3)' },
                    { key: 'admin',       label: 'Admin',       icon: '🏢', color: '#083321', hover: '#0A4A30', text: '#FFFFFF', ring: 'rgba(8,51,33,0.25)', glow: 'rgba(8,51,33,0.3)' },
                    { key: 'worker',      label: 'Worker',      icon: '⚡', color: '#0891B2', hover: '#0E7490', text: '#FFFFFF', ring: 'rgba(8,145,178,0.25)', glow: 'rgba(8,145,178,0.3)' },
                    { key: 'agent',       label: 'Agent',       icon: '🌍', color: '#D97706', hover: '#B45309', text: '#FFFFFF', ring: 'rgba(217,119,6,0.25)', glow: 'rgba(217,119,6,0.3)' },
                ],

                init() {
                    this.applyTheme();
                    this.$watch('role', () => this.applyTheme());
                },

                selectRole(key) {
                    this.role = key;
                },

                applyTheme() {
                    const r = this.roles.find(r => r.key === this.role);
                    const root = document.documentElement;
                    root.style.setProperty('--accent-color', r.color);
                    root.style.setProperty('--accent-hover', r.hover);
                    root.style.setProperty('--accent-text', r.text);
                    root.style.setProperty('--accent-ring', r.ring);
                    root.style.setProperty('--accent-glow', r.glow);
                }
            }
        }
    </script>
</body>
</html>
