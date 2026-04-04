<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Title --}}
    <title>@yield('seo_title', ($seoTitle ?? $siteName ?? 'Lomo Tanzania Safari'))</title>

    {{-- SEO Meta --}}
    @php
        $currentPath = request()->decodedPath();
        $basePath = preg_replace('#^(en|fr|de|es)(/|$)#', '', $currentPath);
        $canonicalUrl = url('/' . app()->getLocale() . '/' . $basePath);
        $defaultDescription = optional($siteSetting ?? null)->meta_description ?? 'Discover unforgettable safari experiences in Tanzania. Custom luxury safaris, Great Migration tours, Kilimanjaro treks and more.';
        $pageDescription = $seoDescription ?? $defaultDescription;
        $pageKeywords = $seoKeywords ?? 'safari, tanzania, serengeti, kilimanjaro, wildlife, africa, luxury safari, great migration';
        $ogImage = $seoOgImage ?? (optional($siteSetting ?? null)->default_og_image ? asset('storage/' . $siteSetting->default_og_image) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1200&h=630&fit=crop&q=80');
    @endphp

    <meta name="description" content="{{ Str::limit(strip_tags($pageDescription), 160) }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Google Search Console Verification --}}
    @if(optional($siteSetting ?? null)->google_search_console)
        <meta name="google-site-verification" content="{{ $siteSetting->google_search_console }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('seo_title', ($seoTitle ?? $siteName ?? 'Lomo Tanzania Safari'))">
    <meta property="og:description" content="{{ Str::limit(strip_tags($pageDescription), 160) }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ $siteName ?? 'Lomo Tanzania Safari' }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    @foreach(['en','fr','de','es'] as $altLocale)
        @if($altLocale !== app()->getLocale())
            <meta property="og:locale:alternate" content="{{ $altLocale }}">
        @endif
    @endforeach

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', ($seoTitle ?? $siteName ?? 'Lomo Tanzania Safari'))">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($pageDescription), 160) }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Hreflang SEO tags --}}
    <link rel="alternate" hreflang="en" href="{{ url('/en/' . $basePath) }}">
    <link rel="alternate" hreflang="fr" href="{{ url('/fr/' . $basePath) }}">
    <link rel="alternate" hreflang="de" href="{{ url('/de/' . $basePath) }}">
    <link rel="alternate" hreflang="es" href="{{ url('/es/' . $basePath) }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/en/' . $basePath) }}">

    {{-- JSON-LD Structured Data --}}
    @stack('jsonld')

    {{-- Google Analytics --}}
    @if(optional($siteSetting ?? null)->google_analytics_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSetting->google_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSetting->google_analytics_id }}');
    </script>
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            green: '#083321',
                            gold: '#FEBC11',
                            dark: '#131414',
                            light: '#F9F7F3',
                        }
                    },
                    fontFamily: {
                        heading: ['"Playfair Display"', 'serif'],
                        body: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @stack('styles')

    {{-- Global typography + Nav animations --}}
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            letter-spacing: 0.025em;
        }

        /* ── NAV UNDERLINE HOVER ── */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #FEBC11;
            transition: width 320ms cubic-bezier(0.22, 1, 0.36, 1), left 320ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        .nav-link:hover::after,
        .nav-item:hover > .nav-link::after,
        .nav-link.active::after {
            width: 100%;
            left: 0;
        }

        /* ── MEGA MENU: top→down reveal ── */
        .mega-panel {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 280ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 280ms cubic-bezier(0.22, 1, 0.36, 1),
                        visibility 280ms;
            pointer-events: none;
        }
        .nav-item:hover > .mega-panel {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        .nav-item { position: static; }

        /* ── MOBILE OFF-CANVAS ── */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 420ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .mobile-menu.open {
            transform: translateX(0);
        }
        .mobile-backdrop {
            opacity: 0;
            transition: opacity 350ms ease;
            pointer-events: none;
        }
        .mobile-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Mobile nav link hover shift */
        .mob-link {
            transition: color 200ms ease, transform 200ms ease;
        }
        .mob-link:hover {
            color: #FEBC11;
            transform: translateX(3px);
        }

        /* Accordion panel */
        .accordion-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 350ms cubic-bezier(0.4, 0, 0.2, 1), opacity 250ms ease;
            opacity: 0;
        }
        .accordion-panel.open {
            opacity: 1;
        }

        /* Hamburger animation */
        .ham-active .ham-top    { transform: rotate(45deg) translate(5px, 5px); }
        .ham-active .ham-mid    { opacity: 0; transform: scaleX(0); }
        .ham-active .ham-bot    { transform: rotate(-45deg) translate(5px, -5px); }

        /* Hero fade-in-up animation */
        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-animate {
            opacity: 0;
            animation: heroFadeUp 800ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .hero-delay-1 { animation-delay: 200ms; }
        .hero-delay-2 { animation-delay: 500ms; }
        .hero-delay-3 { animation-delay: 800ms; }
        .hero-delay-4 { animation-delay: 1050ms; }

        /* Scroll-reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1), transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-brand-light text-brand-dark">

    @php
        $currentLocale = app()->getLocale();
        $switchBasePath = preg_replace('#^(en|fr|de|es)(/|$)#', '', request()->decodedPath());
        $switcherLocales = [
            'en' => ['flag' => '🇬🇧', 'label' => 'English'],
            'fr' => ['flag' => '🇫🇷', 'label' => 'Français'],
            'de' => ['flag' => '🇩🇪', 'label' => 'Deutsch'],
            'es' => ['flag' => '🇪🇸', 'label' => 'Español'],
        ];
    @endphp

    {{-- ========== NAVBAR ========== --}}
    <div x-data="{ mobileOpen: false }" x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : ''" @keydown.escape.window="mobileOpen = false">
    <header class="bg-white sticky top-0 z-50 border-b border-gray-100">
        <nav class="w-full px-6 lg:px-10 flex items-center justify-between h-[80px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="h-11 w-auto object-contain">
                @else
                    <div class="flex flex-col justify-center">
                        <span class="font-heading text-lg font-bold tracking-[0.15em] text-brand-green whitespace-nowrap">{{ strtoupper($siteName ?? 'LOMO TANZANIA SAFARI') }}</span>
                        <span class="text-[9px] tracking-[0.2em] text-brand-gold/90 uppercase whitespace-nowrap">{{ $siteTagline ?? 'Less On Ourselves, More On Others' }}</span>
                    </div>
                @endif
            </a>

            {{-- ──────── DESKTOP NAV (center) ──────── --}}
            <div class="hidden xl:flex items-center justify-center flex-1 gap-0.5">

                {{-- Home --}}
                <a href="{{ route('home') }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.home') }}</a>

                {{-- DESTINATIONS mega --}}
                <div class="nav-item flex items-center">
                    <button class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition inline-flex items-center gap-1">
                        {{ __('messages.destinations') }}
                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="mega-panel absolute top-full left-0 right-0 pt-0">
                        <div class="bg-white border-t border-gray-100 shadow-[0_24px_60px_-20px_rgba(0,0,0,0.12)]">
                            <div class="max-w-7xl mx-auto px-6 py-10">
                                <div class="grid grid-cols-12 gap-10">
                                    {{-- Countries column --}}
                                    <div class="col-span-3">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.countries') }}</p>
                                        <ul class="space-y-3">
                                            @forelse($navCountries ?? [] as $country)
                                                <li>
                                                    <a href="{{ route('countries.show', ['slug' => $country->slug]) }}" class="group flex items-center gap-3 text-sm text-brand-dark/70 hover:text-brand-green transition">
                                                        @if($country->featured_image)
                                                            <img src="{{ asset('storage/' . $country->featured_image) }}" alt="{{ $country->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent group-hover:ring-brand-gold transition">
                                                        @else
                                                            <span class="w-8 h-8 rounded-full bg-brand-green/10 flex items-center justify-center text-[10px] font-bold text-brand-green">{{ strtoupper(substr($country->name, 0, 2)) }}</span>
                                                        @endif
                                                        <span class="font-medium">{{ $country->name }}</span>
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="text-xs text-gray-400">{{ __('messages.coming_soon') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    {{-- Destinations grid --}}
                                    <div class="col-span-6">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.destinations') }}</p>
                                        <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                                            @forelse($navCountries ?? [] as $country)
                                                @foreach($country->destinations->take(6) as $dest)
                                                    <a href="{{ route('destinations.show', $dest->slug) }}" class="group flex items-center gap-2.5 py-1.5 text-sm text-brand-dark/65 hover:text-brand-green transition">
                                                        <span class="w-1 h-1 rounded-full bg-brand-gold/60 group-hover:bg-brand-green transition shrink-0"></span>
                                                        {{ $dest->translated('name') }}
                                                    </a>
                                                @endforeach
                                            @empty
                                                <p class="text-xs text-gray-400 col-span-2">{{ __('messages.coming_soon') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    {{-- Feature image --}}
                                    <div class="col-span-3 relative rounded-xl overflow-hidden min-h-[200px]">
                                        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=280&fit=crop&q=80" alt="Tanzania landscape" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/20 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-5">
                                            <p class="text-[10px] uppercase tracking-[0.2em] text-brand-gold font-semibold mb-1">{{ __('messages.explore') }}</p>
                                            <p class="text-sm text-white/80 leading-relaxed">{{ __('messages.mega_dest_text') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SAFARIS mega --}}
                <div class="nav-item flex items-center">
                    <button class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition inline-flex items-center gap-1">
                        {{ __('messages.safari') }}
                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="mega-panel absolute top-full left-0 right-0 pt-0">
                        <div class="bg-white border-t border-gray-100 shadow-[0_24px_60px_-20px_rgba(0,0,0,0.12)]">
                            <div class="max-w-7xl mx-auto px-6 py-10">
                                <div class="grid grid-cols-12 gap-10">
                                    {{-- Categories --}}
                                    <div class="col-span-3">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.categories') }}</p>
                                        <ul class="space-y-3">
                                            @forelse($navCategories ?? [] as $cat)
                                                <li>
                                                    <a href="{{ route('categories.show', ['slug' => $cat->slug]) }}" class="flex items-center gap-3 text-sm text-brand-dark/70 hover:text-brand-green transition group">
                                                        <span class="w-8 h-8 rounded-lg bg-brand-green/5 group-hover:bg-brand-green/10 flex items-center justify-center transition">
                                                            <svg class="w-4 h-4 text-brand-green/60" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg>
                                                        </span>
                                                        <span class="font-medium">{{ $cat->translated('name') }}</span>
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="text-xs text-gray-400">{{ __('messages.coming_soon') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    {{-- Tour Types --}}
                                    <div class="col-span-6">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.safari_types') }}</p>
                                        <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                                            @forelse($navTourTypes ?? [] as $type)
                                                <a href="{{ route('tour-types.show', ['slug' => $type->slug]) }}" class="group flex items-center gap-2.5 py-1.5 text-sm text-brand-dark/65 hover:text-brand-green transition">
                                                    <span class="w-1 h-1 rounded-full bg-brand-gold/60 group-hover:bg-brand-green transition shrink-0"></span>
                                                    {{ $type->translated('name') }}
                                                </a>
                                            @empty
                                                <p class="text-xs text-gray-400 col-span-2">{{ __('messages.coming_soon') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    {{-- Feature image --}}
                                    <div class="col-span-3 relative rounded-xl overflow-hidden min-h-[200px]">
                                        <img src="https://images.unsplash.com/photo-1547970810-dc1eac37d174?w=400&h=280&fit=crop&q=80" alt="Safari wildlife" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/20 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-5">
                                            <p class="text-[10px] uppercase tracking-[0.2em] text-brand-gold font-semibold mb-1">{{ __('messages.safari') }}</p>
                                            <p class="text-sm text-white/80 leading-relaxed">{{ __('messages.mega_safari_text') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trekking --}}
                <a href="{{ route('safaris.index') }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.trekking') }}</a>

                {{-- Experiences --}}
                <a href="{{ route('safaris.index') }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.experiences') }}</a>

                {{-- Blog --}}
                <a href="{{ route('blog.index') }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.blog') }}</a>

                {{-- About --}}
                <a href="{{ route('page.show', ['slug' => 'about-us']) }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.about') }}</a>

                {{-- Contact --}}
                <a href="{{ route('custom-tour') }}" class="nav-link px-3 py-6 text-[12px] font-semibold uppercase tracking-[0.14em] text-brand-dark/80 hover:text-brand-green transition">{{ __('messages.contact') }}</a>
            </div>

            {{-- ──────── RIGHT SIDE: LANG + CTA ──────── --}}
            <div class="hidden xl:flex items-center shrink-0">

                {{-- Language Switcher Dropdown --}}
                <div class="relative border-l border-gray-200 pl-3" x-data="{ langOpen: false }" @click.away="langOpen = false">
                    <button @click="langOpen = !langOpen" class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-brand-dark/70 hover:text-brand-green transition py-1 px-1.5 rounded" :class="langOpen && 'text-brand-green'">
                        <span>{{ $switcherLocales[$currentLocale]['flag'] }}</span>
                        <span>{{ strtoupper($currentLocale) }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="langOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="langOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute right-0 top-full mt-1 bg-white rounded-md shadow-lg border border-gray-100 py-1 min-w-[150px] z-50" style="display: none;">
                        @foreach($switcherLocales as $code => $locale)
                            <a href="{{ url('/' . $code . '/' . $switchBasePath) }}"
                               class="flex items-center gap-2.5 px-3.5 py-2 text-[12px] transition hover:bg-brand-light {{ $currentLocale === $code ? 'text-brand-green font-semibold' : 'text-brand-dark/70' }}">
                                <span>{{ $locale['flag'] }}</span>
                                <span>{{ $locale['label'] }}</span>
                                @if($currentLocale === $code)
                                    <svg class="w-3.5 h-3.5 ml-auto text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- CTA Button --}}
                <a href="{{ route('plan-safari', ['locale' => $currentLocale]) }}"
                   class="ml-4 px-6 py-2.5 bg-brand-gold text-brand-dark text-[11px] font-bold uppercase tracking-[0.14em] rounded-sm whitespace-nowrap hover:bg-brand-dark hover:text-white transition-all duration-300 shrink-0">
                    {{ __('messages.start_planning') }}
                </a>
            </div>

            {{-- ──────── MOBILE HAMBURGER ──────── --}}
            <button @click="mobileOpen = !mobileOpen" class="xl:hidden relative z-50 w-10 h-10 flex flex-col justify-center items-center shrink-0" :class="mobileOpen && 'ham-active'" aria-label="Toggle menu">
                <span class="ham-top block w-6 h-[2px] bg-brand-dark rounded-full transition-all duration-300"></span>
                <span class="ham-mid block w-6 h-[2px] bg-brand-dark rounded-full mt-[5px] transition-all duration-300"></span>
                <span class="ham-bot block w-6 h-[2px] bg-brand-dark rounded-full mt-[5px] transition-all duration-300"></span>
            </button>
        </nav>
    </header>

    {{-- ========== MOBILE BACKDROP ========== --}}
    <div class="mobile-backdrop fixed inset-0 z-40 bg-black/50 xl:hidden" :class="mobileOpen && 'open'" @click="mobileOpen = false"></div>

    {{-- ========== MOBILE OFF-CANVAS MENU ========== --}}
    <div class="mobile-menu fixed top-0 right-0 bottom-0 w-[320px] max-w-[85vw] z-50 bg-white xl:hidden overflow-y-auto"
         :class="mobileOpen && 'open'"
         x-data="{ openSection: null }">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="h-8 w-auto object-contain">
                @else
                    <span class="font-heading text-sm font-bold tracking-[0.12em] text-brand-green">{{ strtoupper($siteName ?? 'LOMO SAFARI') }}</span>
                @endif
            </a>
            <button @click="mobileOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition" aria-label="Close menu">
                <svg class="w-4 h-4 text-brand-dark/60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Nav links --}}
        <div class="px-6 py-4 space-y-0">

            <a href="{{ route('home') }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.home') }}</a>

            {{-- Destinations accordion --}}
            <div class="border-b border-gray-50">
                <button @click="openSection = openSection === 'dest' ? null : 'dest'" class="mob-link w-full flex justify-between items-center py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80">
                    {{ __('messages.destinations') }}
                    <svg class="w-3.5 h-3.5 text-brand-dark/30 transition-transform duration-300" :class="openSection === 'dest' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openSection === 'dest'" x-collapse x-cloak>
                    <div class="pb-4 pl-3 space-y-1">
                        @forelse($navCountries ?? [] as $country)
                            <p class="text-[10px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-3 mb-2">{{ $country->name }}</p>
                            @foreach($country->destinations->take(5) as $dest)
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="block py-1.5 text-[12px] text-brand-dark/55 hover:text-brand-green transition pl-2">{{ $dest->translated('name') }}</a>
                            @endforeach
                        @empty
                            <p class="text-xs text-gray-400 py-2">{{ __('messages.coming_soon') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Safaris accordion --}}
            <div class="border-b border-gray-50">
                <button @click="openSection = openSection === 'safari' ? null : 'safari'" class="mob-link w-full flex justify-between items-center py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80">
                    {{ __('messages.safari') }}
                    <svg class="w-3.5 h-3.5 text-brand-dark/30 transition-transform duration-300" :class="openSection === 'safari' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openSection === 'safari'" x-collapse x-cloak>
                    <div class="pb-4 pl-3 space-y-1">
                        @if(($navCategories ?? collect())->isNotEmpty())
                            <p class="text-[10px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-2 mb-2">{{ __('messages.categories') }}</p>
                            @foreach($navCategories as $cat)
                                <a href="{{ route('categories.show', ['slug' => $cat->slug]) }}" class="block py-1.5 text-[12px] text-brand-dark/55 hover:text-brand-green transition pl-2">{{ $cat->translated('name') }}</a>
                            @endforeach
                        @endif
                        @if(($navTourTypes ?? collect())->isNotEmpty())
                            <p class="text-[10px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-3 mb-2">{{ __('messages.safari_types') }}</p>
                            @foreach($navTourTypes as $type)
                                <a href="{{ route('tour-types.show', ['slug' => $type->slug]) }}" class="block py-1.5 text-[12px] text-brand-dark/55 hover:text-brand-green transition pl-2">{{ $type->translated('name') }}</a>
                            @endforeach
                        @endif
                        @if(($navCategories ?? collect())->isEmpty() && ($navTourTypes ?? collect())->isEmpty())
                            <p class="text-xs text-gray-400 py-2">{{ __('messages.coming_soon') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('safaris.index') }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.trekking') }}</a>
            <a href="{{ route('safaris.index') }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.experiences') }}</a>
            <a href="{{ route('blog.index') }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.blog') }}</a>
            <a href="{{ route('page.show', ['slug' => 'about-us']) }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.about') }}</a>
            <a href="{{ route('custom-tour') }}" class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark/80 border-b border-gray-50">{{ __('messages.contact') }}</a>
        </div>

        {{-- Language + CTA --}}
        <div class="px-6 pt-2 pb-8 space-y-5">

            {{-- Language Switcher --}}
            <div class="space-y-1">
                @foreach($switcherLocales as $code => $locale)
                    <a href="{{ url('/' . $code . '/' . $switchBasePath) }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-sm text-[12px] transition duration-200
                              {{ $currentLocale === $code ? 'bg-brand-green/10 text-brand-green font-semibold' : 'text-brand-dark/60 hover:bg-gray-50' }}">
                        <span>{{ $locale['flag'] }}</span>
                        <span>{{ $locale['label'] }}</span>
                        @if($currentLocale === $code)
                            <svg class="w-3.5 h-3.5 ml-auto text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <a href="{{ route('plan-safari', ['locale' => $currentLocale]) }}"
               class="block text-center px-5 py-3 bg-brand-gold text-brand-dark text-[12px] font-bold uppercase tracking-[0.12em] rounded-sm hover:bg-brand-dark hover:text-white transition-all duration-300">
                {{ __('messages.start_planning') }}
            </a>

            {{-- WhatsApp --}}
            <a href="#" class="flex items-center justify-center gap-2 py-2.5 rounded-sm bg-[#25D366]/10 hover:bg-[#25D366]/15 transition duration-200">
                <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.111.547 4.099 1.504 5.832L0 24l6.335-1.652A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82c-1.89 0-3.69-.508-5.27-1.447l-.378-.224-3.918 1.023 1.047-3.823-.247-.393A9.782 9.782 0 012.18 12c0-5.414 4.406-9.82 9.82-9.82S21.82 6.586 21.82 12s-4.406 9.82-9.82 9.82z"/></svg>
                <span class="text-[12px] font-semibold text-[#25D366]">{{ __('messages.chat_whatsapp') }}</span>
            </a>
        </div>
    </div>
    </div>{{-- end x-data wrapper --}}

    {{-- Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="bg-brand-dark text-white/60">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-16">

            {{-- Desktop: multi-column grid / Mobile: stacked --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-10">

                {{-- Brand (always visible) --}}
                <div class="md:col-span-1">
                    @if(optional($siteSetting ?? null)->logo_path)
                        <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="h-12 w-auto object-contain mb-3">
                    @else
                        <span class="font-heading text-xl font-bold tracking-widest text-white block">{{ strtoupper($siteName ?? 'LOMO TANZANIA SAFARI') }}</span>
                    @endif
                    <p class="text-xs tracking-wider uppercase text-brand-gold/70 mt-2">{{ $siteTagline ?? 'Less On Ourselves, More On Others' }}</p>
                    <p class="text-sm text-white/40 mt-4 leading-relaxed">{{ __('messages.brand_description') }}</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.quick_links') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.destinations') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.safari') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.trekking') }}</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.blog') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.quick_links') }}
                            <svg class="accordion-icon w-4 h-4 text-white/30 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li><a href="{{ route('home') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.home') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.destinations') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.safari') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.trekking') }}</a></li>
                                <li><a href="{{ route('blog.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.blog') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Experiences --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.experiences_footer') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.day_trips') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.hot_air_balloon') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.cultural_immersion') }}</a></li>
                        <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.photography_safari') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.experiences_footer') }}
                            <svg class="accordion-icon w-4 h-4 text-white/30 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.day_trips') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.hot_air_balloon') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.cultural_immersion') }}</a></li>
                                <li><a href="{{ route('safaris.index') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.photography_safari') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Get In Touch --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.get_in_touch') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        <li class="text-white/50">Arusha, Tanzania</li>
                        <li><a href="mailto:info@lomotanzania.com" class="text-white/50 hover:text-brand-gold transition duration-200">info@lomotanzania.com</a></li>
                        <li><a href="{{ route('custom-tour') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.contact_page') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.get_in_touch') }}
                            <svg class="accordion-icon w-4 h-4 text-white/30 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li class="text-white/50">Arusha, Tanzania</li>
                                <li><a href="mailto:info@lomotanzania.com" class="text-white/50 hover:text-brand-gold transition duration-200">info@lomotanzania.com</a></li>
                                <li><a href="{{ route('custom-tour') }}" class="text-white/50 hover:text-brand-gold transition duration-200">{{ __('messages.contact_page') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-white/10 mt-12 pt-8 text-center space-y-1">
                <p class="text-xs text-white/30">&copy; {{ date('Y') }} {{ $siteName ?? 'Lomo Tanzania Safari' }}. {{ __('messages.all_rights') }}</p>
                <p class="text-[10px] text-white/20">Powered by Scop Kariah</p>
            </div>
        </div>
    </footer>

    {{-- ========== SCRIPTS ========== --}}
    <script>
        // Scroll-reveal observer
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // Footer accordion (vanilla JS — footer is outside Alpine scope)
        document.querySelectorAll('.footer-accordion-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const panel = btn.nextElementSibling;
                const icon = btn.querySelector('.accordion-icon');
                const isOpen = panel.classList.contains('open');

                if (isOpen) {
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    requestAnimationFrame(() => { panel.style.maxHeight = '0'; });
                    panel.classList.remove('open');
                    icon.style.transform = '';
                } else {
                    panel.classList.add('open');
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                    panel.addEventListener('transitionend', function handler() {
                        if (panel.classList.contains('open')) panel.style.maxHeight = 'none';
                        panel.removeEventListener('transitionend', handler);
                    });
                }
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
