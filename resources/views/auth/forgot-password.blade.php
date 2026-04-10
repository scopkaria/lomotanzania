<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password — {{ optional($siteSetting ?? null)->site_name ?: 'Lomo Tanzania Safari' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' } },
                    fontFamily: { body: ['Lato', 'sans-serif'], serif: ['Cormorant Garamond', 'Georgia', 'serif'] }
                }
            }
        }
    </script>
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
        <h1 class="font-serif text-2xl text-brand-dark mb-1">Reset Password</h1>
        <p class="text-gray-400 text-sm max-w-xs mx-auto">Enter your email address and we'll send you a link to reset your password.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-black/5 p-8">
        @if(session('status'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green transition @error('email') border-red-400 @enderror"
                       placeholder="your@email.com">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-brand-green text-white font-semibold py-3 rounded-xl hover:bg-brand-green/90 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:ring-offset-2 transition shadow-sm">
                Send Reset Link
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">
        <a href="javascript:history.back()" class="text-brand-green hover:text-brand-green/80 font-medium transition">&larr; Back to login</a>
    </p>
</div>

</body>
</html>
