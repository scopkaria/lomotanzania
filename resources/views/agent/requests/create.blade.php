@extends('layouts.agent')

@section('title', 'Request Custom Safari')

@section('content')
<div class="max-w-2xl space-y-6" x-data="requestForm()">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('agent.requests.index') }}" class="text-gray-400 hover:text-brand-dark transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-brand-dark">Request Custom Safari</h1>
            <p class="text-sm text-gray-500 mt-0.5">Submit client details and preferences. Admin will build a custom proposal.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm space-y-1">
        @foreach($errors->all() as $error)
        <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('agent.requests.store') }}" class="space-y-5">
        @csrf

        {{-- CLIENT INFO --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
            <h2 class="font-semibold text-brand-dark text-base">Client Information</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" required
                       placeholder="Client full name"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="client_email" value="{{ old('client_email') }}" required
                           placeholder="client@example.com"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone (with country code) <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <select x-model="dialCode" class="w-28 border border-gray-200 rounded-xl px-2 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 bg-white">
                            @foreach(['+1'=>'🇺🇸 +1','+44'=>'🇬🇧 +44','+49'=>'🇩🇪 +49','+33'=>'🇫🇷 +33','+39'=>'🇮🇹 +39','+27'=>'🇿🇦 +27','+255'=>'🇹🇿 +255','+254'=>'🇰🇪 +254','+256'=>'🇺🇬 +256','+260'=>'🇿🇲 +260','+61'=>'🇦🇺 +61','+91'=>'🇮🇳 +91','+86'=>'🇨🇳 +86','+81'=>'🇯🇵 +81','+55'=>'🇧🇷 +55'] as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="tel" x-model="phone" placeholder="712 345 678"
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                    </div>
                    <input type="hidden" name="client_phone" :value="dialCode + ' ' + phone">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Client's Country</label>
                <select name="country" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 bg-white">
                    <option value="">Select country...</option>
                    @foreach($countries as $country)
                    <option value="{{ $country->name }}" {{ old('country') === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- TRAVEL DETAILS --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
            <h2 class="font-semibold text-brand-dark text-base">Travel Details</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Travel Date <span class="text-red-500">*</span></label>
                    <input type="date" name="travel_date" value="{{ old('travel_date') }}" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of People <span class="text-red-500">*</span></label>
                    <input type="number" name="people" value="{{ old('people', 2) }}" min="1" max="200" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                </div>
            </div>
        </div>

        {{-- DESTINATIONS --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
            <h2 class="font-semibold text-brand-dark text-base">Destinations <span class="text-red-500">*</span></h2>
            <p class="text-xs text-gray-400 -mt-2">Select at least one destination.</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($destinations as $dest)
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" name="destinations[]" value="{{ $dest->id }}"
                           {{ in_array($dest->id, (array) old('destinations', [])) ? 'checked' : '' }}
                           class="w-4 h-4 accent-brand-green rounded">
                    <span class="text-sm text-gray-700 group-hover:text-brand-dark transition">{{ $dest->name }}</span>
                </label>
                @endforeach
            </div>
            @error('destinations')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- ACTIVITIES --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
            <h2 class="font-semibold text-brand-dark text-base">Activities & Interests</h2>
            <p class="text-xs text-gray-400 -mt-2">What is the client most interested in?</p>

            @php
                $activityOptions = collect($tourTypes)->pluck('name')->merge(collect($categories)->pluck('name'))->unique()->values();
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($activityOptions as $activity)
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" name="activities[]" value="{{ $activity }}"
                           {{ in_array($activity, (array) old('activities', [])) ? 'checked' : '' }}
                           class="w-4 h-4 accent-brand-green rounded">
                    <span class="text-sm text-gray-700 group-hover:text-brand-dark transition">{{ $activity }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- NOTES --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
            <h2 class="font-semibold text-brand-dark text-base">Client Preferences & Notes</h2>
            <textarea name="notes" rows="5" placeholder="Any special requests, budget expectations, dietary needs, accessibility requirements..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 resize-none">{{ old('notes') }}</textarea>
        </div>

        {{-- SUBMIT --}}
        <div class="flex items-center gap-4 pb-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark font-bold px-8 py-3.5 rounded-xl text-sm hover:bg-brand-gold/90 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Submit Request
            </button>
            <a href="{{ route('agent.requests.index') }}" class="text-sm text-gray-400 hover:text-brand-dark">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function requestForm() {
    return {
        dialCode: '{{ old("dialCode", "+1") }}',
        phone: '',
    }
}
</script>
@endpush
@endsection
