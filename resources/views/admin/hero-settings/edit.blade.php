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

            {{-- Pages to Display Hero --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-2">
                    Pages to Display Hero
                </h2>
                <p class="text-xs text-gray-400 mb-4">Select which pages should show the hero slider. Uncheck a page to hide the hero section on it.</p>

                <div class="space-y-2" x-data="{ selectedPages: @js(old('hero_pages', $heroPageIds)) }">
                    @foreach($pages as $page)
                        @php $pageTitle = is_array($page->title) ? ($page->title['en'] ?? $page->title[array_key_first($page->title)] ?? $page->slug) : $page->title; @endphp
                        <label class="flex items-center gap-4 p-3 rounded-lg cursor-pointer transition-colors"
                               :class="selectedPages.includes({{ $page->id }}) ? 'bg-emerald-50 ring-1 ring-emerald-200' : 'bg-gray-50 hover:bg-gray-100'">
                            <input type="checkbox"
                                   value="{{ $page->id }}"
                                   :checked="selectedPages.includes({{ $page->id }})"
                                   @change="if ($event.target.checked) { selectedPages.push({{ $page->id }}) } else { selectedPages = selectedPages.filter(i => i !== {{ $page->id }}) }"
                                   class="w-4 h-4 rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $page->is_homepage ? 'bg-[#FEBC11]/20 text-[#083321]' : 'bg-gray-200 text-gray-500' }}">
                                    @if($page->is_homepage)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $pageTitle }}</p>
                                    <p class="text-[11px] text-gray-400">/{{ $page->slug }}</p>
                                </div>
                            </div>
                            @if($page->is_homepage)
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-[#083321] bg-[#FEBC11]/30 px-2 py-0.5 rounded-full">Homepage</span>
                            @endif
                        </label>
                    @endforeach

                    {{-- Hidden inputs for form submission --}}
                    <template x-for="id in selectedPages" :key="id">
                        <input type="hidden" name="hero_pages[]" :value="id">
                    </template>
                </div>

                @if($pages->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-400">No published pages found.</p>
                    </div>
                @endif
            </div>

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

            {{-- Hero Slider Safaris --}}
            <div class="bg-white rounded-xl shadow-sm p-6" x-data="heroSafariPicker()">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-2">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                        Hero Slider Safaris
                    </h2>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                          :class="selected.length ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                          x-text="selected.length + ' selected'"></span>
                </div>
                <p class="text-xs text-gray-400 mb-5">Choose which safaris appear in the hero slider and arrange their display order. If none are selected, all featured safaris will show.</p>

                {{-- Selected Safaris (ordered) --}}
                <div class="mb-6" x-show="selected.length > 0" x-collapse>
                    <h3 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        Active Slides (drag to reorder)
                    </h3>
                    <div class="space-y-1.5" x-ref="sortableList">
                        <template x-for="(id, idx) in selected" :key="id">
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-emerald-50 ring-1 ring-emerald-200 group transition-all"
                                 draggable="true"
                                 @dragstart="dragStart($event, idx)"
                                 @dragover.prevent="dragOver($event, idx)"
                                 @dragend="dragEnd()"
                                 :class="dragOverIdx === idx ? 'ring-[#FEBC11] ring-2 bg-amber-50' : ''">
                                {{-- Drag handle --}}
                                <div class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600 px-1" title="Drag to reorder">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-8a2 2 0 10-.001-4.001A2 2 0 0013 6zm0 2a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                                </div>
                                {{-- Order badge --}}
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#083321] text-white text-[10px] font-bold flex items-center justify-center"
                                      x-text="idx + 1"></span>
                                {{-- Thumbnail --}}
                                <template x-if="getSafari(id)?.image">
                                    <img :src="getSafari(id).image" alt="" class="w-12 h-9 rounded-lg object-cover flex-shrink-0">
                                </template>
                                <template x-if="!getSafari(id)?.image">
                                    <div class="w-12 h-9 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                    </div>
                                </template>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="getSafari(id)?.title || 'Unknown'"></p>
                                    <p class="text-[11px] text-gray-500" x-text="getSafari(id)?.meta || ''"></p>
                                </div>
                                {{-- Move up/down --}}
                                <div class="flex flex-col gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="moveUp(idx)" :disabled="idx === 0"
                                            class="w-5 h-5 rounded flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-white disabled:opacity-30 disabled:cursor-not-allowed">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                                    </button>
                                    <button type="button" @click="moveDown(idx)" :disabled="idx === selected.length - 1"
                                            class="w-5 h-5 rounded flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-white disabled:opacity-30 disabled:cursor-not-allowed">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </button>
                                </div>
                                {{-- Remove --}}
                                <button type="button" @click="remove(id)"
                                        class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-red-400 hover:text-white hover:bg-red-500 transition-colors"
                                        title="Remove from slider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Divider --}}
                <div x-show="selected.length > 0" class="border-t border-gray-100 my-4"></div>

                {{-- Available Safaris --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3" x-text="selected.length ? 'Available Safaris' : 'All Published Safaris'"></h3>

                    {{-- Search filter --}}
                    <div class="relative mb-3" x-show="allSafaris.length > 5">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" x-model="search" placeholder="Search safaris..."
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-200 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11] bg-gray-50">
                    </div>

                    <div class="space-y-1.5 max-h-80 overflow-y-auto pr-1">
                        <template x-for="safari in filteredAvailable" :key="safari.id">
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer"
                                 @click="add(safari.id)">
                                {{-- Thumbnail --}}
                                <template x-if="safari.image">
                                    <img :src="safari.image" alt="" class="w-12 h-9 rounded-lg object-cover flex-shrink-0">
                                </template>
                                <template x-if="!safari.image">
                                    <div class="w-12 h-9 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                    </div>
                                </template>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="safari.title"></p>
                                    <p class="text-[11px] text-gray-500" x-text="safari.meta"></p>
                                </div>
                                {{-- Add button --}}
                                <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-emerald-500 hover:text-white hover:bg-emerald-500 transition-colors border border-emerald-200 bg-white"
                                      title="Add to slider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </span>
                            </div>
                        </template>

                        <div x-show="filteredAvailable.length === 0" class="text-center py-6">
                            <p class="text-sm text-gray-400" x-text="search ? 'No safaris match your search.' : 'All safaris are already selected.'"></p>
                        </div>
                    </div>
                </div>

                {{-- Hidden inputs for form submission (preserves order) --}}
                <template x-for="id in selected" :key="'input-'+id">
                    <input type="hidden" name="hero_safari_ids[]" :value="id">
                </template>

                <script>
                function heroSafariPicker() {
                    const allSafaris = @js($allSafaris->map(fn($s) => [
                        'id' => $s->id,
                        'title' => $s->title,
                        'image' => $s->featured_image ? asset('storage/' . $s->featured_image) : null,
                        'slug' => $s->slug,
                        'meta' => trim(($s->duration ? $s->duration . ' days' : '') . ($s->price ? ' · $' . number_format($s->price) : '')),
                    ]));
                    const initialSelected = @js(old('hero_safari_ids', $selectedIds));

                    return {
                        allSafaris,
                        selected: initialSelected.map(Number),
                        search: '',
                        dragIdx: null,
                        dragOverIdx: null,

                        getSafari(id) {
                            return this.allSafaris.find(s => s.id === id);
                        },

                        get filteredAvailable() {
                            const q = this.search.toLowerCase().trim();
                            return this.allSafaris.filter(s =>
                                !this.selected.includes(s.id) &&
                                (!q || s.title.toLowerCase().includes(q))
                            );
                        },

                        add(id) {
                            id = Number(id);
                            if (!this.selected.includes(id)) {
                                this.selected.push(id);
                            }
                        },

                        remove(id) {
                            id = Number(id);
                            this.selected = this.selected.filter(i => i !== id);
                        },

                        moveUp(idx) {
                            if (idx <= 0) return;
                            const arr = [...this.selected];
                            [arr[idx - 1], arr[idx]] = [arr[idx], arr[idx - 1]];
                            this.selected = arr;
                        },

                        moveDown(idx) {
                            if (idx >= this.selected.length - 1) return;
                            const arr = [...this.selected];
                            [arr[idx], arr[idx + 1]] = [arr[idx + 1], arr[idx]];
                            this.selected = arr;
                        },

                        dragStart(e, idx) {
                            this.dragIdx = idx;
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/plain', idx);
                        },

                        dragOver(e, idx) {
                            if (this.dragIdx === null || this.dragIdx === idx) {
                                this.dragOverIdx = null;
                                return;
                            }
                            this.dragOverIdx = idx;
                        },

                        dragEnd() {
                            if (this.dragIdx !== null && this.dragOverIdx !== null && this.dragIdx !== this.dragOverIdx) {
                                const arr = [...this.selected];
                                const [moved] = arr.splice(this.dragIdx, 1);
                                arr.splice(this.dragOverIdx, 0, moved);
                                this.selected = arr;
                            }
                            this.dragIdx = null;
                            this.dragOverIdx = null;
                        },
                    };
                }
                </script>
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
