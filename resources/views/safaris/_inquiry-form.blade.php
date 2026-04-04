{{-- ========== SAFARI INQUIRY FORM ========== --}}
<div id="inquiry-form" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
     x-data="{
         submitted: {{ session('inquiry_sent') ? 'true' : 'false' }},
         loading: false
     }">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-brand-dark to-brand-green px-6 py-5">
        <h3 class="font-heading text-xl font-bold text-white">Plan Your Safari</h3>
        <p class="text-white/70 text-sm mt-1">Tell us your dream trip — we'll handle the rest</p>
    </div>

    {{-- Success Message --}}
    <div x-show="submitted" x-cloak class="px-6 py-10 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>
        <h4 class="font-heading text-xl font-bold text-brand-dark mb-2">Thank You!</h4>
        <p class="text-gray-600 text-sm leading-relaxed">
            Our safari expert will contact you shortly.<br>
            We typically respond within 24 hours.
        </p>
    </div>

    {{-- Form --}}
    <form x-show="!submitted"
          action="{{ route('inquiries.store') }}" method="POST"
          @submit="loading = true"
          class="px-6 py-6 space-y-4">
        @csrf

        <input type="hidden" name="safari_package_id" value="{{ $safari->id }}">

        {{-- Name --}}
        <div>
            <label for="inq_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" id="inq_name" required
                   value="{{ old('name') }}"
                   class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition"
                   placeholder="John Smith">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="inq_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
            <input type="email" name="email" id="inq_email" required
                   value="{{ old('email') }}"
                   class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition"
                   placeholder="john@example.com">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Phone + Country row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="inq_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                <input type="tel" name="phone" id="inq_phone"
                       value="{{ old('phone') }}"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition"
                       placeholder="+1 (555) 000-0000">
                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="inq_country" class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                <input type="text" name="country" id="inq_country"
                       value="{{ old('country') }}"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition"
                       placeholder="United States">
                @error('country') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Travel Date + People row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="inq_date" class="block text-sm font-medium text-gray-700 mb-1.5">Travel Date</label>
                <input type="date" name="travel_date" id="inq_date"
                       value="{{ old('travel_date') }}"
                       min="{{ now()->format('Y-m-d') }}"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition">
                @error('travel_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="inq_people" class="block text-sm font-medium text-gray-700 mb-1.5">Number of People</label>
                <input type="number" name="number_of_people" id="inq_people" min="1" max="999"
                       value="{{ old('number_of_people') }}"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition"
                       placeholder="2">
                @error('number_of_people') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Message --}}
        <div>
            <label for="inq_message" class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
            <textarea name="message" id="inq_message" rows="3"
                      class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 focus:border-brand-green focus:ring-brand-green/20 transition resize-none"
                      placeholder="Tell us about your ideal safari experience...">{{ old('message') }}</textarea>
            @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                :disabled="loading"
                class="w-full bg-brand-gold text-brand-dark font-bold py-3.5 rounded-xl hover:bg-yellow-400 hover:shadow-lg hover:shadow-brand-gold/20 active:scale-[0.98] transition-all duration-200 text-sm tracking-wide disabled:opacity-60">
            <span x-show="!loading">Start Planning Your Safari</span>
            <span x-show="loading" class="inline-flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Sending…
            </span>
        </button>

        <p class="text-xs text-gray-400 text-center">We never share your details. Response within 24h.</p>
    </form>

    {{-- Quick Contact --}}
    @if(config('services.safari.whatsapp_number') || config('services.safari.call_url'))
        <div class="px-6 pb-6 pt-2 flex items-center justify-center gap-4 border-t border-gray-100 mt-2">
            <span class="text-xs text-gray-400">Or reach us directly:</span>

            @if(config('services.safari.whatsapp_number'))
                <a href="https://wa.me/{{ config('services.safari.whatsapp_number') }}?text={{ urlencode('Hi, I\'m interested in ' . $safari->translated('title')) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-600 hover:text-green-700 transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12.05 21.785h-.01a9.867 9.867 0 01-5.031-1.378l-.361-.214-3.741.981.998-3.648-.235-.374A9.86 9.86 0 012.16 12.04C2.16 6.58 6.58 2.16 12.04 2.16c2.652 0 5.145 1.034 7.021 2.91a9.873 9.873 0 012.9 7.03c-.003 5.46-4.423 9.88-9.881 9.88l-.03.005zM20.52 3.449C18.24 1.245 15.24 0 12.05 0 5.464 0 .104 5.334.101 11.893c-.001 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.585 0 11.946-5.336 11.949-11.896a11.836 11.836 0 00-3.48-8.449z"/></svg>
                    WhatsApp
                </a>
            @endif

            @if(config('services.safari.call_url'))
                <a href="{{ config('services.safari.call_url') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-dark hover:text-brand-gold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    Schedule a Call
                </a>
            @endif
        </div>
    @endif
</div>
