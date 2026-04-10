@extends('layouts.app')

@section('title', __('messages.contact') . ' - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
<style>
    .contact-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .contact-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
    @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: fadeSlideUp 0.6s ease forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="bg-brand-dark py-14 md:py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-15">
        <img src="https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?w=1600&h=500&fit=crop&q=60"
             alt="" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/50 to-brand-dark/90"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <p class="text-kicker tracking-kicker uppercase text-brand-gold mb-3">{{ __('messages.get_in_touch') }}</p>
        <h1 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-4">{{ __('messages.contact') }}</h1>
        <p class="text-white/70 text-base md:text-lg max-w-xl mx-auto">We'd love to hear from you. Whether you have a question about our safaris, pricing, or anything else — our team is ready to help.</p>
    </div>
</section>

@if(isset($sections) && $sections->count())
    @include('partials.render-page-sections', ['sections' => $sections, 'sectionDataMap' => $sectionDataMap ?? []])
@endif

{{-- Info Widgets --}}
<section class="bg-brand-light">
    <div class="max-w-6xl mx-auto px-4 md:px-6 -mt-10 relative z-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Phone --}}
            <div class="contact-card bg-white rounded-lg p-6 text-center animate-in delay-1">
                <div class="w-12 h-12 rounded-full bg-brand-green/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                </div>
                <h3 class="font-heading text-sm font-bold text-brand-dark uppercase tracking-wider mb-1">Phone</h3>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone_number ?? '+255123456789') }}" class="text-sm text-brand-dark/70 hover:text-brand-green transition">{{ $setting->phone_number ?? '+255 123 456 789' }}</a>
            </div>

            {{-- WhatsApp --}}
            <div class="contact-card bg-white rounded-lg p-6 text-center animate-in delay-2">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.75.75 0 00.917.918l4.462-1.495A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.34 0-4.528-.67-6.387-1.826l-.458-.282-2.65.888.886-2.647-.282-.458A9.935 9.935 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                </div>
                <h3 class="font-heading text-sm font-bold text-brand-dark uppercase tracking-wider mb-1">WhatsApp</h3>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp_number ?? '255123456789') }}" target="_blank" rel="noopener" class="text-sm text-brand-dark/70 hover:text-green-600 transition">{{ $setting->whatsapp_number ?? '+255 123 456 789' }}</a>
            </div>

            {{-- Email --}}
            <div class="contact-card bg-white rounded-lg p-6 text-center animate-in delay-3">
                <div class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <h3 class="font-heading text-sm font-bold text-brand-dark uppercase tracking-wider mb-1">Email</h3>
                <a href="mailto:{{ $setting->notification_email ?? 'info@lomotanzaniasafari.com' }}" class="text-sm text-brand-dark/70 hover:text-brand-gold transition">{{ $setting->notification_email ?? 'info@lomotanzaniasafari.com' }}</a>
            </div>

            {{-- Hours --}}
            <div class="contact-card bg-white rounded-lg p-6 text-center animate-in delay-4">
                <div class="w-12 h-12 rounded-full bg-brand-dark/5 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-brand-dark/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-heading text-sm font-bold text-brand-dark uppercase tracking-wider mb-1">Office Hours</h3>
                <p class="text-sm text-brand-dark/70">Mon – Sat: 8 AM – 6 PM<br>Sunday: Closed</p>
            </div>
        </div>
    </div>
</section>

{{-- Form + Map --}}
<section class="bg-brand-light py-16 md:py-20">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- Contact Form --}}
            <div class="flex-1 scroll-reveal">
                <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">Send Us a Message</h2>
                <p class="text-sm text-brand-dark/60 mb-8">Fill out the form below and we'll get back to you as soon as possible.</p>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-lg text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="form_started_at" value="{{ old('form_started_at', now()->timestamp) }}">
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Leave this field empty</label>
                        <input type="text" name="website" id="website" value="{{ old('website') }}" tabindex="-1" autocomplete="off">
                    </div>

                    @if($errors->has('spam'))
                        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first('spam') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.full_name') ?: 'Full Name' }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                   placeholder="Your name">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email') ?: 'Email' }} <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                   placeholder="you@example.com">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone_with_code') ?: 'Phone' }} <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <select name="country_code" class="w-28 rounded-lg border-gray-300 bg-white shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-3">
                                    @foreach([
                                        '+93' => '🇦🇫 +93', '+355' => '🇦🇱 +355', '+213' => '🇩🇿 +213', '+54' => '🇦🇷 +54',
                                        '+61' => '🇦🇺 +61', '+43' => '🇦🇹 +43', '+880' => '🇧🇩 +880', '+32' => '🇧🇪 +32',
                                        '+55' => '🇧🇷 +55', '+267' => '🇧🇼 +267', '+1' => '🇨🇦/🇺🇸 +1', '+56' => '🇨🇱 +56',
                                        '+86' => '🇨🇳 +86', '+57' => '🇨🇴 +57', '+243' => '🇨🇩 +243', '+506' => '🇨🇷 +506',
                                        '+385' => '🇭🇷 +385', '+420' => '🇨🇿 +420', '+45' => '🇩🇰 +45', '+20' => '🇪🇬 +20',
                                        '+251' => '🇪🇹 +251', '+358' => '🇫🇮 +358', '+33' => '🇫🇷 +33', '+49' => '🇩🇪 +49',
                                        '+233' => '🇬🇭 +233', '+30' => '🇬🇷 +30', '+852' => '🇭🇰 +852', '+36' => '🇭🇺 +36',
                                        '+91' => '🇮🇳 +91', '+62' => '🇮🇩 +62', '+353' => '🇮🇪 +353', '+972' => '🇮🇱 +972',
                                        '+39' => '🇮🇹 +39', '+81' => '🇯🇵 +81', '+254' => '🇰🇪 +254', '+82' => '🇰🇷 +82',
                                        '+965' => '🇰🇼 +965', '+60' => '🇲🇾 +60', '+52' => '🇲🇽 +52', '+212' => '🇲🇦 +212',
                                        '+258' => '🇲🇿 +258', '+31' => '🇳🇱 +31', '+64' => '🇳🇿 +64', '+234' => '🇳🇬 +234',
                                        '+47' => '🇳🇴 +47', '+92' => '🇵🇰 +92', '+51' => '🇵🇪 +51', '+63' => '🇵🇭 +63',
                                        '+48' => '🇵🇱 +48', '+351' => '🇵🇹 +351', '+974' => '🇶🇦 +974', '+40' => '🇷🇴 +40',
                                        '+7' => '🇷🇺 +7', '+250' => '🇷🇼 +250', '+966' => '🇸🇦 +966', '+65' => '🇸🇬 +65',
                                        '+27' => '🇿🇦 +27', '+34' => '🇪🇸 +34', '+46' => '🇸🇪 +46', '+41' => '🇨🇭 +41',
                                        '+886' => '🇹🇼 +886', '+255' => '🇹🇿 +255', '+66' => '🇹🇭 +66', '+90' => '🇹🇷 +90',
                                        '+256' => '🇺🇬 +256', '+971' => '🇦🇪 +971', '+44' => '🇬🇧 +44',
                                        '+84' => '🇻🇳 +84', '+260' => '🇿🇲 +260', '+263' => '🇿🇼 +263',
                                    ] as $code => $label)
                                        <option value="{{ $code }}" @selected(old('country_code', '+255') === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                       placeholder="712 345 678">
                            </div>
                            @error('country_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                   placeholder="How can we help?">
                            @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.message_placeholder') ? 'Message' : 'Message' }} <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="5" required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                  placeholder="Tell us what you have in mind...">{{ old('message') }}</textarea>
                        @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Consent --}}
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent" value="1" required
                               class="rounded border-gray-300 text-brand-green focus:ring-brand-green mt-0.5">
                        <span class="text-sm text-gray-600 leading-relaxed">By submitting this form, you agree to receive updates and special offers from <strong>{{ $siteName ?? 'Lomo Tanzania Safari' }}</strong>.</span>
                    </label>

                    <div>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3.5 bg-brand-dark text-white text-sm font-semibold uppercase tracking-wider hover:bg-brand-gold hover:text-brand-dark transition-all duration-200">
                            Send Message
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Location & Map --}}
            <div class="lg:w-[420px] flex-shrink-0">
                <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm sticky top-[100px]">
                    {{-- Dynamic map from admin settings --}}
                    <div class="aspect-[4/3] bg-gray-100 relative">
                        @php
                            $mapEmbed = $setting->map_embed ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253466.21150476826!2d36.66857905!3d-3.38692895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x18371b8b1237c7a1%3A0xefb65c43a8923926!2sArusha%2C%20Tanzania!5e0!3m2!1sen!2sus!4v1704067200000';
                        @endphp
                        <iframe
                            src="{{ $mapEmbed }}"
                            class="w-full h-full border-0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading text-base font-bold text-brand-dark mb-3">Our Office</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-brand-green mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <p class="text-sm text-brand-dark/70">{{ $setting->office_location ?? 'Arusha, Tanzania' }}</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-brand-green mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone_number ?? '+255123456789') }}" class="text-sm text-brand-dark/70 hover:text-brand-green transition">{{ $setting->phone_number ?? '+255 123 456 789' }}</a>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-brand-green mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                <a href="mailto:{{ $setting->notification_email ?? 'info@lomotanzaniasafari.com' }}" class="text-sm text-brand-dark/70 hover:text-brand-gold transition">{{ $setting->notification_email ?? 'info@lomotanzaniasafari.com' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
