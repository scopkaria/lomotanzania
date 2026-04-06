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
        $favicon = optional($siteSetting ?? null)->favicon_path ? asset('storage/' . $siteSetting->favicon_path) : asset('favicon.png');
        $headerColor = optional($siteSetting ?? null)->header_color ?: '#083321';
    @endphp

    <meta name="description" content="{{ Str::limit(strip_tags($pageDescription), 160) }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="theme-color" content="{{ $headerColor }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" type="image/png" href="{{ $favicon }}">
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    {{-- Search engine verification --}}
    @if(optional($siteSetting ?? null)->google_search_console)
        <meta name="google-site-verification" content="{{ $siteSetting->google_search_console }}">
    @endif
    @if(optional($siteSetting ?? null)->bing_webmaster_code)
        <meta name="msvalidate.01" content="{{ $siteSetting->bing_webmaster_code }}">
    @endif
    @if(optional($siteSetting ?? null)->yandex_verification_code)
        <meta name="yandex-verification" content="{{ $siteSetting->yandex_verification_code }}">
    @endif
    @if(optional($siteSetting ?? null)->baidu_verification_code)
        <meta name="baidu-site-verification" content="{{ $siteSetting->baidu_verification_code }}">
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

        /* â”€â”€ NAV UNDERLINE HOVER â”€â”€ */
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

        /* â”€â”€ MEGA MENU: topâ†’down reveal â”€â”€ */
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

        /* — MOBILE OFF-CANVAS — */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 420ms cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
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
            'en' => ['flag' => "\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7", 'label' => 'English'],
            'fr' => ['flag' => "\xF0\x9F\x87\xAB\xF0\x9F\x87\xB7", 'label' => "Fran\xC3\xA7ais"],
            'de' => ['flag' => "\xF0\x9F\x87\xA9\xF0\x9F\x87\xAA", 'label' => 'Deutsch'],
            'es' => ['flag' => "\xF0\x9F\x87\xAA\xF0\x9F\x87\xB8", 'label' => "Espa\xC3\xB1ol"],
        ];
        $headerColor = optional($siteSetting ?? null)->header_color ?: '#083321';
        $logoWidth = max(110, min((int) (optional($siteSetting ?? null)->logo_width ?: 176), 280));
        $menuItems = ($navMenuItems ?? collect())->filter(fn ($item) => $item->is_enabled ?? true)->values();

        if ($menuItems->isEmpty()) {
            $menuItems = collect([
                ['label' => __('messages.home'), 'slug' => 'home', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.destinations'), 'slug' => 'destinations', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.safari'), 'slug' => 'safaris', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.experiences'), 'slug' => 'experiences', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.blog'), 'slug' => 'blog', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.about'), 'slug' => 'about', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.contact'), 'slug' => 'contact', 'url' => null, 'open_in_new_tab' => false],
            ])->map(fn ($item) => (object) $item);
        }

        $resolveMenuUrl = function ($item) use ($currentLocale) {
            return match ($item->slug ?? null) {
                'home' => route('home', ['locale' => $currentLocale]),
                'destinations' => route('destinations.index', ['locale' => $currentLocale]),
                'safaris' => route('safaris.index', ['locale' => $currentLocale]),
                'experiences' => route('experiences.index', ['locale' => $currentLocale]),
                'blog' => route('blog.index', ['locale' => $currentLocale]),
                'about' => route('page.show', ['locale' => $currentLocale, 'slug' => 'about-us']),
                'contact' => route('contact', ['locale' => $currentLocale]),
                default => filled($item->url ?? null)
                    ? (Str::startsWith($item->url, ['http://', 'https://', 'mailto:', 'tel:']) ? $item->url : url($item->url))
                    : '#',
            };
        };

        $navCountryCard = $navTanzania ?? (($navCountries ?? collect())->first());
        $navDestinationItems = ($navTanzaniaDestinations ?? collect())->take(6);
        $navFeatureItem = $navFeaturedDestination ?? $navDestinationItems->first();
    @endphp

    {{-- ========== NAVBAR ========== --}}
    <div x-data="{ mobileOpen: false, langOpen: false, mobileLangOpen: false }" x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : ''" @keydown.escape.window="mobileOpen = false">

    {{-- ========== MOBILE TOP STRIP ========== --}}
    <div class="text-white text-[11px] lg:hidden relative z-[60]" style="background-color: {{ $headerColor }};">
        <div class="w-full px-4 flex items-center justify-between h-[34px]">
            {{-- Left: Phone --}}
            <div class="flex items-center">
                @if(optional($siteSetting ?? null)->phone_number)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->phone_number) }}" class="flex items-center gap-1.5 hover:text-brand-gold transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    <span>{{ $siteSetting->phone_number }}</span>
                </a>
                @endif
            </div>
            {{-- Right: Language Dropdown --}}
            <div class="relative">
                <button @click="mobileLangOpen = !mobileLangOpen" @click.outside="mobileLangOpen = false" class="flex items-center gap-1 hover:text-brand-gold transition">
                    <span>{{ $switcherLocales[$currentLocale]['flag'] }}</span>
                    <span class="uppercase font-semibold">{{ $currentLocale }}</span>
                    <svg class="w-3 h-3 transition-transform" :class="mobileLangOpen && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="mobileLangOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-1 w-36 bg-white rounded shadow-lg border border-gray-100 py-1 z-[70]">
                    @foreach($switcherLocales as $code => $locale)
                        <a href="{{ url('/' . $code . '/' . $switchBasePath) }}"
                           class="flex items-center gap-2 px-3 py-1.5 text-[11px] transition {{ $currentLocale === $code ? 'bg-brand-green/10 text-brand-green font-semibold' : 'text-brand-dark hover:bg-gray-50' }}">
                            <span>{{ $locale['flag'] }}</span>
                            <span>{{ $locale['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TOP RIBBON BAR ========== --}}
    <div class="hidden lg:block relative z-[60] text-[12px] text-white" style="background-color: {{ $headerColor }};">
        <div class="w-full px-6 lg:px-10 flex items-center justify-between h-[38px]">
            {{-- Left: Phone + WhatsApp --}}
            <div class="flex items-center gap-5">
                @if(optional($siteSetting ?? null)->phone_number)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->phone_number) }}" class="flex items-center gap-1.5 hover:text-brand-gold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    <span>{{ $siteSetting->phone_number }}</span>
                </a>
                @endif
                @if(optional($siteSetting ?? null)->whatsapp_number)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSetting->whatsapp_number) }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:text-brand-gold transition">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span>WhatsApp</span>
                </a>
                @endif
            </div>
            {{-- Right: Language Switcher + Find Tour --}}
            <div class="flex items-center gap-4">
                {{-- Language Switcher --}}
                <div class="relative" @click.away="langOpen = false">
                    <button @click="langOpen = !langOpen" class="flex items-center gap-1.5 text-[12px] font-medium tracking-wide text-white/90 hover:text-brand-gold transition py-1 px-1.5 rounded" :class="langOpen && 'text-brand-gold'">
                        <span class="text-sm">{{ $switcherLocales[$currentLocale]['flag'] }}</span>
                        <span>{{ strtoupper($currentLocale) }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="langOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="langOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute right-0 top-full mt-1 bg-white rounded-md shadow-lg border border-gray-100 py-1 min-w-[150px] z-50" style="display: none;">
                        @foreach($switcherLocales as $code => $locale)
                            <a href="{{ url('/' . $code . '/' . $switchBasePath) }}"
                               class="flex items-center gap-2.5 px-3.5 py-2 text-[12px] transition hover:bg-brand-light {{ $currentLocale === $code ? 'text-brand-green font-semibold' : 'text-brand-dark' }}">
                                <span>{{ $locale['flag'] }}</span>
                                <span>{{ $locale['label'] }}</span>
                                @if($currentLocale === $code)
                                    <svg class="w-3.5 h-3.5 ml-auto text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <span class="w-px h-4 bg-white/20"></span>

                {{-- Find Tour --}}
                <a href="{{ route('safaris.index') }}" class="flex items-center gap-1.5 text-[12px] font-semibold uppercase tracking-wide text-brand-gold hover:text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    FIND TOUR
                </a>
            </div>
        </div>
    </div>

    <header class="bg-white sticky top-0 z-50 border-b border-gray-100">
        <nav class="w-full px-6 lg:px-10 flex items-center justify-between h-[80px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="max-h-14 object-contain" style="width: {{ $logoWidth }}px; height: auto;">
                @else
                    <div class="flex flex-col justify-center">
                        <span class="font-heading text-lg font-bold tracking-[0.15em] text-brand-green whitespace-nowrap">{{ strtoupper($siteName ?? 'LOMO TANZANIA SAFARI') }}</span>
                        <span class="text-[9px] tracking-[0.2em] text-brand-gold/90 uppercase whitespace-nowrap">{{ $siteTagline ?? 'Less On Ourselves, More On Others' }}</span>
                    </div>
                @endif
            </a>

            {{-- â”€â”€â”€â”€â”€â”€â”€â”€ DESKTOP NAV (center) â”€â”€â”€â”€â”€â”€â”€â”€ --}}
            <div class="hidden xl:flex items-center justify-end flex-1 gap-0.5">

                @foreach($menuItems as $menuItem)
                    @php
                        $menuSlug = $menuItem->slug ?? null;
                        $menuUrl = $resolveMenuUrl($menuItem);
                    @endphp

                    @if($menuSlug === 'destinations')
                        <div class="nav-item flex items-center">
                            <a href="{{ $menuUrl }}" class="nav-link px-3 py-6 text-[14px] font-semibold uppercase tracking-[0.12em] text-brand-dark hover:text-brand-green transition inline-flex items-center gap-1">
                                {{ $menuItem->label }}
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <div class="mega-panel absolute top-full left-0 right-0 pt-0">
                                <div class="border-t border-gray-100 bg-white shadow-[0_24px_60px_-20px_rgba(0,0,0,0.12)]">
                                    <div class="mx-auto max-w-7xl px-6 py-10">
                                        <div class="grid grid-cols-12 gap-8">
                                            <div class="col-span-4">
                                                <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold">Country</p>
                                                @php
                                                    $countryImage = $navCountryCard?->featured_image
                                                        ? asset('storage/' . $navCountryCard->featured_image)
                                                        : ($navFeatureItem?->featured_image
                                                            ? asset('storage/' . $navFeatureItem->featured_image)
                                                            : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=520&fit=crop&q=80');
                                                @endphp
                                                <a href="{{ route('destinations.tanzania', ['locale' => $currentLocale]) }}" class="group block overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md">
                                                    <div class="aspect-[4/3] overflow-hidden">
                                                        <img src="{{ $countryImage }}" alt="Tanzania" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                                    </div>
                                                    <div class="p-4">
                                                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-gold">Featured country</p>
                                                        <p class="mt-1 font-heading text-2xl font-bold text-brand-dark">🇹🇿 Tanzania</p>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-span-4">
                                                <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold">Tanzania destinations</p>
                                                <div class="space-y-2">
                                                    @forelse($navDestinationItems as $dest)
                                                        <a href="{{ route('destinations.show', ['locale' => $currentLocale, 'slug' => $dest->slug]) }}" class="group flex items-center gap-3 rounded-xl border border-gray-100 px-3 py-3 transition hover:border-brand-green/20 hover:bg-brand-light">
                                                            @if($dest->featured_image)
                                                                <img src="{{ asset('storage/' . $dest->featured_image) }}" alt="{{ $dest->translated('name') }}" class="h-12 w-12 flex-shrink-0 object-cover">
                                                            @else
                                                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center bg-brand-green/10 text-xs font-bold text-brand-green">{{ strtoupper(substr($dest->translated('name'), 0, 2)) }}</div>
                                                            @endif
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-semibold text-brand-dark transition group-hover:text-brand-green">{{ $dest->translated('name') }}</p>
                                                                <p class="line-clamp-1 text-xs text-gray-500">{{ Str::limit(strip_tags($dest->translated('description')), 70) }}</p>
                                                            </div>
                                                            <span class="text-brand-green transition group-hover:translate-x-1">→</span>
                                                        </a>
                                                    @empty
                                                        <p class="text-xs text-gray-400">{{ __('messages.coming_soon') }}</p>
                                                    @endforelse
                                                </div>
                                            </div>

                                            <div class="col-span-4">
                                                <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold">Featured escape</p>
                                                @php
                                                    $featureImage = $navFeatureItem?->featured_image
                                                        ? asset('storage/' . $navFeatureItem->featured_image)
                                                        : ($navFeaturedSafari?->featured_image
                                                            ? asset('storage/' . $navFeaturedSafari->featured_image)
                                                            : 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=700&h=520&fit=crop&q=80');
                                                    $featureLink = $navFeatureItem
                                                        ? route('destinations.show', ['locale' => $currentLocale, 'slug' => $navFeatureItem->slug])
                                                        : route('destinations.tanzania', ['locale' => $currentLocale]);
                                                @endphp
                                                <a href="{{ $featureLink }}" class="group relative block min-h-[260px] overflow-hidden rounded-2xl">
                                                    <img src="{{ $featureImage }}" alt="Featured Tanzania destination" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/85 via-brand-dark/20 to-transparent"></div>
                                                    <div class="absolute bottom-0 left-0 right-0 p-5">
                                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-gold">Explore</p>
                                                        <p class="mt-1 font-heading text-2xl font-bold text-white">{{ $navFeatureItem?->translated('name') ?? 'Tanzania Highlights' }}</p>
                                                        <p class="mt-2 text-sm text-white/80">Fresh inspiration pulled dynamically from your live tours and destinations.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($menuSlug === 'safaris')
                        <div class="nav-item flex items-center">
                            <a href="{{ $menuUrl }}" class="nav-link px-3 py-6 text-[14px] font-semibold uppercase tracking-[0.12em] text-brand-dark hover:text-brand-green transition inline-flex items-center gap-1">
                                {{ $menuItem->label }}
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <div class="mega-panel absolute top-full left-0 right-0 pt-0">
                                <div class="bg-white border-t border-gray-100 shadow-[0_24px_60px_-20px_rgba(0,0,0,0.12)]">
                                    <div class="max-w-7xl mx-auto px-6 py-10">
                                        <div class="grid grid-cols-12 gap-8">
                                            <div class="col-span-3">
                                                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.categories') }}</p>
                                                <ul class="space-y-3">
                                                    @forelse($navCategories ?? [] as $cat)
                                                        <li>
                                                            <a href="{{ route('categories.show', ['locale' => $currentLocale, 'slug' => $cat->slug]) }}" class="flex items-center gap-3 text-sm text-brand-dark hover:text-brand-green transition group">
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
                                            <div class="col-span-4">
                                                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.safari_types') }}</p>
                                                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                                    @forelse($navTourTypes ?? [] as $type)
                                                        <a href="{{ route('tour-types.show', ['locale' => $currentLocale, 'slug' => $type->slug]) }}" class="group flex items-center gap-2.5 py-1.5 text-sm text-brand-dark/90 hover:text-brand-green transition">
                                                            <span class="w-1 h-1 rounded-full bg-brand-gold/60 group-hover:bg-brand-green transition shrink-0"></span>
                                                            {{ $type->translated('name') }}
                                                        </a>
                                                    @empty
                                                        <p class="text-xs text-gray-400 col-span-2">{{ __('messages.coming_soon') }}</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="col-span-5">
                                                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-brand-gold mb-5">{{ __('messages.popular_safaris') ?? 'Popular Safaris' }}</p>
                                                <div class="grid grid-cols-2 gap-3">
                                                    @forelse($navSafaris ?? [] as $ns)
                                                        <a href="{{ route('safaris.show', ['locale' => $currentLocale, 'slug' => $ns->slug]) }}" class="group relative rounded-lg overflow-hidden aspect-[4/3]">
                                                            <img src="{{ asset('storage/' . $ns->featured_image) }}" alt="{{ $ns->translated('title') }}" class="absolute inset-0 w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                                            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/10 to-transparent"></div>
                                                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                                                <p class="text-[11px] font-semibold text-white leading-tight line-clamp-2">{{ $ns->translated('title') }}</p>
                                                                @if($ns->duration_days)
                                                                    <p class="text-[10px] text-brand-gold mt-0.5">{{ $ns->duration_days }} Days</p>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    @empty
                                                        <div class="col-span-2 relative rounded-xl overflow-hidden min-h-[140px]">
                                                            <img src="https://images.unsplash.com/photo-1547970810-dc1eac37d174?w=400&h=280&fit=crop&q=80" alt="Safari wildlife" class="absolute inset-0 w-full h-full object-cover">
                                                            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/20 to-transparent"></div>
                                                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                                                <p class="text-sm text-white leading-relaxed">Explore our safaris</p>
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $menuUrl }}"
                           @if($menuItem->open_in_new_tab ?? false) target="_blank" rel="noopener" @endif
                           class="nav-link px-3 py-6 text-[14px] font-semibold uppercase tracking-[0.12em] text-brand-dark hover:text-brand-green transition">
                            {{ $menuItem->label }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- â”€â”€â”€â”€â”€â”€â”€â”€ RIGHT SIDE: LANG + CTA â”€â”€â”€â”€â”€â”€â”€â”€ --}}
            {{-- -------- RIGHT SIDE: CTA -------- --}}
            <div class="hidden xl:flex items-center shrink-0 ml-4">
                {{-- CTA Button --}}
                <a href="{{ route('plan-safari', ['locale' => $currentLocale]) }}"
                   class="px-6 py-2.5 bg-brand-gold text-brand-dark text-[11px] font-bold uppercase tracking-[0.14em] rounded-sm whitespace-nowrap hover:bg-brand-dark hover:text-white transition-all duration-300 shrink-0">
                    {{ __('messages.start_planning') }}
                </a>
            </div>

            {{-- â”€â”€â”€â”€â”€â”€â”€â”€ MOBILE HAMBURGER â”€â”€â”€â”€â”€â”€â”€â”€ --}}
            <button @click="mobileOpen = !mobileOpen" class="xl:hidden relative z-50 w-10 h-10 flex flex-col justify-center items-center shrink-0" :class="mobileOpen && 'ham-active'" aria-label="Toggle menu">
                <span class="ham-top block w-6 h-[2px] bg-brand-dark rounded-full transition-all duration-300"></span>
                <span class="ham-mid block w-6 h-[2px] bg-brand-dark rounded-full mt-[5px] transition-all duration-300"></span>
                <span class="ham-bot block w-6 h-[2px] bg-brand-dark rounded-full mt-[5px] transition-all duration-300"></span>
            </button>
        </nav>
    </header>

    {{-- ========== MOBILE BACKDROP ========== --}}
    <div class="mobile-backdrop fixed inset-0 z-[90] bg-black/50 xl:hidden" :class="mobileOpen && 'open'" @click="mobileOpen = false"></div>

    {{-- ========== MOBILE OFF-CANVAS MENU ========== --}}
    <div class="mobile-menu fixed inset-0 z-[100] bg-white xl:hidden overflow-y-auto"
         :class="mobileOpen && 'open'"
         x-data="{ openSection: null }">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="max-h-10 object-contain" style="width: {{ min($logoWidth, 150) }}px; height: auto;">
                @else
                    <span class="font-heading text-sm font-bold tracking-[0.12em] text-brand-green">{{ strtoupper($siteName ?? 'LOMO SAFARI') }}</span>
                @endif
            </a>
            <button @click="mobileOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition" aria-label="Close menu">
                <svg class="w-4 h-4 text-brand-dark/80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Nav links --}}
        <div class="px-6 py-4 space-y-0">

            @foreach($menuItems as $menuItem)
                @php
                    $menuSlug = $menuItem->slug ?? null;
                    $menuUrl = $resolveMenuUrl($menuItem);
                @endphp

                @if($menuSlug === 'destinations')
                    <div class="border-b border-gray-50">
                        <button @click="openSection = openSection === 'dest' ? null : 'dest'" class="mob-link w-full flex justify-between items-center py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark">
                            {{ $menuItem->label }}
                            <svg class="w-3.5 h-3.5 text-brand-dark/50 transition-transform duration-300" :class="openSection === 'dest' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openSection === 'dest'" x-collapse x-cloak>
                            <div class="pb-4 pl-3 space-y-2">
                                <a href="{{ route('destinations.tanzania', ['locale' => $currentLocale]) }}" class="flex items-center gap-3 rounded-xl border border-gray-100 px-3 py-3 text-brand-dark hover:border-brand-green/20 hover:bg-brand-light transition">
                                    <span class="text-lg">🇹🇿</span>
                                    <div>
                                        <p class="text-sm font-semibold">Tanzania</p>
                                        <p class="text-[11px] text-gray-500">Explore the flagship safari country</p>
                                    </div>
                                </a>
                                @foreach($navDestinationItems as $dest)
                                    <a href="{{ route('destinations.show', ['locale' => $currentLocale, 'slug' => $dest->slug]) }}" class="flex items-center gap-3 rounded-xl px-2 py-2 text-[12px] text-brand-dark/80 hover:text-brand-green transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                        <span>{{ $dest->translated('name') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif($menuSlug === 'safaris')
                    <div class="border-b border-gray-50">
                        <button @click="openSection = openSection === 'safari' ? null : 'safari'" class="mob-link w-full flex justify-between items-center py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark">
                            {{ $menuItem->label }}
                            <svg class="w-3.5 h-3.5 text-brand-dark/50 transition-transform duration-300" :class="openSection === 'safari' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openSection === 'safari'" x-collapse x-cloak>
                            <div class="pb-4 pl-3 space-y-1">
                                @if(($navCategories ?? collect())->isNotEmpty())
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-2 mb-2">{{ __('messages.categories') }}</p>
                                    @foreach($navCategories as $cat)
                                        <a href="{{ route('categories.show', ['locale' => $currentLocale, 'slug' => $cat->slug]) }}" class="block py-1.5 text-[12px] text-brand-dark/80 hover:text-brand-green transition pl-2">{{ $cat->translated('name') }}</a>
                                    @endforeach
                                @endif
                                @if(($navTourTypes ?? collect())->isNotEmpty())
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-3 mb-2">{{ __('messages.safari_types') }}</p>
                                    @foreach($navTourTypes as $type)
                                        <a href="{{ route('tour-types.show', ['locale' => $currentLocale, 'slug' => $type->slug]) }}" class="block py-1.5 text-[12px] text-brand-dark/80 hover:text-brand-green transition pl-2">{{ $type->translated('name') }}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $menuUrl }}"
                       @if($menuItem->open_in_new_tab ?? false) target="_blank" rel="noopener" @endif
                       class="mob-link block py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-brand-dark border-b border-gray-50">
                        {{ $menuItem->label }}
                    </a>
                @endif
            @endforeach
        </div>

        {{-- Language + CTA --}}
        <div class="px-6 pt-2 pb-8 space-y-5">

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
    <footer class="bg-brand-dark text-white/80">
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
                    <p class="text-sm text-white/70 mt-4 leading-relaxed">{{ __('messages.brand_description') }}</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.quick_links') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        <li><a href="{{ route('home', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('destinations.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.destinations') }}</a></li>
                        <li><a href="{{ route('safaris.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.safari') }}</a></li>
                        <li><a href="{{ route('experiences.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.experiences') }}</a></li>
                        <li><a href="{{ route('blog.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.blog') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.quick_links') }}
                            <svg class="accordion-icon w-4 h-4 text-white/80 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li><a href="{{ route('home', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.home') }}</a></li>
                                <li><a href="{{ route('destinations.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.destinations') }}</a></li>
                                <li><a href="{{ route('safaris.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.safari') }}</a></li>
                                <li><a href="{{ route('experiences.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.experiences') }}</a></li>
                                <li><a href="{{ route('blog.index', ['locale' => $currentLocale]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.blog') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Experiences --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.experiences_footer') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        @foreach(($navTourTypes ?? collect())->take(5) as $footerType)
                            <li><a href="{{ route('tour-types.show', ['slug' => $footerType->slug]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ $footerType->translated('name') }}</a></li>
                        @endforeach
                        <li><a href="{{ route('experiences.index') }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.view_all') }} &rarr;</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.experiences_footer') }}
                            <svg class="accordion-icon w-4 h-4 text-white/80 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                @foreach(($navTourTypes ?? collect())->take(5) as $footerType)
                                    <li><a href="{{ route('tour-types.show', ['slug' => $footerType->slug]) }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ $footerType->translated('name') }}</a></li>
                                @endforeach
                                <li><a href="{{ route('experiences.index') }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.view_all') }} &rarr;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Get In Touch --}}
                <div>
                    <p class="hidden md:block text-[11px] uppercase tracking-widest text-brand-gold font-semibold mb-4">{{ __('messages.get_in_touch') }}</p>
                    <ul class="hidden md:block space-y-3 text-sm">
                        <li class="text-white/80">Arusha, Tanzania</li>
                        <li><a href="mailto:info@lomotanzania.com" class="text-white/80 hover:text-brand-gold transition duration-200">info@lomotanzania.com</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.contact_page') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-white/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-xs uppercase tracking-widest text-brand-gold font-semibold">
                            {{ __('messages.get_in_touch') }}
                            <svg class="accordion-icon w-4 h-4 text-white/80 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li class="text-white/80">Arusha, Tanzania</li>
                                <li><a href="mailto:info@lomotanzania.com" class="text-white/80 hover:text-brand-gold transition duration-200">info@lomotanzania.com</a></li>
                                <li><a href="{{ route('contact') }}" class="text-white/80 hover:text-brand-gold transition duration-200">{{ __('messages.contact_page') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-white/10 mt-12 pt-8 text-center space-y-1">
                <p class="text-xs text-white/80">&copy; {{ date('Y') }} {{ $siteName ?? 'Lomo Tanzania Safari' }}. {{ __('messages.all_rights') }}</p>
                <p class="text-[10px] text-white/80">Powered by Scop Kariah</p>
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

        // Footer accordion (vanilla JS - footer is outside Alpine scope)
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

    @include('partials.chat-widget')

</body>
</html>
