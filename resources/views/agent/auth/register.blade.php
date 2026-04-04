<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Registration — Lomo Tanzania Safari</title>
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
<body class="font-body antialiased bg-brand-light min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-lg px-6">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-brand-dark flex items-center justify-center mx-auto mb-4">
            <span class="text-brand-gold font-bold text-lg">L</span>
        </div>
        <h1 class="font-semibold text-2xl text-brand-dark">Become an Agent</h1>
        <p class="text-gray-500 text-sm mt-1">Register your agency and start earning commissions</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-black/5 p-8">
        <form method="POST" action="{{ route('agent.register.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password *</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('password') border-red-400 @enderror">
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                </div>

                <div class="sm:col-span-2 border-t border-gray-100 pt-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Agency Details</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Company / Agency Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    @error('company_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                    <input type="text" name="country" value="{{ old('country') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-800">
                Your account will be reviewed by our team before activation. We'll notify you once approved.
            </div>

            <button type="submit"
                    class="w-full bg-brand-dark text-white font-semibold py-2.5 rounded-lg hover:bg-brand-dark/90 transition text-sm">
                Submit Registration
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <a href="{{ route('agent.login') }}" class="text-brand-dark font-medium hover:underline">Sign in</a>
    </p>
</div>
</body>
</html>
