@extends('layouts.agent')
@section('page-title', 'Create Booking')

@section('content')
<div class="max-w-2xl">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-brand-dark">Create New Booking</h2>
        <p class="text-sm text-gray-500 mt-0.5">Fill in client details and select a safari package.</p>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-8">
        <form method="POST" action="{{ route('agent.bookings.store') }}" class="space-y-6" x-data="bookingForm()">
            @csrf

            {{-- Safari Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Safari Package *</label>
                <select name="safari_package_id" required x-model="safariId" @change="updatePrice()"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('safari_package_id') border-red-400 @enderror">
                    <option value="">— Select a safari —</option>
                    @foreach($safaris as $safari)
                        <option value="{{ $safari->id }}" data-price="{{ $safari->price }}" {{ old('safari_package_id') == $safari->id ? 'selected' : '' }}>
                            {{ $safari->title }} ({{ $safari->duration }}) — ${{ number_format($safari->price, 0) }} pp
                        </option>
                    @endforeach
                </select>
                @error('safari_package_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Client Details --}}
            <div class="border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Client Details</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Client Full Name *</label>
                        <input type="text" name="client_name" value="{{ old('client_name') }}" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('client_name') border-red-400 @enderror">
                        @error('client_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Client Email *</label>
                        <input type="email" name="client_email" value="{{ old('client_email') }}" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('client_email') border-red-400 @enderror">
                        @error('client_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Client Phone</label>
                        <input type="text" name="client_phone" value="{{ old('client_phone') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    </div>
                </div>
            </div>

            {{-- Travel Details --}}
            <div class="border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Travel Details</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Travel Date *</label>
                        <input type="date" name="travel_date" value="{{ old('travel_date') }}" required
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('travel_date') border-red-400 @enderror">
                        @error('travel_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Number of People *</label>
                        <input type="number" name="num_people" value="{{ old('num_people', 1) }}" required min="1" max="100"
                               x-model.number="numPeople" @input="updatePrice()"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold @error('num_people') border-red-400 @enderror">
                        @error('num_people')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes (optional)</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- Price Summary --}}
            <div x-show="safariId" class="bg-brand-light rounded-xl px-6 py-5 space-y-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Price Summary</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Price per person</span>
                    <span class="font-medium text-brand-dark">$<span x-text="pricePerPerson.toFixed(2)">0</span></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Number of people</span>
                    <span class="font-medium text-brand-dark" x-text="numPeople"></span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex justify-between text-base font-bold">
                    <span class="text-brand-dark">Total Price</span>
                    <span class="text-brand-dark">$<span x-text="totalPrice.toFixed(2)">0</span></span>
                </div>
                <div class="flex justify-between text-sm text-green-700 font-semibold">
                    <span>Your Commission ({{ Auth::user()->agent?->commission_rate ?? 10 }}%)</span>
                    <span>$<span x-text="commission.toFixed(2)">0</span></span>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-brand-dark text-white font-semibold py-2.5 rounded-xl hover:bg-brand-dark/90 transition text-sm">
                    Create Booking
                </button>
                <a href="{{ route('agent.bookings.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function bookingForm() {
        return {
            safariId: '{{ old('safari_package_id', '') }}',
            numPeople: {{ old('num_people', 1) }},
            pricePerPerson: 0,
            totalPrice: 0,
            commission: 0,
            commissionRate: {{ Auth::user()->agent?->commission_rate ?? 10 }},

            updatePrice() {
                const select = document.querySelector('[name="safari_package_id"]');
                const option = select.options[select.selectedIndex];
                this.pricePerPerson = parseFloat(option?.dataset?.price ?? 0) || 0;
                this.totalPrice = this.pricePerPerson * (this.numPeople || 1);
                this.commission = this.totalPrice * (this.commissionRate / 100);
            },

            init() {
                this.$nextTick(() => this.updatePrice());
            }
        }
    }
</script>
@endpush
@endsection
