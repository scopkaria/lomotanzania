<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password — {{ optional($siteSetting ?? null)->site_name ?: 'Lomo Tanzania Safari' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&family=playfair-display:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' } },
                    fontFamily: { body: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'] }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-body antialiased bg-brand-light min-h-screen flex items-center justify-center px-6 py-12">

<div class="w-full max-w-md">
    {{-- Logo --}}
    <div class="text-center mb-10">
        @if(optional($siteSetting ?? null)->logo_path)
            <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="{{ optional($siteSetting)->site_name ?: 'Lomo' }}" class="h-14 mx-auto mb-6">
        @else
            <div class="w-14 h-14 rounded-2xl bg-brand-dark flex items-center justify-center mx-auto mb-6">
                <span class="text-brand-gold font-bold text-xl">L</span>
            </div>
        @endif
        <h1 class="font-serif text-2xl text-brand-dark mb-1">Set New Password</h1>
        <p class="text-gray-400 text-sm">Choose a new secure password for your account.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-black/5 p-8">
        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green transition @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">New password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green transition @error('password') border-red-400 @enderror">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="show" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green transition">
            </div>

            <button type="submit"
                    class="w-full bg-brand-green text-white font-semibold py-3 rounded-xl hover:bg-brand-green/90 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:ring-offset-2 transition shadow-sm">
                Reset Password
            </button>
        </form>
    </div>
</div>

</body>
</html>
