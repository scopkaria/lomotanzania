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

            <div class="pt-4 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wide rounded-lg hover:brightness-90 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
