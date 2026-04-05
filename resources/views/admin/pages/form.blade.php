<x-app-layout>
    <x-slot name="header">{{ $page ? 'Edit Page: ' . ($page->title['en'] ?? '') : 'Add New Page' }}</x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div x-data="pageBuilder()" x-init="init()">
    <form method="POST"
          action="{{ $page ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($page) @method('PUT') @endif

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pages.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </a>
                <p class="text-sm text-gray-500">Drag sections to reorder. Toggle visibility. Click to expand &amp; edit.</p>
            </div>
            <div class="flex items-center gap-2">
                @if($page)
                    <a href="{{ $page->liveUrl('en') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        View Page
                    </a>
                @endif
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    {{ $page ? 'Save Page' : 'Create Page' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

            {{-- ====== LEFT COLUMN: Title + Sections ====== --}}
            <div class="xl:col-span-3 space-y-6">

                {{-- Title (Multilingual) --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-5">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Page Title</h3>
                        @if(view()->exists('admin.partials.auto-translate-button'))
                            @include('admin.partials.auto-translate-button')
                        @endif
                    </div>
                    <div class="flex gap-1 mb-4 border-b border-gray-100">
                        @foreach($locales as $code)
                            <button type="button" @click="langTab = '{{ $code }}'"
                                    :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="flex items-center gap-1.5 px-4 py-2.5 text-sm border-b-2 transition">{{ strtoupper($code) }}</button>
                        @endforeach
                    </div>
                    @foreach($locales as $code)
                        <div x-show="langTab === '{{ $code }}'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Title ({{ strtoupper($code) }}) @if($code === 'en') <span class="text-red-500">*</span> @endif
                                </label>
                                <input type="text" name="title[{{ $code }}]"
                                       value="{{ old("title.{$code}", $page ? ($page->title[$code] ?? '') : '') }}"
                                       placeholder="Page title in {{ strtoupper($code) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm"
                                       @if($code === 'en') required @endif>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ====== SECTIONS BUILDER ====== --}}
                <div class="space-y-4">
                    <template x-for="(section, idx) in sections" :key="section._key">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200"
                             draggable="true"
                             @dragstart="dragStart($event, idx)"
                             @dragover.prevent="dragOver($event, idx)"
                             @drop="drop($event, idx)">

                            {{-- Section header --}}
                            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 cursor-move"
                                 :class="!section.is_active && 'opacity-60'">
                                {{-- Drag handle --}}
                                <svg class="w-4 h-4 text-gray-300 shrink-0 cursor-grab" fill="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="5" r="1.5"/><circle cx="15" cy="5" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="19" r="1.5"/><circle cx="15" cy="19" r="1.5"/></svg>

                                {{-- Section type badge --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#083321]/10 text-[#083321]"
                                      x-text="sectionLabel(section.section_type)"></span>

                                {{-- Preview text --}}
                                <span class="flex-1 text-sm text-gray-500 truncate"
                                      x-text="sectionPreview(section)"></span>

                                {{-- Hidden inputs --}}
                                <input type="hidden" :name="'sections['+idx+'][id]'" :value="section.id || ''">
                                <input type="hidden" :name="'sections['+idx+'][section_type]'" :value="section.section_type">

                                {{-- Controls: [Edit] [Delete] [Toggle] [Expand] --}}
                                <div class="flex items-center gap-1.5 shrink-0">
                                    {{-- Edit button --}}
                                    <button type="button" @click.stop="section._open = true"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-[#083321] hover:bg-[#083321]/5 transition"
                                            title="Edit section">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                    </button>

                                    {{-- Delete button --}}
                                    <button type="button" @click.stop="confirmDeleteSection(idx)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Delete section">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    {{-- Divider --}}
                                    <div class="w-px h-5 bg-gray-200 mx-0.5"></div>

                                    {{-- Active toggle --}}
                                    <label class="relative inline-flex items-center cursor-pointer" @click.stop title="Toggle visibility">
                                        <input type="checkbox" class="sr-only peer" :checked="section.is_active" @change="section.is_active = $event.target.checked">
                                        <input type="hidden" :name="'sections['+idx+'][is_active]'" :value="section.is_active ? '1' : ''">
                                        <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#083321] after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                                    </label>

                                    {{-- Expand/Collapse arrow --}}
                                    <button type="button" @click.stop="section._open = !section._open" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                                        <svg class="w-5 h-5 transition-transform duration-200" :class="section._open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
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

                                {{-- ═══════ HERO SLIDER ═══════ --}}
                                <template x-if="section.section_type === 'hero'">
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Autoplay</label>
                                                <select :name="'sections['+idx+'][data][slider_autoplay]'" x-model="section.data.slider_autoplay"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="1">Yes</option><option value="0">No</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Interval (ms)</label>
                                                <input type="number" :name="'sections['+idx+'][data][slider_interval]'" x-model="section.data.slider_interval"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]" placeholder="5000">
                                            </div>
                                        </div>

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
                                                        <div x-show="slide._open" x-collapse class="p-4 space-y-4">
                                                            <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][id]'" :value="slide.id || ''">
                                                            <template x-for="loc in locales" :key="'slide-'+sIdx+'-'+loc">
                                                                <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                                    <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Label (' + loc.toUpperCase() + ')'"></label>
                                                                        <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][label]['+loc+']'" x-model="slide.label[loc]" placeholder="e.g. New: Private Jet Journey" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                    <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Title (' + loc.toUpperCase() + ')'"></label>
                                                                        <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][title]['+loc+']'" x-model="slide.title[loc]" placeholder="Main heading text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                    <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subtitle (' + loc.toUpperCase() + ')'"></label>
                                                                        <textarea :name="'sections['+idx+'][slides]['+sIdx+'][subtitle]['+loc+']'" x-model="slide.subtitle[loc]" rows="2" placeholder="Supporting text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea></div>
                                                                    <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Button Text (' + loc.toUpperCase() + ')'"></label>
                                                                        <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][button_text]['+loc+']'" x-model="slide.button_text[loc]" placeholder="Learn More" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                    <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Next Up Text (' + loc.toUpperCase() + ')'"></label>
                                                                        <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][next_up_text]['+loc+']'" x-model="slide.next_up_text[loc]" placeholder="Coming This June" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                </div>
                                                            </template>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1">Button Link</label>
                                                                    <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][button_link]'" x-model="slide.button_link" placeholder="/en/safaris" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
                                                                    <div class="flex items-center gap-2">
                                                                        <input type="color" :name="'sections['+idx+'][slides]['+sIdx+'][bg_color]'" x-model="slide.bg_color" class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                                                        <input type="text" x-model="slide.bg_color" placeholder="#083321" class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                                    </div></div>
                                                            </div>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1">Featured Image</label>
                                                                    <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][image]'" x-model="slide.image">
                                                                    <div class="flex items-center gap-2">
                                                                        <div x-show="slide.image" class="relative group shrink-0">
                                                                            <img :src="'/storage/' + slide.image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                                            <button type="button" @click="slide.image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                                        </div>
                                                                        <button type="button" @click="openPicker(idx, 'slide_image', sIdx)" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Select</button>
                                                                        <button type="button" @click="uploadFor(idx, 'slide_image', sIdx)" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload</button>
                                                                    </div></div>
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1">Background Image</label>
                                                                    <input type="hidden" :name="'sections['+idx+'][slides]['+sIdx+'][bg_image]'" x-model="slide.bg_image">
                                                                    <div class="flex items-center gap-2">
                                                                        <div x-show="slide.bg_image" class="relative group shrink-0">
                                                                            <img :src="'/storage/' + slide.bg_image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                                            <button type="button" @click="slide.bg_image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                                        </div>
                                                                        <button type="button" @click="openPicker(idx, 'slide_bg', sIdx)" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Select</button>
                                                                        <button type="button" @click="uploadFor(idx, 'slide_bg', sIdx)" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload</button>
                                                                    </div></div>
                                                            </div>
                                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Image Alt Text (SEO)</label>
                                                                <input type="text" :name="'sections['+idx+'][slides]['+sIdx+'][image_alt]'" x-model="slide.image_alt" placeholder="Descriptive alt text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <div x-show="section.slides.length === 0" class="text-center py-8 text-gray-400 text-sm">No slides yet. Click "Add Slide" to create one.</div>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ SPLIT HERO ═══════ --}}
                                <template x-if="section.section_type === 'split_hero'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'sh-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'" x-model="section.data.subheading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Body (' + loc.toUpperCase() + ')'"></label>
                                                    <textarea :name="'sections['+idx+'][data][body]['+loc+']'" x-model="section.data.body[loc]" rows="4" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Button Text (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][button_text]['+loc+']'" x-model="section.data.button_text[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Button URL</label>
                                                <input type="text" :name="'sections['+idx+'][data][button_url]'" x-model="section.data.button_url" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Layout</label>
                                                <select :name="'sections['+idx+'][data][layout]'" x-model="section.data.layout" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="image_right">Text Left / Image Right</option><option value="image_left">Image Left / Text Right</option>
                                                </select></div>
                                        </div>
                                        <div><label class="block text-xs font-medium text-gray-600 mb-1">Hero Image</label>
                                            <input type="hidden" :name="'sections['+idx+'][data][image]'" x-model="section.data.image">
                                            <div class="flex items-center gap-2">
                                                <div x-show="section.data.image" class="relative group shrink-0">
                                                    <img :src="'/storage/' + section.data.image" class="w-24 h-16 object-cover rounded-lg border" loading="lazy" alt="">
                                                    <button type="button" @click="section.data.image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                </div>
                                                <button type="button" @click="openPicker(idx, 'section_image')" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Select</button>
                                                <button type="button" @click="uploadFor(idx, 'section_image')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload</button>
                                            </div></div>
                                        <div><label class="block text-xs font-medium text-gray-600 mb-1">Overlay Color</label>
                                            <div class="flex items-center gap-2">
                                                <input type="color" :name="'sections['+idx+'][data][bg_color]'" x-model="section.data.bg_color" class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                                <input type="text" x-model="section.data.bg_color" placeholder="#083321" class="w-32 px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                            </div></div>
                                    </div>
                                </template>

                                {{-- ═══════ DYNAMIC DATA SECTIONS ═══════ --}}
                                <template x-if="['featured_safaris','destinations','testimonials','blog','safari_list','safari_grid','destination_showcase','testimonial_slider'].includes(section.section_type)">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'dyn-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'" x-model="section.data.subheading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Number to Show</label>
                                                <input type="number" :name="'sections['+idx+'][data][count]'" x-model="section.data.count" min="1" max="20" placeholder="6" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            <div x-show="['featured_safaris','safari_grid'].includes(section.section_type)">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Featured Only</label>
                                                <select :name="'sections['+idx+'][data][featured_only]'" x-model="section.data.featured_only" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="1">Yes</option><option value="0">No — Show All Published</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div x-show="section.section_type === 'safari_grid'" class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Columns</label>
                                                <select :name="'sections['+idx+'][data][columns]'" x-model="section.data.columns" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                                </select></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Filter by Category</label>
                                                <input type="text" :name="'sections['+idx+'][data][category_filter]'" x-model="section.data.category_filter" placeholder="Leave blank for all" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                        </div>
                                        <div x-show="section.section_type === 'testimonial_slider'" class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Show Rating</label>
                                                <select :name="'sections['+idx+'][data][show_rating]'" x-model="section.data.show_rating" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="1">Yes</option><option value="0">No</option>
                                                </select></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Autoplay</label>
                                                <select :name="'sections['+idx+'][data][slider_autoplay]'" x-model="section.data.slider_autoplay" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="1">Yes</option><option value="0">No</option>
                                                </select></div>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ WHY CHOOSE US ═══════ --}}
                                <template x-if="section.section_type === 'why_choose_us'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'wcu-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'" x-model="section.data.subheading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-sm font-semibold text-gray-800">Feature Items</h4>
                                                <button type="button" @click="addWhyItem(idx)" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-[#FEBC11]/10 text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
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
                                                        <div class="mb-3">
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                                                            <select :name="'sections['+idx+'][data][items]['+iIdx+'][icon]'" x-model="item.icon" class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                                <option value="shield">Shield</option><option value="star">Star</option><option value="heart">Heart</option><option value="globe">Globe</option><option value="users">Users</option><option value="clock">Clock</option><option value="map">Map</option><option value="camera">Camera</option>
                                                            </select>
                                                        </div>
                                                        <template x-for="loc in locales" :key="'wcu-item-'+iIdx+'-'+loc">
                                                            <div x-show="(section._lang || 'en') === loc" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Title (' + loc.toUpperCase() + ')'"></label>
                                                                    <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][title]['+loc+']'" x-model="item.title[loc]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Description (' + loc.toUpperCase() + ')'"></label>
                                                                    <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][description]['+loc+']'" x-model="item.description[loc]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ ICON FEATURES ═══════ --}}
                                <template x-if="section.section_type === 'icon_features'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'if-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'" x-model="section.data.subheading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Columns</label>
                                                <select :name="'sections['+idx+'][data][columns]'" x-model="section.data.columns" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="3">3</option><option value="4">4</option><option value="6">6</option>
                                                </select></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" :name="'sections['+idx+'][data][bg_color]'" x-model="section.data.bg_color" class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                                    <input type="text" x-model="section.data.bg_color" class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                </div></div>
                                        </div>
                                        <div class="pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-sm font-semibold text-gray-800">Feature Items</h4>
                                                <button type="button" @click="addWhyItem(idx)" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-[#FEBC11]/10 text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg> Add Item
                                                </button>
                                            </div>
                                            <div class="space-y-3">
                                                <template x-for="(item, iIdx) in (section.data.items || [])" :key="'if-item-'+iIdx">
                                                    <div class="border border-gray-200 rounded-lg p-4 bg-white relative">
                                                        <button type="button" @click="section.data.items.splice(iIdx, 1)" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                        <div class="mb-3">
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                                                            <select :name="'sections['+idx+'][data][items]['+iIdx+'][icon]'" x-model="item.icon" class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                                <option value="shield">Shield</option><option value="star">Star</option><option value="heart">Heart</option><option value="globe">Globe</option><option value="users">Users</option><option value="clock">Clock</option><option value="map">Map</option><option value="camera">Camera</option><option value="check">Check</option><option value="trophy">Trophy</option><option value="sparkle">Sparkle</option><option value="phone">Phone</option>
                                                            </select>
                                                        </div>
                                                        <template x-for="loc in locales" :key="'if-item-'+iIdx+'-'+loc">
                                                            <div x-show="(section._lang || 'en') === loc" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Title (' + loc.toUpperCase() + ')'"></label>
                                                                    <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][title]['+loc+']'" x-model="item.title[loc]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Description (' + loc.toUpperCase() + ')'"></label>
                                                                    <input type="text" :name="'sections['+idx+'][data][items]['+iIdx+'][description]['+loc+']'" x-model="item.description[loc]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ CTA / CTA BANNER ═══════ --}}
                                <template x-if="['cta','cta_banner'].includes(section.section_type)">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'cta-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Subheading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][subheading]['+loc+']'" x-model="section.data.subheading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Button Text (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][button_text]['+loc+']'" x-model="section.data.button_text[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Button URL</label>
                                                <input type="text" :name="'sections['+idx+'][data][button_url]'" x-model="section.data.button_url" placeholder="/en/plan-safari" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" :name="'sections['+idx+'][data][bg_color]'" x-model="section.data.bg_color" class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                                    <input type="text" x-model="section.data.bg_color" placeholder="#083321" class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                </div></div>
                                        </div>
                                        <div><label class="block text-xs font-medium text-gray-600 mb-1">Background Image</label>
                                            <input type="hidden" :name="'sections['+idx+'][data][bg_image]'" x-model="section.data.bg_image">
                                            <div class="flex items-center gap-2">
                                                <div x-show="section.data.bg_image" class="relative group shrink-0">
                                                    <img :src="'/storage/' + section.data.bg_image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                    <button type="button" @click="section.data.bg_image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                </div>
                                                <button type="button" @click="openPicker(idx, 'bg_image')" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Select</button>
                                                <button type="button" @click="uploadFor(idx, 'bg_image')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload</button>
                                            </div></div>
                                    </div>
                                </template>

                                {{-- ═══════ TEXT BLOCK ═══════ --}}
                                <template x-if="section.section_type === 'text'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'txt-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Body (' + loc.toUpperCase() + ')'"></label>
                                                    <textarea :name="'sections['+idx+'][data][body]['+loc+']'" x-model="section.data.body[loc]" rows="5" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- ═══════ IMAGE + TEXT ═══════ --}}
                                <template x-if="section.section_type === 'image_text'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'it-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Body (' + loc.toUpperCase() + ')'"></label>
                                                    <textarea :name="'sections['+idx+'][data][body]['+loc+']'" x-model="section.data.body[loc]" rows="4" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Image</label>
                                                <input type="hidden" :name="'sections['+idx+'][data][image]'" x-model="section.data.image">
                                                <div class="flex items-center gap-2">
                                                    <div x-show="section.data.image" class="relative group shrink-0">
                                                        <img :src="'/storage/' + section.data.image" class="w-20 h-14 object-cover rounded-lg border" alt="">
                                                        <button type="button" @click="section.data.image = ''" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                    </div>
                                                    <button type="button" @click="openPicker(idx, 'section_image')" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Select</button>
                                                    <button type="button" @click="uploadFor(idx, 'section_image')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload</button>
                                                </div></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Layout</label>
                                                <select :name="'sections['+idx+'][data][layout]'" x-model="section.data.layout" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="image_left">Image Left</option><option value="image_right">Image Right</option>
                                                </select></div>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ GALLERY / IMAGE GALLERY ═══════ --}}
                                <template x-if="['gallery','image_gallery'].includes(section.section_type)">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'gal-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div x-show="section.section_type === 'image_gallery'" class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Layout</label>
                                                <select :name="'sections['+idx+'][data][gallery_layout]'" x-model="section.data.gallery_layout" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="masonry">Masonry</option><option value="grid">Grid</option>
                                                </select></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Lightbox</label>
                                                <select :name="'sections['+idx+'][data][lightbox]'" x-model="section.data.lightbox" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                                    <option value="1">Yes</option><option value="0">No</option>
                                                </select></div>
                                        </div>
                                        <div><label class="block text-xs font-medium text-gray-600 mb-1">Gallery Images</label>
                                            <input type="hidden" :name="'sections['+idx+'][data][images]'" x-model="section.data.images">
                                            <div class="flex flex-wrap gap-2 mb-2" x-show="section.data.images">
                                                <template x-for="(img, gi) in (section.data.images || '').split(',').filter(i => i.trim())" :key="gi">
                                                    <div class="relative group">
                                                        <img :src="'/storage/' + img.trim()" class="w-16 h-16 object-cover rounded-lg border border-gray-200" alt="">
                                                        <button type="button" @click="removeGalleryImage(idx, gi)" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">&times;</button>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="openPicker(idx, 'gallery')" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">Add from Library</button>
                                                <button type="button" @click="uploadFor(idx, 'gallery')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">Upload Images</button>
                                            </div></div>
                                    </div>
                                </template>

                                {{-- ═══════ MAP SECTION ═══════ --}}
                                <template x-if="section.section_type === 'map'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'map-'+loc">
                                            <div x-show="(section._lang || 'en') === loc" class="space-y-3">
                                                <div><label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Heading (' + loc.toUpperCase() + ')'"></label>
                                                    <input type="text" :name="'sections['+idx+'][data][heading]['+loc+']'" x-model="section.data.heading[loc]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></div>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                                                <input type="text" :name="'sections['+idx+'][data][latitude]'" x-model="section.data.latitude" placeholder="-6.3690" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"></div>
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                                                <input type="text" :name="'sections['+idx+'][data][longitude]'" x-model="section.data.longitude" placeholder="34.8888" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-medium text-gray-600 mb-1">Zoom Level</label>
                                                <input type="number" :name="'sections['+idx+'][data][zoom]'" x-model="section.data.zoom" min="1" max="18" placeholder="6" class="w-40 px-3 py-2.5 border border-gray-300 rounded-lg text-sm"></div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Embed URL <span class="text-gray-400 font-normal">(optional — overrides lat/lng)</span></label>
                                            <input type="text" :name="'sections['+idx+'][data][embed_url]'" x-model="section.data.embed_url" placeholder="https://www.google.com/maps/embed?pb=..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                                            <p class="text-xs text-gray-400 mt-1">Paste a Google Maps embed URL. If set, latitude/longitude will be ignored.</p>
                                        </div>
                                    </div>
                                </template>

                                {{-- ═══════ CUSTOM HTML ═══════ --}}
                                <template x-if="section.section_type === 'html'">
                                    <div class="space-y-4">
                                        <template x-for="loc in locales" :key="'html-'+loc">
                                            <div x-show="(section._lang || 'en') === loc">
                                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'HTML (' + loc.toUpperCase() + ')'"></label>
                                                <textarea :name="'sections['+idx+'][data][html]['+loc+']'" x-model="section.data.html[loc]" rows="8" placeholder="<div>Your custom HTML</div>" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"></textarea>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>

                    {{-- Empty state --}}
                    <div x-show="sections.length === 0" class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400 text-sm">
                        <svg class="w-10 h-10 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg>
                        No sections yet. Click "Add Section" below to start building your page.
                    </div>
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
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $page ? 'Save Page' : 'Create Page' }}
                    </button>
                </div>
            </div>

            {{-- ====== RIGHT COLUMN: Page Settings ====== --}}
            <div class="space-y-6">

                {{-- Publish --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">Publish</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="draft" @selected(old('status', $page->status ?? 'draft') === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $page->status ?? 'draft') === 'published')>Published</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="auto-generated-from-title"
                                   @readonly($page?->isSystemPage())
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] {{ $page?->isSystemPage() ? 'bg-gray-50 cursor-not-allowed text-gray-500' : '' }}">
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $page?->isSystemPage() ? 'This core page route is locked to keep the frontend stable.' : 'Leave blank to auto-generate.' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                        </div>

                        {{-- Homepage toggle --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg bg-[#FEBC11]/5 border border-[#FEBC11]/20 cursor-pointer hover:bg-[#FEBC11]/10 transition {{ $page?->isSystemPage() && !($page->is_homepage ?? false) ? 'opacity-70' : '' }}">
                            <input type="hidden" name="is_homepage" value="0">
                            <input type="checkbox" name="is_homepage" value="1"
                                   @checked(old('is_homepage', $page->is_homepage ?? false))
                                   @disabled($page?->isSystemPage() && !($page->is_homepage ?? false))
                                   class="w-4 h-4 rounded border-gray-300 text-[#FEBC11] focus:ring-[#FEBC11]">
                            <div>
                                <span class="text-sm font-semibold text-gray-900 block">Set as Homepage</span>
                                <span class="text-xs text-gray-500">{{ $page?->isSystemPage() && !($page->is_homepage ?? false) ? 'Core listing pages keep their existing routes and cannot become the homepage.' : 'This page will be the site\'s front page' }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Page Settings --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">Page Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Layout</label>
                            <select name="layout" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="full_width" @selected(old('layout', $page->layout ?? 'full_width') === 'full_width')>Full Width</option>
                                <option value="boxed" @selected(old('layout', $page->layout ?? 'full_width') === 'boxed')>Boxed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="bg_color_picker" value="{{ old('bg_color', $page->bg_color ?? '#ffffff') }}"
                                       onchange="document.querySelector('[name=bg_color]').value = this.value"
                                       class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" name="bg_color" value="{{ old('bg_color', $page->bg_color ?? '') }}" placeholder="#ffffff"
                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section Spacing</label>
                            <select name="section_spacing" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="none" @selected(old('section_spacing', $page->section_spacing ?? 'normal') === 'none')>None</option>
                                <option value="compact" @selected(old('section_spacing', $page->section_spacing ?? 'normal') === 'compact')>Compact</option>
                                <option value="normal" @selected(old('section_spacing', $page->section_spacing ?? 'normal') === 'normal')>Normal</option>
                                <option value="wide" @selected(old('section_spacing', $page->section_spacing ?? 'normal') === 'wide')>Wide</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                            <select name="template" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="default" @selected(old('template', $page->template ?? 'default') === 'default')>Default</option>
                                <option value="full_width" @selected(old('template', $page->template ?? 'default') === 'full_width')>Full Width</option>
                                <option value="sidebar" @selected(old('template', $page->template ?? 'default') === 'sidebar')>With Sidebar</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">SEO</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta[description]" rows="3" placeholder="Page meta description"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('meta.description', $page->meta['description'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                            <input type="text" name="meta[keywords]" value="{{ old('meta.keywords', $page->meta['keywords'] ?? '') }}" placeholder="safari, tanzania, tours"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                        </div>
                    </div>
                </div>
            </div>

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

    <input type="file" x-ref="fileUpload" accept="image/*" class="hidden" @change="handleFileUpload($event)">

    {{-- ====== DELETE CONFIRMATION MODAL ====== --}}
    <div x-show="deleteConfirmOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @keydown.escape.window="deleteConfirmOpen = false">
        <div x-show="deleteConfirmOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden"
             @click.outside="deleteConfirmOpen = false">
            <div class="p-6">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Section</h3>
                <p class="text-sm text-gray-500 text-center mb-1">Are you sure you want to delete this section?</p>
                <p class="text-sm text-gray-500 text-center" x-show="deleteTargetIdx !== null">
                    <span class="font-semibold text-gray-700" x-text="deleteTargetIdx !== null ? sectionLabel(sections[deleteTargetIdx]?.section_type) : ''"></span>
                    will be permanently removed.
                </p>
                <p class="text-xs text-amber-600 text-center mt-2" x-show="sections.length <= 1">
                    ⚠ This is the only section on this page.
                </p>
            </div>
            <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                <button type="button" @click="deleteConfirmOpen = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button" @click="executeDeleteSection()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                    Delete
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function pageBuilder() {
            return {
                langTab: 'en',
                sections: [],
                locales: @json($locales),
                sectionTypes: @json($sectionTypes),
                dragIdx: null,

                // Delete confirmation
                deleteConfirmOpen: false,
                deleteTargetIdx: null,

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

                    this.sections = (Array.isArray(raw) ? raw : []).map((s, i) => ({
                        ...s,
                        _key: s.id || (Date.now() + i),
                        _open: false,
                        _lang: 'en',
                        data: {
                            heading: emptyLocale(),
                            subheading: emptyLocale(),
                            body: emptyLocale(),
                            button_text: emptyLocale(),
                            html: emptyLocale(),
                            count: 6,
                            featured_only: '1',
                            slider_autoplay: '1',
                            slider_interval: 5000,
                            bg_color: '',
                            bg_image: '',
                            button_url: '',
                            image: '',
                            images: '',
                            layout: 'image_right',
                            latitude: '',
                            longitude: '',
                            zoom: 6,
                            embed_url: '',
                            items: [],
                            columns: '3',
                            category_filter: '',
                            show_rating: '1',
                            gallery_layout: 'masonry',
                            lightbox: '1',
                            ...(typeof s.data === 'object' && s.data !== null ? s.data : {}),
                        },
                        slides: (s.slides || []).map((sl, si) => ({
                            ...sl,
                            _key: sl.id || (Date.now() + i * 100 + si),
                            _open: si === 0,
                            label: { ...emptyLocale(), ...(sl.label || {}) },
                            title: { ...emptyLocale(), ...(sl.title || {}) },
                            subtitle: { ...emptyLocale(), ...(sl.subtitle || {}) },
                            button_text: { ...emptyLocale(), ...(sl.button_text || {}) },
                            next_up_text: { ...emptyLocale(), ...(sl.next_up_text || {}) },
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

                sectionPreview(section) {
                    if (section.section_type === 'hero') return section.slides?.length ? section.slides.length + ' slide(s)' : 'Hero Slider';
                    return section.data?.heading?.en || 'Untitled';
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
                            heading: emptyLocale(), subheading: emptyLocale(), body: emptyLocale(),
                            button_text: emptyLocale(), html: emptyLocale(),
                            count: 6, featured_only: '1', slider_autoplay: '1', slider_interval: 5000,
                            bg_color: '', bg_image: '', button_url: '', image: '', images: '',
                            layout: 'image_right', latitude: '', longitude: '', zoom: 6, embed_url: '', items: [],
                            columns: '3', category_filter: '', show_rating: '1',
                            gallery_layout: 'masonry', lightbox: '1',
                        },
                        slides: [],
                    });
                },

                confirmDeleteSection(idx) {
                    this.deleteTargetIdx = idx;
                    this.deleteConfirmOpen = true;
                },

                executeDeleteSection() {
                    if (this.deleteTargetIdx !== null) {
                        const el = document.querySelectorAll('[x-data="pageBuilder()"] [draggable="true"]')[this.deleteTargetIdx];
                        if (el) {
                            el.style.transition = 'all 300ms ease';
                            el.style.opacity = '0';
                            el.style.transform = 'translateX(20px)';
                            el.style.maxHeight = el.offsetHeight + 'px';
                            setTimeout(() => {
                                el.style.maxHeight = '0';
                                el.style.marginBottom = '0';
                                el.style.paddingTop = '0';
                                el.style.paddingBottom = '0';
                                el.style.overflow = 'hidden';
                            }, 50);
                            setTimeout(() => {
                                this.sections.splice(this.deleteTargetIdx, 1);
                                this.deleteTargetIdx = null;
                            }, 350);
                        } else {
                            this.sections.splice(this.deleteTargetIdx, 1);
                            this.deleteTargetIdx = null;
                        }
                    }
                    this.deleteConfirmOpen = false;
                },

                removeSection(idx) {
                    this.confirmDeleteSection(idx);
                },

                addSlide(sectionIdx) {
                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });
                    this.sections[sectionIdx].slides.push({
                        id: '', _key: Date.now() + Math.random(), _open: true,
                        label: emptyLocale(), title: emptyLocale(), subtitle: emptyLocale(),
                        button_text: emptyLocale(), next_up_text: emptyLocale(),
                        image: '', button_link: '', bg_color: '#083321', bg_image: '', image_alt: '',
                    });
                },

                removeSlide(sectionIdx, slideIdx) {
                    if (confirm('Remove this slide?')) this.sections[sectionIdx].slides.splice(slideIdx, 1);
                },

                addWhyItem(sectionIdx) {
                    const emptyLocale = () => ({ en: '', fr: '', de: '', es: '' });
                    if (!this.sections[sectionIdx].data.items) this.sections[sectionIdx].data.items = [];
                    this.sections[sectionIdx].data.items.push({ icon: 'shield', title: emptyLocale(), description: emptyLocale() });
                },

                removeGalleryImage(sectionIdx, imageIdx) {
                    const sec = this.sections[sectionIdx];
                    const imgs = (sec.data.images || '').split(',').map(s => s.trim()).filter(Boolean);
                    imgs.splice(imageIdx, 1);
                    sec.data.images = imgs.join(', ');
                },

                // ── Drag & Drop ──
                dragStart(e, idx) { this.dragIdx = idx; e.dataTransfer.effectAllowed = 'move'; },
                dragOver(e, idx) { e.dataTransfer.dropEffect = 'move'; },
                drop(e, idx) {
                    if (this.dragIdx === null || this.dragIdx === idx) return;
                    const item = this.sections.splice(this.dragIdx, 1)[0];
                    this.sections.splice(idx, 0, item);
                    this.dragIdx = null;
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

                selectMediaItem(item) { this.mediaSelectedPath = item.path; },

                confirmMedia() {
                    if (!this.mediaSelectedPath) return;
                    const sec = this.sections[this.pickerTargetIdx];
                    const field = this.pickerTargetField;
                    const sIdx = this.pickerTargetSlide;

                    if (field === 'slide_image' && sIdx !== null) sec.slides[sIdx].image = this.mediaSelectedPath;
                    else if (field === 'slide_bg' && sIdx !== null) sec.slides[sIdx].bg_image = this.mediaSelectedPath;
                    else if (field === 'bg_image') sec.data.bg_image = this.mediaSelectedPath;
                    else if (field === 'section_image') sec.data.image = this.mediaSelectedPath;
                    else if (field === 'gallery') {
                        const current = (sec.data.images || '').split(',').map(s => s.trim()).filter(Boolean);
                        current.push(this.mediaSelectedPath);
                        sec.data.images = current.join(', ');
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
                        if (!res.ok) throw new Error('Upload failed');
                        const data = await res.json();
                        const media = data.media || [];
                        if (media.length) {
                            const path = media[0].path;
                            const sec = this.sections[this.pickerTargetIdx];
                            const field = this.pickerTargetField;
                            const sIdx = this.pickerTargetSlide;
                            if (field === 'slide_image' && sIdx !== null) sec.slides[sIdx].image = path;
                            else if (field === 'slide_bg' && sIdx !== null) sec.slides[sIdx].bg_image = path;
                            else if (field === 'bg_image') sec.data.bg_image = path;
                            else if (field === 'section_image') sec.data.image = path;
                            else if (field === 'gallery') {
                                const current = (sec.data.images || '').split(',').map(s => s.trim()).filter(Boolean);
                                current.push(path);
                                sec.data.images = current.join(', ');
                            }
                        }
                    } catch (e) {
                        console.error('Upload error:', e);
                        alert('Upload failed.');
                    } finally {
                        event.target.value = '';
                    }
                },

                async uploadInModal(event) {
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
                        if (!res.ok) throw new Error('Upload failed');
                        await this.fetchMedia();
                    } catch (e) {
                        console.error('Upload error:', e);
                        alert('Upload failed.');
                    } finally {
                        event.target.value = '';
                    }
                },

                async fetchMedia() {
                    try {
                        const url = '/admin/media/json' + (this.mediaSearch ? '?search=' + encodeURIComponent(this.mediaSearch) : '');
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.mediaItems = Array.isArray(data) ? data : (data.data || data.media?.data || []);
                        }
                    } catch (e) {
                        console.error('Fetch media error:', e);
                    }
                },
            };
        }
    </script>
    @endpush
    </div>
</x-app-layout>
