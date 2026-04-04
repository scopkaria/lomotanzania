@extends('layouts.agent')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-lg">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-brand-dark">My Profile</h2>
        <p class="text-sm text-gray-500 mt-0.5">Update your agent details.</p>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-8">
        <form method="POST" action="{{ route('agent.profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="text" value="{{ Auth::user()->email }}" disabled
                       class="w-full border border-gray-100 bg-gray-50 rounded-lg px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-400">Contact admin to change email.</p>
            </div>

            <div class="border-t border-gray-100 pt-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Agency Details</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Company / Agency Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $agent->company_name) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                @error('company_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                    <input type="text" name="country" value="{{ old('country', $agent->country) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-brand-light rounded-xl px-4 py-3 text-sm">
                <span class="text-gray-500">Commission Rate: </span>
                <span class="font-bold text-brand-dark">{{ $agent->commission_rate }}%</span>
                <span class="text-gray-400 text-xs ml-1">(set by admin)</span>
            </div>

            <div class="bg-brand-light rounded-xl px-4 py-3 text-sm">
                <span class="text-gray-500">Account Status: </span>
                <span class="font-semibold {{ $agent->status === 'active' ? 'text-green-600' : 'text-amber-600' }}">
                    {{ ucfirst($agent->status) }}
                </span>
            </div>

            <button type="submit"
                    class="w-full bg-brand-dark text-white font-semibold py-2.5 rounded-xl hover:bg-brand-dark/90 transition text-sm">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
