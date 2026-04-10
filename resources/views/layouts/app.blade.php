<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Title --}}
    <title>@yield('seo_title', ($seoTitle ?? $siteName ?? 'Lomo Tanzania Safaris'))</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Great+Vibes&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

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
                        heading: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        body: ['"Lato"', 'sans-serif'],
                        accent: ['"Great Vibes"', 'cursive'],
                    },
                    fontSize: {
                        'kicker': ['0.6875rem', { lineHeight: '1.4', letterSpacing: '0.25em', fontWeight: '700' }],
                        'label':  ['0.75rem',   { lineHeight: '1.4', letterSpacing: '0.1em',  fontWeight: '700' }],
                    },
                    letterSpacing: {
                        'safari':  '0.015em',
                        'kicker':  '0.25em',
                        'heading': '0.12em',
                    },
                    lineHeight: {
                        'body': '1.75',
                        'heading': '1.15',
                    },
                }
            }
        }
    </script>

    @stack('styles')

    {{-- Global typography + Nav animations --}}
    <style>
        [x-cloak] { display: none !important; }

        /* ======================================
           GLOBAL TYPOGRAPHY SYSTEM
           Cormorant Garamond (headings) + Lato (body)
        ====================================== */

        body {
            font-family: 'Lato', sans-serif;
            font-size: 1rem;            /* 16px base */
            line-height: 1.7;
            color: #2D2D2D;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        /* — Heading hierarchy — */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 700;
            color: #131414;
            letter-spacing: 0.01em;
            line-height: 1.15;
        }

        h1 {
            font-size: 2.25rem;         /* 36px mobile */
            letter-spacing: 0.015em;
            line-height: 1.1;
        }
        h2 {
            font-size: 1.75rem;         /* 28px mobile */
            line-height: 1.2;
        }
        h3 {
            font-size: 1.375rem;        /* 22px mobile */
            line-height: 1.25;
        }
        h4 {
            font-size: 1.125rem;        /* 18px mobile */
            font-weight: 600;
            line-height: 1.3;
        }
        h5 {
            font-size: 1rem;            /* 16px */
            font-weight: 600;
            line-height: 1.4;
        }
        h6 {
            font-size: 0.875rem;        /* 14px */
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* Tablet breakpoint */
        @media (min-width: 768px) {
            body { font-size: 1rem; line-height: 1.75; }
            h1 { font-size: 2.75rem; }  /* 44px */
            h2 { font-size: 2.125rem; } /* 34px */
            h3 { font-size: 1.5rem; }   /* 24px */
            h4 { font-size: 1.25rem; }  /* 20px */
        }

        /* Desktop breakpoint */
        @media (min-width: 1024px) {
            h1 { font-size: 3.25rem; }  /* 52px */
            h2 { font-size: 2.5rem; }   /* 40px */
            h3 { font-size: 1.75rem; }  /* 28px */
            h4 { font-size: 1.375rem; } /* 22px */
        }

        /* Large desktop */
        @media (min-width: 1280px) {
            h1 { font-size: 3.75rem; }  /* 60px */
            h2 { font-size: 2.75rem; }  /* 44px */
        }

        /* — Paragraph & body text — */
        p {
            margin-bottom: 1rem;
            color: rgba(45, 45, 45, 0.85);
            line-height: 1.75;
        }

        /* — Section kicker / eyebrow label — */
        .kicker,
        .eyebrow {
            font-family: 'Lato', sans-serif;
            font-size: 0.6875rem;       /* 11px */
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* — Section heading with uppercase treatment — */
        .heading-caps {
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        /* — Small caps for subheadings / labels — */
        .label-caps {
            font-family: 'Lato', sans-serif;
            font-size: 0.75rem;         /* 12px */
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(19, 20, 20, 0.55);
        }

        /* — Body text sizing utilities — */
        .text-body-sm  { font-size: 0.875rem; line-height: 1.65; } /* 14px */
        .text-body     { font-size: 1rem;     line-height: 1.75; } /* 16px */
        .text-body-lg  { font-size: 1.125rem; line-height: 1.8;  } /* 18px */

        /* — Brand color text utilities — */
        .text-safari-dark  { color: #131414; }
        .text-safari-body  { color: rgba(45, 45, 45, 0.85); }
        .text-safari-muted { color: rgba(45, 45, 45, 0.6); }
        .text-safari-light { color: rgba(45, 45, 45, 0.45); }

        /* ====================================== */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #FEBC11;
            border-radius: 1px;
            transform: translateX(-50%);
            transition: width 320ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        .nav-link:hover::after,
        .nav-item:hover > .nav-link::after,
        .nav-link.active::after {
            width: 24px;
        }

        /* ====================================== */
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

        /* — MOBILE OFF-CANVAS — */
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

        /* ═══════ GLOBAL ANIMATION SYSTEM ═══════ */
        /* ADDED: configurable animation types driven by data attributes */

        /* Speed modifiers (default = medium) */
        :root {
            --anim-duration: 800ms;
            --anim-easing: cubic-bezier(0.16, 1, 0.3, 1);
        }
        [data-anim-speed="slow"]  { --anim-duration: 1200ms; }
        [data-anim-speed="fast"]  { --anim-duration: 450ms; }

        /* Base: all animated sections start hidden */
        [data-animate] {
            opacity: 0;
            transition: opacity var(--anim-duration) var(--anim-easing),
                        transform var(--anim-duration) var(--anim-easing);
        }

        /* Fade In */
        [data-animate="fade-in"]          { transform: none; }
        /* Slide Up (default) */
        [data-animate="slide-up"]         { transform: translateY(30px); }
        /* Slide Left */
        [data-animate="slide-left"]       { transform: translateX(-60px); }
        /* Slide Right */
        [data-animate="slide-right"]      { transform: translateX(60px); }
        /* Zoom In */
        [data-animate="zoom-in"]          { transform: scale(0.92); }
        /* Stagger — applied to container, children animate */
        [data-animate="stagger"] > * {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity var(--anim-duration) var(--anim-easing),
                        transform var(--anim-duration) var(--anim-easing);
        }

        /* Visible state */
        [data-animate].visible {
            opacity: 1;
            transform: none;
        }
        [data-animate="stagger"].visible > * {
            opacity: 1;
            transform: none;
        }
        /* Stagger child delays (up to 8 children) */
        [data-animate="stagger"].visible > *:nth-child(1) { transition-delay: 0ms; }
        [data-animate="stagger"].visible > *:nth-child(2) { transition-delay: 120ms; }
        [data-animate="stagger"].visible > *:nth-child(3) { transition-delay: 240ms; }
        [data-animate="stagger"].visible > *:nth-child(4) { transition-delay: 360ms; }
        [data-animate="stagger"].visible > *:nth-child(5) { transition-delay: 480ms; }
        [data-animate="stagger"].visible > *:nth-child(6) { transition-delay: 600ms; }
        [data-animate="stagger"].visible > *:nth-child(7) { transition-delay: 720ms; }
        [data-animate="stagger"].visible > *:nth-child(8) { transition-delay: 840ms; }

        /* ── Legacy reveal classes (backward compatible) ── */

        /* Scroll-reveal */
        .reveal, .scroll-reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1), transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible, .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Slide-in from left */
        .reveal-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: opacity 900ms cubic-bezier(0.16, 1, 0.3, 1), transform 900ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Slide-in from right */
        .reveal-right {
            opacity: 0;
            transform: translateX(60px);
            transition: opacity 900ms cubic-bezier(0.16, 1, 0.3, 1), transform 900ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Staggered fade-up for child cards */
        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 600ms cubic-bezier(0.16, 1, 0.3, 1), transform 600ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-stagger.visible > * {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-stagger.visible > *:nth-child(1) { transition-delay: 0ms; }
        .reveal-stagger.visible > *:nth-child(2) { transition-delay: 150ms; }
        .reveal-stagger.visible > *:nth-child(3) { transition-delay: 300ms; }
        .reveal-stagger.visible > *:nth-child(4) { transition-delay: 450ms; }

        /* ── Cinematic Experience Stacking Scroll ── */
        .exp-cinema-section { overflow: visible; }
        .exp-cinema-heading { transition: opacity .6s ease; }
        @keyframes expHeadUp {
            from { opacity:0; transform:translateY(28px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Image zoom transition */
        .exp-cinema-img { transition: transform 1.6s cubic-bezier(.25,.46,.45,.94); }

        /* Staggered text reveal */
        .exp-txt-item {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .7s ease, transform .7s ease;
            transition-delay: calc(var(--exp-d, 0) * .12s + .15s);
        }
        .exp-txt-item.exp-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile: disable sticky, shrink heights */
        @media (max-width: 767px) {
            .exp-cinema-heading { position: relative !important; height: auto !important; padding: 4rem 0 2rem; }
            .exp-cinema-card   { position: relative !important; height: auto !important; min-height: 0 !important; }
            .exp-cinema-track  { margin-top: 0 !important; }
            .exp-txt-item      { opacity: 1 !important; transform: none !important; }
        }

        /* Line clamp utility */
        .line-clamp-5 {
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ====================================== */
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
</head>
<body class="flex flex-col min-h-screen bg-brand-light text-brand-dark">

    {{-- ========== FRONTEND ADMIN EDIT BAR ========== --}}
    @auth
        @if(auth()->user()->isSuperAdmin())
            @php
                $adminBarLinks = [];
                $routeName = Route::currentRouteName();

                if ($routeName === 'safaris.show' && isset($safari)) {
                    $adminBarLinks[] = ['label' => 'Edit Safari', 'url' => route('admin.safaris.edit', $safari->id), 'icon' => 'pencil'];
                } elseif ($routeName === 'destinations.show' && isset($destination)) {
                    $adminBarLinks[] = ['label' => 'Edit Destination', 'url' => route('admin.destinations.edit', $destination->id), 'icon' => 'pencil'];
                } elseif ($routeName === 'tour-types.show' && isset($tourType)) {
                    $adminBarLinks[] = ['label' => 'Edit Experience', 'url' => route('admin.tour-types.edit', $tourType->id), 'icon' => 'pencil'];
                } elseif ($routeName === 'blog.show' && isset($post)) {
                    $adminBarLinks[] = ['label' => 'Edit Post', 'url' => route('admin.posts.edit', $post->id), 'icon' => 'pencil'];
                }
            @endphp
            <div class="admin-edit-bar fixed top-0 left-0 right-0 z-[9999] flex items-center justify-between bg-[#1e1e1e]/95 backdrop-blur-sm px-4 py-1.5 text-white shadow-lg"
                 style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin') }}" class="flex items-center gap-1.5 text-xs font-bold text-white/90 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z"/><circle cx="12" cy="12" r="3"/></svg>
                        Dashboard
                    </a>
                    @foreach($adminBarLinks as $link)
                        <span class="text-white/30">|</span>
                        <a href="{{ $link['url'] }}" class="flex items-center gap-1.5 rounded bg-white/10 px-2.5 py-1 text-xs font-semibold text-[#FEBC11] hover:bg-white/20 hover:text-yellow-300 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
                <div class="flex items-center gap-2 text-[11px] text-white/50">
                    <span>Logged in as <strong class="text-white/70">{{ auth()->user()->name }}</strong></span>
                </div>
            </div>
            <div class="h-[36px]"></div>{{-- Spacer to offset fixed bar --}}
        @endif
    @endauth

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

        // Removed "Experiences" — already exists under Safaris mega menu
        if ($menuItems->isEmpty()) {
            $menuItems = collect([
                ['label' => __('messages.home'), 'slug' => 'home', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.destinations'), 'slug' => 'destinations', 'url' => null, 'open_in_new_tab' => false],
                ['label' => __('messages.safari'), 'slug' => 'safaris', 'url' => null, 'open_in_new_tab' => false],
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
        // Show 4 destinations instead of 3
        $navDestinationItems = ($navTanzaniaDestinations ?? collect())->take(4);
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

    <header class="bg-white sticky top-0 z-50 border-b border-gray-100 transition-shadow duration-300" id="main-header">
        <nav class="w-full px-4 lg:px-10 flex items-center h-[80px] gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 mr-auto">
                @if(optional($siteSetting ?? null)->logo_path)
                    <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="max-h-14 object-contain" style="width: {{ $logoWidth }}px; height: auto;">
                @else
                    <div class="flex flex-col justify-center">
                        <span class="font-heading text-lg font-bold tracking-[0.15em] text-brand-green whitespace-nowrap">{{ strtoupper($siteName ?? 'LOMO TANZANIA SAFARI') }}</span>
                        <span class="text-[9px] tracking-[0.2em] text-brand-gold/90 uppercase whitespace-nowrap">{{ $siteTagline ?? 'Less On Ourselves, More On Others' }}</span>
                    </div>
                @endif
            </a>

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
                                {{-- FIXED MEGA MENU: Destinations panel with equal height columns --}}
                                <div class="border-t border-gray-100 bg-white shadow-[0_24px_60px_-20px_rgba(0,0,0,0.12)]">
                                    <div class="mx-auto max-w-7xl px-6 py-10">
                                        <div class="grid grid-cols-12 gap-8 items-stretch">
                                            <div class="col-span-4 flex flex-col">
                                                <p class="mb-4 text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold">Country</p>
                                                @php
                                                    $countryImage = $navCountryCard?->featured_image
                                                        ? asset('storage/' . $navCountryCard->featured_image)
                                                        : ($navFeatureItem?->featured_image
                                                            ? asset('storage/' . $navFeatureItem->featured_image)
                                                            : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=520&fit=crop&q=80');
                                                @endphp
                                                <a href="{{ route('destinations.tanzania', ['locale' => $currentLocale]) }}" class="group block overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md">
                                                    <div class="aspect-[4/3] overflow-hidden">
                                                        <img src="{{ $countryImage }}" alt="Tanzania" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                                    </div>
                                                    <div class="p-4">
                                                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-gold">Featured country</p>
                                                        <p class="mt-1 font-heading text-2xl font-bold text-brand-dark">Tanzania</p>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-span-4 flex flex-col">
                                                <p class="mb-4 text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold">Destinations</p>
                                                <div class="space-y-2 flex-1">
                                                    @forelse($navDestinationItems as $dest)
                                                        <a href="{{ route('destinations.show', ['locale' => $currentLocale, 'slug' => $dest->slug]) }}" class="group flex items-center gap-3 rounded-xl border border-gray-100 px-3 py-2.5 transition hover:border-brand-green/20 hover:bg-brand-light">
                                                            @if($dest->featured_image)
                                                                <img src="{{ asset('storage/' . $dest->featured_image) }}" alt="{{ $dest->translated('name') }}" loading="lazy" class="h-11 w-11 flex-shrink-0 rounded-lg object-cover">
                                                            @else
                                                                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-brand-green/10 text-xs font-bold text-brand-green">{{ strtoupper(substr($dest->translated('name'), 0, 2)) }}</div>
                                                            @endif
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-[14px] font-semibold text-brand-dark transition group-hover:text-brand-green">{{ $dest->translated('name') }}</p>
                                                                <p class="line-clamp-1 text-xs text-gray-500">{{ Str::limit(strip_tags($dest->translated('description')), 60) }}</p>
                                                            </div>
                                                            <span class="text-brand-green transition group-hover:translate-x-1">→</span>
                                                        </a>
                                                    @empty
                                                        <p class="text-xs text-gray-400">{{ __('messages.coming_soon') }}</p>
                                                    @endforelse
                                                </div>
                                                @if($navDestinationItems->isNotEmpty())
                                                    <a href="{{ route('destinations.tanzania', ['locale' => $currentLocale]) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-green hover:text-brand-dark transition">
                                                        View All Destinations   →
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="col-span-4 flex flex-col">
                                                <p class="mb-4 text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold">Featured escape</p>
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
                                                <a href="{{ $featureLink }}" class="group relative block flex-1 min-h-[300px] overflow-hidden rounded-2xl">
                                                    <img src="{{ $featureImage }}" alt="Featured Tanzania destination" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
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
                                        <div class="grid grid-cols-12 gap-10">
                                            {{-- UPDATED NAV FONT: Budget column --}}
                                            <div class="col-span-3 border-r border-gray-100 pr-8">
                                                <p class="text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold mb-5 flex items-center gap-2">
                                                    <span class="w-5 h-px bg-brand-gold/40"></span>
                                                    {{ __('messages.categories') }}
                                                </p>
                                                <ul class="space-y-1.5">
                                                    @forelse($navCategories ?? [] as $cat)
                                                        <li>
                                                            <a href="{{ route('categories.show', ['locale' => $currentLocale, 'slug' => $cat->slug]) }}" class="flex items-center gap-2.5 py-2.5 px-3 -mx-3 text-[14px] text-brand-dark/80 hover:text-brand-green hover:bg-brand-green/[0.04] rounded-lg transition group">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-brand-gold/50 group-hover:bg-brand-green shrink-0 transition"></span>
                                                                <span class="font-medium">{{ $cat->translated('name') }}</span>
                                                            </a>
                                                        </li>
                                                    @empty
                                                        <li class="text-xs text-gray-400 px-3">{{ __('messages.coming_soon') }}</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            {{-- UPDATED NAV FONT: Experiences column --}}
                                            <div class="col-span-4 border-r border-gray-100 pr-8">
                                                <p class="text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold mb-5 flex items-center gap-2">
                                                    <span class="w-5 h-px bg-brand-gold/40"></span>
                                                    {{ __('messages.safari_types') }}
                                                </p>
                                                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5">
                                                    @forelse($navTourTypes ?? [] as $type)
                                                        <a href="{{ route('tour-types.show', ['locale' => $currentLocale, 'slug' => $type->slug]) }}" class="group flex items-center gap-2.5 py-2.5 px-3 -mx-3 text-[14px] text-brand-dark/80 hover:text-brand-green hover:bg-brand-green/[0.04] rounded-lg transition">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold/50 group-hover:bg-brand-green shrink-0 transition"></span>
                                                            {{ $type->translated('name') }}
                                                        </a>
                                                    @empty
                                                        <p class="text-xs text-gray-400 col-span-2 px-3">{{ __('messages.coming_soon') }}</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                            {{-- UPDATED NAV FONT: Popular Safaris column --}}
                                            <div class="col-span-5">
                                                <p class="text-[13px] font-bold uppercase tracking-[0.2em] text-brand-gold mb-5 flex items-center gap-2">
                                                    <span class="w-5 h-px bg-brand-gold/40"></span>
                                                    {{ __('messages.popular_safaris') }}
                                                </p>
                                                <div class="grid grid-cols-2 gap-3">
                                                    @forelse($navSafaris ?? [] as $ns)
                                                        <a href="{{ route('safaris.show', ['locale' => $currentLocale, 'slug' => $ns->slug]) }}" class="group relative rounded-lg overflow-hidden aspect-[4/3]">
                                                            <img src="{{ asset('storage/' . $ns->featured_image) }}" alt="{{ $ns->translated('title') }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition duration-300 group-hover:scale-105">
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
                                                            <img src="https://images.unsplash.com/photo-1547970810-dc1eac37d174?w=400&h=280&fit=crop&q=80" alt="Safari wildlife" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
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

            {{-- -------- RIGHT SIDE: CTA -------- --}}
            <div class="hidden xl:flex items-center shrink-0 ml-4">
                {{-- CTA Button --}}
                <a href="{{ route('plan-safari', ['locale' => $currentLocale]) }}"
                   class="px-6 py-2.5 bg-brand-gold text-brand-dark text-[11px] font-bold uppercase tracking-[0.14em] rounded-sm whitespace-nowrap hover:bg-brand-dark hover:text-white transition-all duration-300 shrink-0">
                    {{ __('messages.start_planning') }}
                </a>
            </div>

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

        {{-- Header with gradient accent --}}
        <div class="relative">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-green via-brand-gold to-brand-green"></div>
            <div class="flex items-center justify-between px-5 py-5 border-b border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @if(optional($siteSetting ?? null)->logo_path)
                        <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="max-h-10 object-contain" style="width: {{ min($logoWidth, 150) }}px; height: auto;">
                    @else
                        <span class="font-heading text-sm font-bold tracking-[0.12em] text-brand-green">{{ strtoupper($siteName ?? 'LOMO SAFARI') }}</span>
                    @endif
                </a>
                <button @click="mobileOpen = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition" aria-label="Close menu">
                    <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Nav links --}}
        <div class="px-5 py-3 space-y-0">

            @foreach($menuItems as $menuItem)
                @php
                    $menuSlug = $menuItem->slug ?? null;
                    $menuUrl = $resolveMenuUrl($menuItem);
                @endphp

                @if($menuSlug === 'destinations')
                    <div class="border-b border-gray-100">
                        <button @click="openSection = openSection === 'dest' ? null : 'dest'" class="mob-link w-full flex justify-between items-center py-4 text-[14px] font-bold uppercase tracking-[0.1em] text-brand-dark">
                            {{ $menuItem->label }}
                            <svg class="w-4 h-4 text-brand-dark/40 transition-transform duration-300" :class="openSection === 'dest' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
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
                    <div class="border-b border-gray-100">
                        <button @click="openSection = openSection === 'safari' ? null : 'safari'" class="mob-link w-full flex justify-between items-center py-4 text-[14px] font-bold uppercase tracking-[0.1em] text-brand-dark">
                            {{ $menuItem->label }}
                            <svg class="w-4 h-4 text-brand-dark/40 transition-transform duration-300" :class="openSection === 'safari' && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openSection === 'safari'" x-collapse x-cloak>
                            <div class="pb-4 pl-2 space-y-1">
                                @if(($navCategories ?? collect())->isNotEmpty())
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-2 mb-2">{{ __('messages.categories') }}</p>
                                    @foreach($navCategories as $cat)
                                        <a href="{{ route('categories.show', ['locale' => $currentLocale, 'slug' => $cat->slug]) }}" class="block py-2 text-[13px] text-brand-dark/80 hover:text-brand-green transition pl-2">{{ $cat->translated('name') }}</a>
                                    @endforeach
                                @endif
                                @if(($navTourTypes ?? collect())->isNotEmpty())
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold font-bold mt-3 mb-2">{{ __('messages.safari_types') }}</p>
                                    @foreach($navTourTypes as $type)
                                        <a href="{{ route('tour-types.show', ['locale' => $currentLocale, 'slug' => $type->slug]) }}" class="block py-2 text-[13px] text-brand-dark/80 hover:text-brand-green transition pl-2">{{ $type->translated('name') }}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $menuUrl }}"
                       @if($menuItem->open_in_new_tab ?? false) target="_blank" rel="noopener" @endif
                       class="mob-link block py-4 text-[14px] font-bold uppercase tracking-[0.1em] text-brand-dark border-b border-gray-100">
                        {{ $menuItem->label }}
                    </a>
                @endif
            @endforeach
        </div>

        {{-- CTA Buttons --}}
        <div class="px-5 pt-4 pb-3 space-y-3">
            <a href="{{ route('plan-safari', ['locale' => $currentLocale]) }}"
               class="block text-center px-5 py-3.5 bg-brand-gold text-brand-dark text-[13px] font-bold uppercase tracking-[0.12em] rounded-lg hover:bg-brand-dark hover:text-white transition-all duration-300 shadow-sm">
                {{ __('messages.start_planning') }}
            </a>

            @if(optional($siteSetting ?? null)->whatsapp_number)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSetting->whatsapp_number) }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 py-3 rounded-lg border border-[#25D366]/30 bg-[#25D366]/5 hover:bg-[#25D366]/10 transition duration-200">
                <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.111.547 4.099 1.504 5.832L0 24l6.335-1.652A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82c-1.89 0-3.69-.508-5.27-1.447l-.378-.224-3.918 1.023 1.047-3.823-.247-.393A9.782 9.782 0 012.18 12c0-5.414 4.406-9.82 9.82-9.82S21.82 6.586 21.82 12s-4.406 9.82-9.82 9.82z"/></svg>
                <span class="text-[13px] font-semibold text-[#25D366]">{{ __('messages.chat_whatsapp') }}</span>
            </a>
            @endif
        </div>

        {{-- Featured Safaris Slider --}}
        @if(($navSafaris ?? collect())->isNotEmpty())
        <div class="px-5 pb-6">
            <div class="border-t border-gray-100 pt-5">
                <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold font-bold mb-3">Popular Safaris</p>
                <div x-data="{ mobSlide: 0, total: {{ ($navSafaris ?? collect())->count() }} }" class="relative overflow-hidden rounded-xl">
                    <div class="flex transition-transform duration-400 ease-out" :style="'transform: translateX(-' + (mobSlide * 100) + '%)'">
                        @foreach($navSafaris ?? [] as $navSafari)
                        <a href="{{ route('safaris.show', ['locale' => $currentLocale, 'slug' => $navSafari->slug]) }}" class="w-full flex-shrink-0 group" @click="mobileOpen = false">
                            <div class="relative aspect-[16/9] overflow-hidden rounded-xl">
                                <img src="{{ $navSafari->featured_image ? asset('storage/' . $navSafari->featured_image) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=225&fit=crop' }}" alt="{{ $navSafari->translated('title') }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <p class="font-heading text-lg font-bold text-white leading-tight">{{ \Illuminate\Support\Str::limit($navSafari->translated('title'), 40) }}</p>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-white/80">
                                        @if($navSafari->duration)
                                            <span>{{ $navSafari->duration }} days</span>
                                        @endif
                                        @if($navSafari->price)
                                            <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                            <span class="text-brand-gold font-semibold">From ${{ number_format($navSafari->price) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @if(($navSafaris ?? collect())->count() > 1)
                    <div class="flex items-center justify-center gap-1.5 mt-3">
                        @foreach($navSafaris ?? [] as $idx => $ns)
                        <button @click="mobSlide = {{ $idx }}" class="h-1.5 rounded-full transition-all duration-300" :class="mobSlide === {{ $idx }} ? 'w-5 bg-brand-green' : 'w-1.5 bg-gray-300'"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>{{-- end x-data wrapper --}}

    {{-- Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="bg-[#f5f0de] text-brand-dark">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-16">

            {{-- Desktop: multi-column grid / Mobile: stacked --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-10">

                {{-- Brand (always visible) --}}
                <div class="md:col-span-1">
                    @if(optional($siteSetting ?? null)->logo_path)
                        <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ $siteName ?? 'Logo' }}" class="h-16 w-auto object-contain mb-3">
                    @else
                        <span class="font-heading text-2xl font-bold tracking-widest text-brand-dark block">{{ strtoupper($siteName ?? 'LOMO TANZANIA SAFARI') }}</span>
                    @endif
                    <p class="font-heading text-2xl sm:text-xl text-brand-green mt-3 italic">{{ $siteTagline ?? 'Less On Ourselves, More On Others' }}</p>
                    <p class="text-sm md:text-base text-brand-dark/80 mt-4 leading-relaxed">{{ __('messages.brand_description') }}</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <p class="hidden md:block text-sm uppercase tracking-widest text-brand-dark font-bold mb-4">{{ __('messages.quick_links') }}</p>
                    <ul class="hidden md:block space-y-3 text-[15px]">
                        <li><a href="{{ route('home', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('destinations.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.destinations') }}</a></li>
                        <li><a href="{{ route('safaris.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.safari') }}</a></li>
                        <li><a href="{{ route('experiences.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.experiences') }}</a></li>
                        <li><a href="{{ route('blog.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.blog') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-brand-dark/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-sm uppercase tracking-widest text-brand-dark font-bold">
                            {{ __('messages.quick_links') }}
                            <svg class="accordion-icon w-4 h-4 text-brand-dark/90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li><a href="{{ route('home', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.home') }}</a></li>
                                <li><a href="{{ route('destinations.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.destinations') }}</a></li>
                                <li><a href="{{ route('safaris.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.safari') }}</a></li>
                                <li><a href="{{ route('experiences.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.experiences') }}</a></li>
                                <li><a href="{{ route('blog.index', ['locale' => $currentLocale]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.blog') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Experiences --}}
                <div>
                    <p class="hidden md:block text-sm uppercase tracking-widest text-brand-dark font-bold mb-4">{{ __('messages.experiences_footer') }}</p>
                    <ul class="hidden md:block space-y-3 text-[15px]">
                        @foreach(($navTourTypes ?? collect())->take(5) as $footerType)
                            <li><a href="{{ route('tour-types.show', ['slug' => $footerType->slug]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ $footerType->translated('name') }}</a></li>
                        @endforeach
                        <li><a href="{{ route('experiences.index') }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.view_all') }} &rarr;</a></li>
                    </ul>
                    <div class="md:hidden border-t border-brand-dark/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-sm uppercase tracking-widest text-brand-dark font-bold">
                            {{ __('messages.experiences_footer') }}
                            <svg class="accordion-icon w-4 h-4 text-brand-dark/90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                @foreach(($navTourTypes ?? collect())->take(5) as $footerType)
                                    <li><a href="{{ route('tour-types.show', ['slug' => $footerType->slug]) }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ $footerType->translated('name') }}</a></li>
                                @endforeach
                                <li><a href="{{ route('experiences.index') }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.view_all') }} &rarr;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Get In Touch --}}
                <div>
                    <p class="hidden md:block text-sm uppercase tracking-widest text-brand-dark font-bold mb-4">{{ __('messages.get_in_touch') }}</p>
                    <ul class="hidden md:block space-y-3 text-[15px]">
                        <li class="text-brand-dark/90">Arusha, Tanzania</li>
                        <li><a href="mailto:info@lomotanzaniasafari.com" class="text-brand-dark/90 hover:text-brand-green transition duration-200">info@lomotanzaniasafari.com</a></li>
                        <li><a href="{{ route('contact') }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.contact_page') }}</a></li>
                    </ul>
                    <div class="md:hidden border-t border-brand-dark/10">
                        <button class="footer-accordion-toggle accordion-toggle w-full flex justify-between items-center py-4 text-sm uppercase tracking-widest text-brand-dark font-bold">
                            {{ __('messages.get_in_touch') }}
                            <svg class="accordion-icon w-4 h-4 text-brand-dark/90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="accordion-panel">
                            <ul class="pb-4 space-y-3 text-sm">
                                <li class="text-brand-dark/90">Arusha, Tanzania</li>
                                <li><a href="mailto:info@lomotanzaniasafari.com" class="text-brand-dark/90 hover:text-brand-green transition duration-200">info@lomotanzaniasafari.com</a></li>
                                <li><a href="{{ route('contact') }}" class="text-brand-dark/90 hover:text-brand-green transition duration-200">{{ __('messages.contact_page') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-brand-dark/10 mt-12 pt-8 pb-20 sm:pb-0 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-sm text-black">&copy; {{ date('Y') }} {{ $siteName ?? 'Lomo Tanzania Safari' }}. {{ __('messages.all_rights') }}</p>
                <p class="text-xs text-black">Powered by <a href="https://twncolors.com" target="_blank" rel="noopener noreferrer" class="underline hover:text-brand-green transition duration-200">Scop Kariah</a></p>
            </div>
        </div>
    </footer>

    {{-- ========== BACK TO TOP BUTTON ========== --}}
    {{-- UPDATED: dynamic color via JS --}}
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="fixed bottom-6 left-6 z-[80] flex h-11 w-11 items-center justify-center rounded-full shadow-lg transition-all duration-300 hover:scale-110 opacity-0 translate-y-4 pointer-events-none" aria-label="Back to top" style="transition: opacity 300ms, transform 300ms, background-color 400ms, color 400ms; background-color: #083321; color: #fff;">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
    </button>

    {{-- ========== SCRIPTS ========== --}}
    <script>
        // UPDATED: unified scroll-reveal observer for legacy classes + data-animate system
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Apply custom delay from data attribute if set
                    const delay = entry.target.dataset.animDelay;
                    if (delay) {
                        setTimeout(() => entry.target.classList.add('visible'), parseInt(delay, 10));
                    } else {
                        entry.target.classList.add('visible');
                    }
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        // ADDED: observe both legacy .reveal classes, .scroll-reveal, and new data-animate elements
        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-stagger, .scroll-reveal, [data-animate]').forEach(el => revealObserver.observe(el));

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

        // Back to top button + ADDED: dynamic color detection
        (function() {
            const btn = document.getElementById('back-to-top');
            if (!btn) return;
            let visible = false;

            // ADDED: color detection for floating buttons
            const PRIMARY = '#083321';
            const SECONDARY = '#FEBC11';

            function detectBgColor(el) {
                const rect = el.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const cy = rect.top + rect.height / 2;
                // Walk up from the element behind the button
                el.style.pointerEvents = 'none';
                const behind = document.elementFromPoint(cx, cy);
                el.style.pointerEvents = '';
                if (!behind) return 'light';
                let node = behind;
                while (node && node !== document.body) {
                    const bg = getComputedStyle(node).backgroundColor;
                    if (bg && bg !== 'rgba(0, 0, 0, 0)' && bg !== 'transparent') {
                        const m = bg.match(/\d+/g);
                        if (m) {
                            const [r, g, b] = m.map(Number);
                            const hex = '#' + [r,g,b].map(c => c.toString(16).padStart(2,'0')).join('');
                            // Check if primary green
                            if (r < 30 && g > 40 && g < 70 && b < 50) return 'primary';
                            // Dark check (luminance)
                            const lum = (0.299*r + 0.587*g + 0.114*b) / 255;
                            return lum < 0.45 ? 'dark' : 'light';
                        }
                    }
                    node = node.parentElement;
                }
                return 'light';
            }

            function updateBtnColor() {
                if (!visible) return;
                const tone = detectBgColor(btn);
                if (tone === 'primary') {
                    btn.style.backgroundColor = SECONDARY;
                    btn.style.color = '#131414';
                } else if (tone === 'dark') {
                    btn.style.backgroundColor = '#ffffff';
                    btn.style.color = '#131414';
                } else {
                    btn.style.backgroundColor = PRIMARY;
                    btn.style.color = '#ffffff';
                }
            }

            // Also update chat button color
            function updateChatBtnColor() {
                const chatBtn = document.querySelector('[x-data*="liveChatWidget"] > button:last-of-type, [x-data*="liveChatWidget"] button.w-14');
                if (!chatBtn) return;
                const tone = detectBgColor(chatBtn);
                if (tone === 'primary') {
                    chatBtn.style.backgroundColor = SECONDARY;
                    chatBtn.querySelectorAll('svg').forEach(s => s.setAttribute('class', 'w-6 h-6 text-[#131414]'));
                } else if (tone === 'dark') {
                    chatBtn.style.backgroundColor = '#ffffff';
                    chatBtn.querySelectorAll('svg').forEach(s => s.setAttribute('class', 'w-6 h-6 text-brand-dark'));
                } else {
                    chatBtn.style.backgroundColor = PRIMARY;
                    chatBtn.querySelectorAll('svg').forEach(s => s.setAttribute('class', 'w-6 h-6 text-white'));
                }
            }

            let colorRaf = false;
            window.addEventListener('scroll', () => {
                const show = window.scrollY > 600;
                if (show !== visible) {
                    visible = show;
                    btn.style.opacity = show ? '1' : '0';
                    btn.style.transform = show ? 'translateY(0)' : 'translateY(1rem)';
                    btn.style.pointerEvents = show ? 'auto' : 'none';
                }
                if (!colorRaf) {
                    colorRaf = true;
                    requestAnimationFrame(() => {
                        updateBtnColor();
                        updateChatBtnColor();
                        colorRaf = false;
                    });
                }
            }, { passive: true });
        })();
    </script>

    @include('partials.global-toast')

    @stack('scripts')

    @include('partials.chat-widget')

    <script>
    (function(){
        const hdr = document.getElementById('main-header');
        if (!hdr) return;
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (window.scrollY > 20) {
                        hdr.classList.add('shadow-md');
                        hdr.classList.remove('border-b', 'border-gray-100');
                    } else {
                        hdr.classList.remove('shadow-md');
                        hdr.classList.add('border-b', 'border-gray-100');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    })();
    </script>

</body>
</html>
