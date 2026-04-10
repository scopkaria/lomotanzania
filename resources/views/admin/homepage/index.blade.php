<x-app-layout>
    <x-slot name="header">Homepage Builder</x-slot>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.homepage.update') }}" enctype="multipart/form-data"
          x-data="homepageBuilder()" x-init="init()">
        @csrf
        @method('PUT')

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">Drag sections to reorder. Toggle visibility. Click to expand &amp; edit.</p>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Homepage
            </button>
        </div>

        {{-- ====== SECTIONS LIST ====== --}}
        <div class="space-y-4 mb-6">
            <template x-for="(section, idx) in sections" :key="section._key">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200"
                     draggable="true"
                     @dragstart="dragStart($event, idx)"
                     @dragover.prevent="dragOver($event, idx)"
                     @drop="drop($event, idx)">

                    {{-- Section header --}}
                    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 cursor-move">
                        {{-- Drag handle --}}
                        <svg class="w-4 h-4 text-gray-300 shrink-0 cursor-grab" fill="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="5" r="1.5"/><circle cx="15" cy="5" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="19" r="1.5"/><circle cx="15" cy="19" r="1.5"/></svg>

                        {{-- Type badge --}}
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#083321]/10 text-[#083321]"
                              x-text="sectionLabel(section.section_type)"></span>

                        {{-- Preview text --}}
                        <span class="flex-1 text-sm text-gray-500 truncate"
                              x-text="section.data?.heading?.en || (section.section_type === 'hero' ? 'Hero Slider' : 'Untitled')"></span>

                        {{-- Hidden inputs --}}
                        <input type="hidden" :name="'sections['+idx+'][id]'" :value="section.id || ''">
                        <input type="hidden" :name="'sections['+idx+'][section_type]'" :value="section.section_type">

                        {{-- Active toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer" @click.stop>
                            <input type="checkbox" class="sr-only peer" :checked="section.is_active" @change="section.is_active = $event.target.checked">
                            <input type="hidden" :name="'sections['+idx+'][is_active]'" :value="section.is_active ? '1' : ''">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#083321] after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>

                        {{-- Expand --}}
                        <button type="button" @click="section._open = !section._open" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5 transition-transform" :class="section._open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        {{-- Delete --}}
                        <button type="button" @click="removeSection(idx)" class="text-red-400 hover:text-red-600 transition">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    {{-- Section body --}}
                    <div x-show="section._open" x-collapse class="p-5">

                        {{-- Language tabs --}}
                        <div class="flex gap-1 mb-5 border-b border-gray-100">
                            <template x-for="loc in locales" :key="loc">
                                <button type="button" @click="section._lang = loc"
                                        :class="(section._lang || 'en') === loc ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                        class="px-3 py-2 text-xs border-b-2 transition uppercase" x-text="loc"></button>
                            </template>
                        </div>

                        {{-- ═══════ HERO SECTION ═══════ --}}
                        <template x-if="section.section_type === 'hero'">
                            <div class="space-y-6">
                                {{-- Slider settings --}}
                                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Autoplay</label>
                                        <select :name="'sections['+idx+'][data][slider_autoplay]'"
                                                x-model="section.data.slider_autoplay"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Interval (ms)</label>
                                        <input type="number" :name="'sections['+idx+'][data][slider_interval]'"
                                               x-model="section.data.slider_interval"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                                               placeholder="5000">
                                    </div>
                                </div>

                                {{-- Slides repeater --}}
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-800">Slides</h4>
                                        <button type="button" @click="addSlide(idx)"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-[#FEBC11]/10 text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                            Add Slide
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <template x-for="(slide, sIdx) in section.slides" :key="slide._key">
                                            <div class="border border-gray-200 rounded-lg bg-gray-50/50 overflow-hidden">
                                                {{-- Slide header --}}
                                                <div class="flex items-center gap-2 px-4 py-2.5 bg-white border-b border-gray-100">
                                                    <span class="text-xs font-bold text-gray-400" x-text="'Slide ' + (sIdx+1)"></span>
                                                    <span class="flex-1 text-xs text-gray-400 truncate" x-text="slide.title?.en || ''"></span>
                                                    <button type="button" @click="slide._open = !slide._open" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-4 h-4 transition-transform" :class="slide._open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                    <button type="button" @click="removeSlide(idx, sIdx)" class="text-red-400 hover:text-red-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>

                                                {{-- Slide body --}}
                                                <div x-show="slide._open" x-collapse class="p-4 space-y-4">
                                                    <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][id]'" :value="slide.id || ''">

                                                    {{-- Multilingual fields per locale --}}
                                                    <template x-for="loc in locales" :key="'slide-'+sIdx+'-'+loc">
                                                        <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Label (' + loc.toUpperCase() + ')'"></label>
                                                                <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][label]['+loc+']'"
                                                                       x-model="slide.label[loc]"
                                                                       placeholder="e.g. New: Private Jet Journey"
                                                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Title (' + loc.toUpperCase() + ')'"></label>
                                                                <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][title]['+loc+']'"
                                                                       x-model="slide.title[loc]"
                                                                       placeholder="Main heading text"
                                                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subtitle (' + loc.toUpperCase() + ')'"></label>
                                                                <textarea :name="'sections['+idx+'][slides]['+sIdx+'][subtitle]['+loc+']'"
                                                                          x-model="slide.subtitle[loc]" rows="2"
                                                                          placeholder="Supporting text"
                                                                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Button Text (' + loc.toUpperCase() + ')'"></label>
                                                                <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][button_text]['+loc+']'"
                                                                       x-model="slide.button_text[loc]"
                                                                       placeholder="Learn More"
                                                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Next Up Text (' + loc.toUpperCase() + ')'"></label>
                                                                <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][next_up_text]['+loc+']'"
                                                                       x-model="slide.next_up_text[loc]"
                                                                       placeholder="Coming This June"
                                                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            </div>
                                                        </div>
                                                    </template>

                                                    {{-- Non-locale fields (always visible) --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Button Link</label>
                                                            <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][button_link]'"
                                                                   x-model="slide.button_link" placeholder="/en/safaris"
                                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
                                                            <div class="flex items-center gap-2">
                                                                <input type="color" :name="'sections['+idx+'][slides]['+sIdx+'][bg_color]'"
                                                                       x-model="slide.bg_color"
                                                                       class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                                                <input type="text" x-model="slide.bg_color" placeholder="#083321"
                                                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Image & BG image --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Featured Image</label>
                                                            <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][image]'" x-model="slide.image">
                                                            <div class="flex items-center gap-2">
                                                                <div x-show="slide.image" class="relative group shrink-0">
                                                                    <img :src="'/storage/' + slide.image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                                    <button type="button" @click="slide.image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                                </div>
                                                                <button type="button" @click="openPicker(idx, 'slide_image', sIdx)"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                                                    Select
                                                                </button>
                                                                <button type="button" @click="uploadFor(idx, 'slide_image', sIdx)"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                                                    Upload
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Background Image</label>
                                                            <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][bg_image]'" x-model="slide.bg_image">
                                                            <div class="flex items-center gap-2">
                                                                <div x-show="slide.bg_image" class="relative group shrink-0">
                                                                    <img :src="'/storage/' + slide.bg_image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                                    <button type="button" @click="slide.bg_image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                                </div>
                                                                <button type="button" @click="openPicker(idx, 'slide_bg', sIdx)"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                                                    Select
                                                                </button>
                                                                <button type="button" @click="uploadFor(idx, 'slide_bg', sIdx)"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                                                    Upload
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Image Alt Text (SEO)</label>
                                                        <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][image_alt]'"
                                                               x-model="slide.image_alt" placeholder="Descriptive alt text"
                                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="section.slides.length === 0" class="text-center py-8 text-gray-400 text-sm">
                                        No slides yet. Click "Add Slide" to create one.
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ FEATURED SAFARIS ═══════ --}}
                        <template x-if="section.section_type === 'featured_safaris'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'fs-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="Featured Safari Journeys"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]" placeholder="Explore our curated collection"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>
                                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Number to Show</label>
                                        <input type="number" :name="'sections['+idx+'][data][count]'" x-model="section.data.count"
                                               min="1" max="12" placeholder="6"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Featured Only</label>
                                        <select :name="'sections['+idx+'][data][featured_only]'" x-model="section.data.featured_only"
                                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                            <option value="1">Yes</option>
                                            <option value="0">No — Show All Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ DESTINATIONS ═══════ --}}
                        <template x-if="section.section_type === 'destinations'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'dest-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="Explore Destinations"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]" placeholder="Discover Africa's finest"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Number to Show</label>
                                    <input type="number" :name="'sections['+idx+'][data][count]'" x-model="section.data.count"
                                           min="1" max="20" placeholder="8"
                                           class="w-40 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ WHY CHOOSE US ═══════ --}}
                        <template x-if="section.section_type === 'why_choose_us'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'wcu-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="Why Choose Us"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]" placeholder="What sets us apart"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>

                                {{-- Items repeater --}}
                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-800">Feature Items</h4>
                                        <button type="button" @click="addWhyItem(idx)"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-[#FEBC11]/10 text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                            Add Item
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <template x-for="(item, iIdx) in (section.data.items || [])" :key="'wcu-item-'+iIdx">
                                            <div class="border border-gray-200 rounded-lg p-4 bg-white relative">
                                                <button type="button" @click="section.data.items.splice(iIdx, 1)" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                                                        <select :name="'sections['+idx+'][data][items]['+iIdx+'][icon]'" x-model="item.icon"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                            <option value="shield">Shield</option>
                                                            <option value="star">Star</option>
                                                            <option value="heart">Heart</option>
                                                            <option value="globe">Globe</option>
                                                            <option value="users">Users</option>
                                                            <option value="clock">Clock</option>
                                                            <option value="map">Map</option>
                                                            <option value="camera">Camera</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <template x-for="loc in locales" :key="'wcu-item-'+iIdx+'-'+loc">
                                                    <div x-show="(section._lang || 'en') === loc" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Title (' + loc.toUpperCase() + ')'"></label>
                                                            <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][title]['+loc+']'"
                                                                   x-model="item.title[loc]" placeholder="Feature title"
                                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Description (' + loc.toUpperCase() + ')'"></label>
                                                            <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][description]['+loc+']'"
                                                                   x-model="item.description[loc]" placeholder="Feature description"
                                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ TESTIMONIALS ═══════ --}}
                        <template x-if="section.section_type === 'testimonials'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'test-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="What Our Guests Say"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]" placeholder="Real stories from real travelers"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Number to Show</label>
                                    <input type="number" :name="'sections['+idx+'][data][count]'" x-model="section.data.count"
                                           min="1" max="12" placeholder="3"
                                           class="w-40 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ CALL TO ACTION ═══════ --}}
                        <template x-if="section.section_type === 'cta'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'cta-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="Ready for Your Adventure?"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Button Text (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][button_text]['+loc+']'"
                                                   x-model="section.data.button_text[loc]" placeholder="Plan Your Safari"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Button URL</label>
                                        <input type="text" :name="'sections['+idx+'][data][button_url]'" x-model="section.data.button_url"
                                               placeholder="/en/plan-safari"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
                                        <div class="flex items-center gap-2">
                                            <input type="color" :name="'sections['+idx+'][data][bg_color]'" x-model="section.data.bg_color"
                                                   class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" x-model="section.data.bg_color" placeholder="#083321"
                                                   class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Background Image</label>
                                    <input type="hidden" :name="'sections['+idx+'][data][bg_image]'" x-model="section.data.bg_image">
                                    <div class="flex items-center gap-2">
                                        <div x-show="section.data.bg_image" class="relative group shrink-0">
                                            <img :src="'/storage/' + section.data.bg_image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                            <button type="button" @click="section.data.bg_image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                        </div>
                                        <button type="button" @click="openPicker(idx, 'bg_image')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                            Select
                                        </button>
                                        <button type="button" @click="uploadFor(idx, 'bg_image')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ═══════ LATEST BLOG ═══════ --}}
                        <template x-if="section.section_type === 'blog'">
                            <div class="space-y-4">
                                <template x-for="loc in locales" :key="'blog-'+loc">
                                    <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'"
                                                   x-model="section.data.heading[loc]" placeholder="From Our Blog"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                            <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'"
                                                   x-model="section.data.subheading[loc]" placeholder="Travel tips and stories"
                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                        </div>
                                    </div>
                                </template>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Number to Show</label>
                                    <input type="number" :name="'sections['+idx+'][data][count]'" x-model="section.data.count"
                                           min="1" max="12" placeholder="3"
                                           class="w-40 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                </div>
                            </div>
                        </template>

                    </div>
                </div>
            </template>
        </div>

        {{-- ====== ADD SECTION ====== --}}
        <div class="bg-white rounded-xl shadow-sm p-5" x-data="{ addOpen: false }">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Add Section</h3>
                <button type="button" @click="addOpen = !addOpen"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#083321] text-white text-xs font-semibold rounded-lg hover:bg-[#083321]/90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Section
                </button>
            </div>
            <div x-show="addOpen" x-collapse class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <template x-for="(label, type) in sectionTypes" :key="type">
                    <button type="button" @click="addSection(type); addOpen = false"
                            class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl text-center hover:border-[#FEBC11] hover:bg-[#FEBC11]/5 transition group">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-[#FEBC11] transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-gray-900" x-text="label"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Bottom save --}}
        <div class="flex justify-end mt-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Homepage
            </button>
        </div>
    </form>

    {{-- ====== MEDIA PICKER MODAL ====== --}}
    <div x-show="pickerOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @keydown.escape.window="pickerOpen = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[80vh] flex flex-col" @click.outside="pickerOpen = false">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <h3 class="font-semibold text-gray-900">Media Library</h3>
                <div class="flex items-center gap-3">
                    <input type="text" x-model="mediaSearch" @input.debounce.300ms="fetchMedia()" placeholder="Search..."
                           class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-48 focus:ring-2 focus:ring-[#FEBC11]">
                    <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        Upload
                        <input type="file" accept="image/*" class="hidden" @change="uploadInModal($event)">
                    </label>
                    <button type="button" @click="pickerOpen = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-5">
                <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                    <template x-for="item in mediaItems" :key="item.id">
                        <div @click="selectMediaItem(item)"
                             :class="mediaSelectedPath === item.path ? 'ring-2 ring-[#FEBC11]' : 'hover:ring-2 hover:ring-gray-300'"
                             class="aspect-square rounded-lg overflow-hidden cursor-pointer border border-gray-200 transition">
                            <img :src="'/storage/' + item.path" class="w-full h-full object-cover" :alt="item.alt || ''">
                        </div>
                    </template>
                </div>
                <p x-show="mediaItems.length === 0" class="text-center text-gray-400 py-8 text-sm">No media found.</p>
            </div>
            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t">
                <button type="button" @click="pickerOpen = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="button" @click="confirmMedia()" :disabled="!mediaSelectedPath"
                        class="px-5 py-2 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition disabled:opacity-50">
                    Select Image
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden file input for uploads --}}
    <input type="file" x-ref="fileUpload" accept="image/*" class="hidden" @change="handleFileUpload($event)">

    @push('scripts')
    <script>
        function homepageBuilder() {
            return {
                sections: [],
                locales: @json($locales),
                sectionTypes: @json($sectionTypes),
                dragIdx: null,

                // Media picker
                pickerOpen: false,
                pickerTargetIdx: null,
                pickerTargetField: null,
                pickerTargetSlide: null,
                mediaItems: [],
                mediaSearch: '',
                mediaSelectedPath: '',

                init() {
                    const raw = @json($sectionsJson);

                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });

                    this.sections = raw.map((s, i) => ({
                        ...s,
                        _key: s.id || (Date.now() + i),
                        _open: false,
                        _lang: 'en',
                        data: {
                            heading: emptyLocale(),
                            subheading: emptyLocale(),
                            button_text: emptyLocale(),
                            count: 6,
                            featured_only: '1',
                            slider_autoplay: '1',
                            slider_interval: 5000,
                            bg_color: '',
                            bg_image: '',
                            button_url: '',
                            items: [],
                            ...s.data,
                        },
                        slides: (s.slides || []).map((sl, si) => ({
                            ...sl,
                            _key: sl.id || (Date.now() + i * 100 + si),
                            _open: si === 0,
                            label: { ...emptyLocale(), ...sl.label },
                            title: { ...emptyLocale(), ...sl.title },
                            subtitle: { ...emptyLocale(), ...sl.subtitle },
                            button_text: { ...emptyLocale(), ...sl.button_text },
                            next_up_text: { ...emptyLocale(), ...sl.next_up_text },
                            image: sl.image || '',
                            button_link: sl.button_link || '',
                            bg_color: sl.bg_color || '',
                            bg_image: sl.bg_image || '',
                            image_alt: sl.image_alt || '',
                        })),
                    }));
                },

                sectionLabel(type) {
                    return this.sectionTypes[type] || type;
                },

                addSection(type) {
                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });
                    this.sections.push({
                        id: '',
                        section_type: type,
                        is_active: true,
                        _key: Date.now() + Math.random(),
                        _open: true,
                        _lang: 'en',
                        data: {
                            heading: emptyLocale(),
                            subheading: emptyLocale(),
                            button_text: emptyLocale(),
                            count: 6,
                            featured_only: '1',
                            slider_autoplay: '1',
                            slider_interval: 5000,
                            bg_color: '',
                            bg_image: '',
                            button_url: '',
                            items: [],
                        },
                        slides: [],
                    });
                },

                async removeSection(idx) {
                    const confirmed = await window.showLomoConfirm({
                        title: 'Remove section',
                        message: 'Remove this section?',
                        confirmText: 'Remove section',
                        tone: 'danger',
                    });

                    if (confirmed) {
                        this.sections.splice(idx, 1);
                    }
                },

                addSlide(sectionIdx) {
                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });
                    this.sections[sectionIdx].slides.push({
                        id: '',
                        _key: Date.now() + Math.random(),
                        _open: true,
                        label: emptyLocale(),
                        title: emptyLocale(),
                        subtitle: emptyLocale(),
                        button_text: emptyLocale(),
                        next_up_text: emptyLocale(),
                        image: '',
                        button_link: '',
                        bg_color: '#083321',
                        bg_image: '',
                        image_alt: '',
                    });
                },

                async removeSlide(sectionIdx, slideIdx) {
                    const confirmed = await window.showLomoConfirm({
                        title: 'Remove slide',
                        message: 'Remove this slide?',
                        confirmText: 'Remove slide',
                        tone: 'danger',
                    });

                    if (confirmed) {
                        this.sections[sectionIdx].slides.splice(slideIdx, 1);
                    }
                },

                addWhyItem(sectionIdx) {
                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });
                    if (!this.sections[sectionIdx].data.items) {
                        this.sections[sectionIdx].data.items = [];
                    }
                    this.sections[sectionIdx].data.items.push({
                        icon: 'shield',
                        title: emptyLocale(),
                        description: emptyLocale(),
                    });
                },

                // ── Media ──

                openPicker(sectionIdx, field, slideIdx = null) {
                    this.pickerTargetIdx = sectionIdx;
                    this.pickerTargetField = field;
                    this.pickerTargetSlide = slideIdx;
                    this.mediaSelectedPath = '';
                    this.pickerOpen = true;
                    this.fetchMedia();
                },

                selectMediaItem(item) {
                    this.mediaSelectedPath = item.path;
                },

                confirmMedia() {
                    if (!this.mediaSelectedPath) return;
                    const sec = this.sections[this.pickerTargetIdx];
                    if (this.pickerTargetField === 'slide_image' && this.pickerTargetSlide !== null) {
                        sec.slides[this.pickerTargetSlide].image = this.mediaSelectedPath;
                    } else if (this.pickerTargetField === 'slide_bg' && this.pickerTargetSlide !== null) {
                        sec.slides[this.pickerTargetSlide].bg_image = this.mediaSelectedPath;
                    } else if (this.pickerTargetField === 'bg_image') {
                        sec.data.bg_image = this.mediaSelectedPath;
                    }
                    this.pickerOpen = false;
                },

                uploadFor(sectionIdx, field, slideIdx = null) {
                    this.pickerTargetIdx = sectionIdx;
                    this.pickerTargetField = field;
                    this.pickerTargetSlide = slideIdx;
                    this.$refs.fileUpload.click();
                },

                async handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const formData = new FormData();
                    formData.append('files[]', file);

                    try {
                        const res = await fetch('/admin/media', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: formData,
                        });

                        if (!res.ok) {
                            let message = 'Upload failed. Files can be up to {{ (int) config('uploads.max_upload_mb', 20) }}MB.';
                            try {
                                const data = await res.json();
                                message = Object.values(data.errors || {}).flat()[0] || data.message || message;
                            } catch (error) {
                                console.error('Upload response parse error:', error);
                            }
                            throw new Error(message);
                        }

                        const data = await res.json();
                        const media = data.media || [];
                        if (media.length) {
                            const path = media[0].path;
                            const sec = this.sections[this.pickerTargetIdx];
                            if (this.pickerTargetField === 'slide_image' && this.pickerTargetSlide !== null) {
                                sec.slides[this.pickerTargetSlide].image = path;
                            } else if (this.pickerTargetField === 'slide_bg' && this.pickerTargetSlide !== null) {
                                sec.slides[this.pickerTargetSlide].bg_image = path;
                            } else if (this.pickerTargetField === 'bg_image') {
                                sec.data.bg_image = path;
                            }
                        }
                    } catch (e) {
                        console.error('Upload error:', e);
                        if (window.showLomoToast) {
                            window.showLomoToast(e.message || 'Upload failed.', 'error');
                        }
                    } finally {
                        event.target.value = '';
                    }
                },

                async uploadInModal(event) {
                    const files = Array.from(event.target.files);
                    if (!files.length) return;
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const formData = new FormData();
                    files.forEach(f => formData.append('files[]', f));
                    try {
                        const res = await fetch('/admin/media', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: formData,
                        });

                        if (!res.ok) {
                            let message = 'Upload failed. Files can be up to {{ (int) config('uploads.max_upload_mb', 20) }}MB.';
                            try {
                                const data = await res.json();
                                message = Object.values(data.errors || {}).flat()[0] || data.message || message;
                            } catch (error) {
                                console.error('Upload response parse error:', error);
                            }
                            throw new Error(message);
                        }

                        await this.fetchMedia();
                    } catch (e) {
                        console.error(e);
                        if (window.showLomoToast) {
                            window.showLomoToast(e.message || 'Upload failed.', 'error');
                        }
                    } finally {
                        event.target.value = '';
                    }
                },

                async fetchMedia() {
                    try {
                        const params = new URLSearchParams();
                        if (this.mediaSearch) params.append('search', this.mediaSearch);
                        const res = await fetch('/admin/media/json?' + params.toString(), {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.mediaItems = Array.isArray(data) ? data : (data.media?.data || data.media || data.data || []);
                    } catch (e) {
                        console.error(e);
                    }
                },

                // ── Drag & Drop ──

                dragStart(e, idx) {
                    this.dragIdx = idx;
                    e.dataTransfer.effectAllowed = 'move';
                },
                dragOver(e, idx) {
                    e.dataTransfer.dropEffect = 'move';
                },
                drop(e, idx) {
                    if (this.dragIdx === null || this.dragIdx === idx) return;
                    const item = this.sections.splice(this.dragIdx, 1)[0];
                    this.sections.splice(idx, 0, item);
                    this.dragIdx = null;
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
