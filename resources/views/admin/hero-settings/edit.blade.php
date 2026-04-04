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
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">
                    Featured Safaris ({{ $featuredSafaris->count() }})
                </h2>

                @if($featuredSafaris->count())
                    <div class="space-y-2">
                        @foreach($featuredSafaris as $safari)
                            <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                                <span class="text-xs font-bold text-gray-400 w-6 text-center">{{ $safari->featured_order }}</span>
                                @if($safari->featured_image)
                                    <img src="{{ asset('storage/' . $safari->featured_image) }}" alt="" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $safari->title }}</p>
                                    @if($safari->featured_label)
                                        <span class="text-[10px] font-semibold uppercase tracking-wide text-[#FEBC11]">{{ $safari->featured_label }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.safaris.edit', $safari) }}" class="text-xs text-[#083321] hover:underline">Edit</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-400">No featured safaris yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Mark safaris as "Featured" in the safari editor to display them in the hero.</p>
                    </div>
                @endif
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
