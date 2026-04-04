<x-app-layout>
<div class="max-w-2xl space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-brand-dark transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-brand-dark">Booking #{{ $booking->id }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">Created {{ $booking->created_at->format('d M Y, g:i A') }}</p>
        </div>
    </div>

    {{-- Status Update --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <h3 class="font-semibold text-brand-dark mb-4">Booking Status</h3>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="flex items-center gap-4">
            @csrf
            @method('PATCH')
            <select name="status" class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                @foreach(['pending', 'confirmed', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ $booking->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-brand-gold text-brand-dark font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-brand-gold/90 transition">
                Update
            </button>
        </form>
    </div>

    {{-- Agent --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Agent</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 mb-0.5">Name</p><p class="font-medium text-brand-dark">{{ $booking->agent->user->name ?? '—' }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Company</p><p class="font-medium text-brand-dark">{{ $booking->agent->company_name ?? '—' }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Commission Rate</p><p class="font-medium text-brand-dark">{{ $booking->agent->commission_rate }}%</p></div>
        </div>
    </div>

    {{-- Client --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Client</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 mb-0.5">Name</p><p class="font-medium text-brand-dark">{{ $booking->client_name }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Email</p><p class="font-medium text-brand-dark">{{ $booking->client_email }}</p></div>
            @if($booking->client_phone)
            <div><p class="text-gray-400 mb-0.5">Phone</p><p class="font-medium text-brand-dark">{{ $booking->client_phone }}</p></div>
            @endif
        </div>
    </div>

    {{-- Safari & Travel --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Safari & Travel</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="col-span-2"><p class="text-gray-400 mb-0.5">Safari</p><p class="font-medium text-brand-dark">{{ $booking->safari->title ?? '—' }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Travel Date</p><p class="font-medium text-brand-dark">{{ $booking->travel_date->format('d M Y') }}</p></div>
            <div><p class="text-gray-400 mb-0.5">People</p><p class="font-medium text-brand-dark">{{ $booking->num_people }}</p></div>
        </div>
    </div>

    {{-- Financials --}}
    <div class="bg-brand-light rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Financials</p>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Price</span>
                <span class="font-bold text-brand-dark text-base">${{ number_format($booking->total_price, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Agent Commission</span>
                <span class="font-semibold text-green-600">${{ number_format($booking->commission_amount, 2) }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-200 pt-2">
                <span class="text-gray-600 font-medium">Net Revenue</span>
                <span class="font-bold text-brand-dark">${{ number_format($booking->total_price - $booking->commission_amount, 2) }}</span>
            </div>
        </div>
    </div>

    @if($booking->notes)
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
        <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
    </div>
    @endif

</div>
</x-app-layout>
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
        <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
    </div>
    @endif

</div>
</x-app-layout>
