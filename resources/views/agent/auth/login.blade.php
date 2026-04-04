<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Portal — Lomo Tanzania Safari</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { dark: '#131414', gold: '#FEBC11', light: '#F9F7F3', green: '#083321' } },
                    fontFamily: { body: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
</head>
<body class="font-body antialiased bg-brand-light min-h-screen flex items-center justify-center">
<div class="w-full max-w-md px-6 py-12">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-brand-dark flex items-center justify-center mx-auto mb-4">
            <span class="text-brand-gold font-bold text-lg">L</span>
        </div>
        <h1 class="font-semibold text-2xl text-brand-dark">Agent Login</h1>
        <p class="text-gray-500 text-sm mt-1">Sign in to your agent portal</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-black/5 p-8">
        @if(session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('agent.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <span class="text-gray-600">Remember me</span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-brand-dark text-white font-semibold py-2.5 rounded-lg hover:bg-brand-dark/90 transition text-sm">
                Sign In
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="{{ route('agent.register') }}" class="text-brand-dark font-medium hover:underline">Register as agent</a>
    </p>
</div>
</body>
</html>
