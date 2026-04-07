<x-app-layout>
    <x-slot name="header">Site Settings</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Site Name --}}
            <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                <input type="text" name="site_name" id="site_name"
                       value="{{ old('site_name', $setting->site_name) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm"
                       required>
                @error('site_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tagline --}}
            <div>
                <label for="tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                <input type="text" name="tagline" id="tagline"
                       value="{{ old('tagline', $setting->tagline) }}"
                       placeholder="e.g. Less On Ourselves, More On Others"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                @error('tagline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo Upload --}}
            <div>
                @include('admin.media.picker', [
                    'name'  => 'logo',
                    'value' => old('logo', $setting->logo_path ?? ''),
                    'label' => 'Logo',
                ])
            </div>

            {{-- ═══════════ BRAND & APPEARANCE ═══════════ --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Brand & Appearance</h3>
                <p class="text-xs text-gray-400 mb-5">Refine the favicon, logo sizing, and the top header ribbon color without redesigning the site.</p>

                <div class="mb-5">
                    @include('admin.media.picker', [
                        'name'  => 'favicon',
                        'value' => old('favicon', $setting->favicon_path ?? ''),
                        'label' => 'Favicon',
                    ])
                    <p class="mt-1 text-xs text-gray-400">Upload a square site icon. It will be used for browser tabs and mobile shortcuts.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="logo_width" class="block text-sm font-medium text-gray-700 mb-1">Logo Width</label>
                        <input type="range" name="logo_width" id="logo_width" min="110" max="280" step="2"
                               value="{{ old('logo_width', $setting->logo_width ?? 176) }}"
                               oninput="document.getElementById('logo_width_value').textContent = this.value + 'px'"
                               class="w-full accent-[#083321]">
                        <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                            <span>Compact</span>
                            <span id="logo_width_value" class="font-semibold text-[#083321]">{{ old('logo_width', $setting->logo_width ?? 176) }}px</span>
                            <span>Large</span>
                        </div>
                    </div>

                    <div>
                        <label for="header_color" class="block text-sm font-medium text-gray-700 mb-1">Header Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="header_color" id="header_color" value="{{ old('header_color', $setting->header_color ?? '#083321') }}" oninput="document.getElementById('header_color_value').value = this.value" class="h-12 w-16 cursor-pointer rounded-lg border border-gray-300 bg-white p-1">
                            <input type="text" id="header_color_value" value="{{ old('header_color', $setting->header_color ?? '#083321') }}" readonly class="flex-1 rounded-lg border-gray-300 bg-gray-50 text-sm text-gray-600">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">This color is applied to the top ribbon bar for better brand consistency.</p>
                    </div>
                </div>
            </div>

            {{-- ═══════════ SEO SETTINGS ═══════════ --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-1">SEO Settings</h3>
                <p class="text-xs text-gray-400 mb-5">Configure default search engine optimization for your site.</p>

                {{-- Meta Description --}}
                <div class="mb-5">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Default Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" maxlength="500"
                              placeholder="Describe your safari business in 150-160 characters for Google search results..."
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">{{ old('meta_description', $setting->meta_description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Recommended: 150-160 characters.</p>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default OG Image --}}
                <div class="mb-5">
                    @include('admin.media.picker', [
                        'name'  => 'default_og_image',
                        'value' => old('default_og_image', $setting->default_og_image ?? ''),
                        'label' => 'Default Social Share Image (OG Image)',
                    ])
                    <p class="mt-1 text-xs text-gray-400">Recommended: 1200x630px. JPG, PNG, or WebP.</p>
                </div>

                {{-- Google Analytics ID --}}
                <div class="mb-5">
                    <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                    <input type="text" name="google_analytics_id" id="google_analytics_id"
                           value="{{ old('google_analytics_id', $setting->google_analytics_id) }}"
                           placeholder="G-XXXXXXXXXX"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    <p class="mt-1 text-xs text-gray-400">Your Google Analytics 4 measurement ID.</p>
                    @error('google_analytics_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Google Search Console --}}
                <div class="mb-5">
                    <label for="google_search_console" class="block text-sm font-medium text-gray-700 mb-1">Google Search Console Verification</label>
                    <input type="text" name="google_search_console" id="google_search_console"
                           value="{{ old('google_search_console', $setting->google_search_console) }}"
                           placeholder="HTML tag content value"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    <p class="mt-1 text-xs text-gray-400">Paste the content attribute value from the Google verification meta tag.</p>
                    @error('google_search_console')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ═══════════ CONTACT & CHAT SETTINGS ═══════════ --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Contact & Live Chat</h3>
                <p class="text-xs text-gray-400 mb-5">Configure phone, WhatsApp, and live chat for visitors.</p>

                {{-- Phone Number --}}
                <div class="mb-5">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number"
                           value="{{ old('phone_number', $setting->phone_number) }}"
                           placeholder="+255 123 456 789"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    <p class="mt-1 text-xs text-gray-400">Displayed on the website and used for call links.</p>
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WhatsApp Number --}}
                <div class="mb-5">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number"
                           value="{{ old('whatsapp_number', $setting->whatsapp_number) }}"
                           placeholder="+255123456789 (no spaces)"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                    <p class="mt-1 text-xs text-gray-400">WhatsApp link shown in the chat widget. Use international format without spaces.</p>
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TripAdvisor URL --}}
                <div class="mb-5">
                    <label for="tripadvisor_url" class="block text-sm font-medium text-gray-700 mb-1">TripAdvisor URL</label>
                    <input type="url" name="tripadvisor_url" id="tripadvisor_url"
                           value="{{ old('tripadvisor_url', $setting->tripadvisor_url) }}"
                           placeholder="https://www.tripadvisor.com/..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                    <p class="mt-1 text-xs text-gray-400">Used on safari pages to link or embed your TripAdvisor review presence. Static reviews will remain as fallback.</p>
                    @error('tripadvisor_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Chat Greeting --}}
                <div class="mb-5">
                    <label for="chat_greeting" class="block text-sm font-medium text-gray-700 mb-1">Chat Greeting Message</label>
                    <textarea name="chat_greeting" id="chat_greeting" rows="2"
                              placeholder="Hello! How can we help you plan your safari?"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">{{ old('chat_greeting', $setting->chat_greeting) }}</textarea>
                    @error('chat_greeting')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Chat Enabled --}}
                <div class="mb-5 flex items-center gap-3">
                    <input type="hidden" name="chat_enabled" value="0">
                    <input type="checkbox" name="chat_enabled" id="chat_enabled" value="1"
                           {{ old('chat_enabled', $setting->chat_enabled) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <label for="chat_enabled" class="text-sm font-medium text-gray-700">Enable Live Chat Widget</label>
                </div>

                {{-- Notification Sound --}}
                <div class="mb-5 flex items-center gap-3">
                    <input type="hidden" name="notification_sound_enabled" value="0">
                    <input type="checkbox" name="notification_sound_enabled" id="notification_sound_enabled" value="1"
                           {{ old('notification_sound_enabled', $setting->notification_sound_enabled ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                    <label for="notification_sound_enabled" class="text-sm font-medium text-gray-700">Enable Notification Sounds</label>
                </div>

                {{-- Notification Volume --}}
                <div class="mb-5">
                    <label for="notification_sound_volume" class="block text-sm font-medium text-gray-700 mb-1">Notification Volume</label>
                    <select name="notification_sound_volume" id="notification_sound_volume"
                            class="w-full sm:w-48 rounded-lg border-gray-300 text-sm focus:border-brand-gold focus:ring-brand-gold">
                        <option value="low" {{ old('notification_sound_volume', $setting->notification_sound_volume ?? 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('notification_sound_volume', $setting->notification_sound_volume ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('notification_sound_volume', $setting->notification_sound_volume ?? 'medium') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wide rounded-lg hover:brightness-90 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
