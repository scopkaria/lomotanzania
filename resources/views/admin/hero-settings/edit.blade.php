<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Hero Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Configure the homepage hero section background video and slider behaviour.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.pages.hero-settings.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Background Video --}}
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3">
                    Background Video
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Video File</label>
                    @include('admin.media.picker', [
                        'name'  => 'background_video',
                        'value' => old('background_video', $settings->background_video ?? ''),
                        'label' => 'Background Video',
                    ])
                    <p class="text-xs text-gray-400 mt-1">Upload an MP4 video. Recommended: 1920×1080, under 15 MB, short loop (10-30s).</p>
                    @error('background_video') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Video Poster (Fallback Image)</label>
                    @include('admin.media.picker', [
                        'name'  => 'video_poster',
                        'value' => old('video_poster', $settings->video_poster ?? ''),
                        'label' => 'Video Poster',
                    ])
                    <p class="text-xs text-gray-400 mt-1">Shown while the video loads, or on mobile when video is disabled.</p>
                    @error('video_poster') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Overlay & Transitions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3">
                    Display Settings
                </h2>

                <div x-data="{ opacity: {{ old('overlay_opacity', $settings->overlay_opacity ?? 0.50) }} }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Overlay Opacity: <span class="text-[#FEBC11] font-bold" x-text="Math.round(opacity * 100) + '%'"></span>
                    </label>
                    <input type="range" name="overlay_opacity" min="0" max="1" step="0.05"
                           x-model="opacity"
                           class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-[#083321]">
                    <div class="flex justify-between text-[10px] text-gray-400 mt-1">
                        <span>0% (no overlay)</span>
                        <span>100% (solid dark)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slide Transition Speed (ms)</label>
                    <input type="number" name="transition_speed" min="1000" max="30000" step="500"
                           value="{{ old('transition_speed', $settings->transition_speed ?? 5000) }}"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11]">
                    <p class="text-xs text-gray-400 mt-1">How long each safari slide stays visible (in milliseconds). Default: 5000.</p>
                    @error('transition_speed') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="autoplay" value="0">
                    <input type="checkbox" name="autoplay" value="1"
                           @checked(old('autoplay', $settings->autoplay ?? true))
                           class="w-4 h-4 rounded border-gray-300 text-[#FEBC11] focus:ring-[#FEBC11]">
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">Auto-play Slider</span>
                        <span class="text-xs text-gray-400">Automatically cycle through featured safaris</span>
                    </div>
                </label>
            </div>

            {{-- Featured Safaris Preview --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-2">
                    Hero Slider Safaris
                </h2>
                <p class="text-xs text-gray-400 mb-4">Tick the safaris you want to appear in the hero slider. Drag to reorder (top = first slide). If none are selected, all featured safaris will show.</p>

                @if($allSafaris->count())
                    <div class="space-y-2" x-data="{ ids: @js(old('hero_safari_ids', $selectedIds)) }">
                        @foreach($allSafaris as $safari)
                            <label class="flex items-center gap-4 p-3 rounded-lg cursor-pointer transition-colors"
                                   :class="ids.includes({{ $safari->id }}) ? 'bg-emerald-50 ring-1 ring-emerald-200' : 'bg-gray-50 hover:bg-gray-100'">
                                <input type="checkbox"
                                       value="{{ $safari->id }}"
                                       :checked="ids.includes({{ $safari->id }})"
                                       @change="if ($event.target.checked) { ids.push({{ $safari->id }}) } else { ids = ids.filter(i => i !== {{ $safari->id }}) }"
                                       class="w-4 h-4 rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                                @if($safari->featured_image)
                                    <img src="{{ asset('storage/' . $safari->featured_image) }}" alt="" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $safari->title }}</p>
                                </div>
                                <a href="{{ route('admin.safaris.edit', $safari) }}" class="text-xs text-[#083321] hover:underline" @click.stop>Edit</a>
                            </label>
                        @endforeach

                        {{-- Hidden inputs for form submission --}}
                        <template x-for="id in ids" :key="id">
                            <input type="hidden" name="hero_safari_ids[]" :value="id">
                        </template>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-400">No published safaris yet.</p>
                    </div>
                @endif
            </div>

            {{-- CTA Button --}}
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3">
                    Call-to-Action Button
                </h2>
                <p class="text-xs text-gray-400 -mt-2">The button that appears on each slide. Leave blank to link to each safari's own page.</p>

                @php $locales = ['en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch', 'es' => 'Español']; @endphp

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($locales as $code => $label)
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
                                <input type="text" name="button_text[{{ $code }}]"
                                       value="{{ old('button_text.' . $code, $settings->button_text[$code] ?? '') }}"
                                       placeholder="{{ $code === 'en' ? 'Explore Safari' : '' }}"
                                       class="w-full rounded-lg border-gray-300 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11]">
                            </div>
                        @endforeach
                    </div>
                    @error('button_text') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Link (optional)</label>
                    <input type="text" name="button_link"
                           value="{{ old('button_link', $settings->button_link ?? '') }}"
                           placeholder="e.g. /en/safaris  — leave blank to link to each safari"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11]">
                    <p class="text-xs text-gray-400 mt-1">If set, all slides share this link. If blank, each slide links to its own safari page.</p>
                    @error('button_link') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition-colors shadow-sm">
                    Save Hero Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
