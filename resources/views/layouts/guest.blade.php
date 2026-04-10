<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Great+Vibes&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
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
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
