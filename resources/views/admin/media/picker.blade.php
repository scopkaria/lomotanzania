{{-- ============================================================
     Media Picker Component — Reusable across admin forms
     Usage: @include('admin.media.picker', [
         'name'  => 'featured_image',
         'value' => $model->featured_image ?? '',
         'label' => 'Featured Image',
     ])
     ============================================================ --}}

@php
    $fieldName  = $name ?? 'image';
    $fieldValue = $value ?? '';
    $fieldLabel = $label ?? 'Image';
    $pickerMode = $mode ?? 'image';
    $pickerMaxMb = (int) config('uploads.max_upload_mb', 20);
    $pickerNoun = $pickerMode === 'video' ? 'Video' : 'Image';
    $pickerPlural = $pickerMode === 'video' ? 'videos' : 'images';
    $pickerAccept = $pickerMode === 'video'
        ? 'video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov'
        : 'image/*';
@endphp

<div x-data="mediaPicker(@js($fieldValue), { kind: @js($pickerMode), maxMb: {{ $pickerMaxMb }} })" class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">{{ $fieldLabel }}</label>

    {{-- Preview --}}
    <div x-show="currentPath" x-transition class="relative inline-block group">
        <template x-if="isVideoPath(currentPath)">
            <video :src="'/storage/' + currentPath" class="w-44 h-28 object-cover rounded-lg border border-gray-200 shadow-sm bg-[#131414]" controls muted playsinline preload="metadata"></video>
        </template>
        <template x-if="!isVideoPath(currentPath)">
            <img :src="'/storage/' + currentPath"
                 class="w-44 h-28 object-cover rounded-lg border border-gray-200 shadow-sm" alt="Preview">
        </template>
        <button type="button" @click="removeFile()"
                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow hover:bg-red-600 transition opacity-0 group-hover:opacity-100">
            &times;
        </button>
    </div>

    {{-- Hidden input --}}
    <input type="hidden" name="{{ $fieldName }}" :value="currentPath">

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2">
        <button type="button" @click="openLibrary()"
                class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 hover:border-[#FEBC11] transition">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
            Select {{ $pickerNoun }}
        </button>
        <button type="button" @click="$refs.fileUpload.click()"
                class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Upload {{ $pickerNoun }}
        </button>
        <input type="file" x-ref="fileUpload" @change="uploadFile($event)" class="hidden" accept="{{ $pickerAccept }}">
        <span x-show="uploading" class="text-xs text-gray-400 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Uploading…
        </span>
    </div>

    @error($fieldName)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror

    {{-- ═══ Library Modal ═══ --}}
    <div x-show="libraryOpen" x-transition.opacity
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4" style="display:none;">
        <div @click.outside="libraryOpen = false"
             class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">

            {{-- Modal Header --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-gray-900 text-base">Media Library</h3>
                <button type="button" @click="libraryOpen = false"
                        class="p-1 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Upload + Search Bar --}}
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3 shrink-0">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="currentPage = 1; libraryItems = []; fetchMedia()"
                           placeholder="Search {{ $pickerPlural }}…"
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]">
                </div>
                <button type="button" @click="$refs.modalUpload.click()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11] text-[#131414] text-xs font-bold rounded-lg hover:bg-yellow-400 transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Upload
                </button>
                <input type="file" x-ref="modalUpload" @change="uploadFromModal($event)" class="hidden" accept="{{ $pickerAccept }}" multiple>
            </div>

            {{-- Grid --}}
            <div class="flex-1 overflow-y-auto p-5">
                <div x-show="modalUploading" class="text-center py-4 text-sm text-gray-500">
                    <svg class="w-5 h-5 animate-spin mx-auto mb-2 text-[#FEBC11]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Uploading…
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                    <template x-for="item in libraryItems" :key="item.id">
                        <button type="button" @click="selectItem(item)"
                                class="aspect-square rounded-lg overflow-hidden border-2 transition hover:shadow-md focus:outline-none"
                                :class="selectedId === item.id ? 'border-[#FEBC11] ring-2 ring-[#FEBC11]/30 shadow-md' : 'border-transparent hover:border-gray-200'">
                            <template x-if="isVideoItem(item)">
                                <div class="relative h-full w-full bg-[#131414]">
                                    <video :src="'/storage/' + item.path" class="h-full w-full object-cover" muted playsinline preload="metadata"></video>
                                    <span class="absolute bottom-2 right-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-[#131414]">Video</span>
                                </div>
                            </template>
                            <template x-if="!isVideoItem(item)">
                                <img :src="'/storage/' + item.path" :alt="item.alt_text || item.filename"
                                     class="w-full h-full object-cover">
                            </template>
                        </button>
                    </template>
                </div>
                <div x-show="libraryItems.length === 0 && !modalUploading" class="text-center py-12 text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                    No {{ $pickerPlural }} found. Upload one!
                </div>
                {{-- Load More --}}
                <div x-show="currentPage < lastPage" class="text-center pt-4">
                    <button type="button" @click="loadMore()" :disabled="loadingMore"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-[#131414] bg-[#FEBC11]/20 rounded-lg hover:bg-[#FEBC11]/40 transition disabled:opacity-50">
                        <template x-if="loadingMore">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </template>
                        Load More
                    </button>
                    <p class="text-xs text-gray-400 mt-1" x-text="'Showing ' + libraryItems.length + ' of ' + totalItems"></p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between shrink-0">
                <p class="text-xs text-gray-400" x-show="selectedId">
                    Selected: <span class="font-medium text-gray-600" x-text="selectedFilename"></span>
                </p>
                <div class="flex gap-2 ml-auto">
                    <button type="button" @click="libraryOpen = false"
                            class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="button" @click="confirmSelection()" :disabled="!selectedId"
                            class="px-4 py-2 bg-[#FEBC11] text-[#131414] text-sm font-bold rounded-lg hover:bg-yellow-400 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Use {{ $pickerNoun }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaPicker', (initialValue, options = {}) => ({
        currentPath: initialValue || '',
        kind: options.kind || 'all',
        maxMb: options.maxMb || 20,
        libraryOpen: false,
        libraryItems: [],
        searchQuery: '',
        selectedId: null,
        selectedPath: '',
        selectedFilename: '',
        uploading: false,
        modalUploading: false,
        currentPage: 1,
        lastPage: 1,
        totalItems: 0,
        loadingMore: false,

        removeFile() {
            this.currentPath = '';
        },

        openLibrary() {
            this.libraryOpen = true;
            this.selectedId = null;
            this.selectedPath = '';
            this.selectedFilename = '';
            this.currentPage = 1;
            this.libraryItems = [];
            this.fetchMedia();
        },

        async fetchMedia(append = false) {
            try {
                if (append) this.loadingMore = true;
                const params = new URLSearchParams();
                params.append('kind', this.kind);
                params.append('page', append ? this.currentPage : 1);
                if (this.searchQuery) params.append('search', this.searchQuery);
                const res = await fetch('/admin/media/json?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                const items = json.data || json;
                if (append) {
                    this.libraryItems = [...this.libraryItems, ...(Array.isArray(items) ? items : [])];
                } else {
                    this.libraryItems = Array.isArray(items) ? items : [];
                    this.currentPage = 1;
                }
                this.currentPage = json.current_page || this.currentPage;
                this.lastPage    = json.last_page || 1;
                this.totalItems  = json.total || this.libraryItems.length;
            } catch (e) {
                console.error('Failed to load media:', e);
                this.notify('Unable to load the media library right now.', 'error');
            } finally {
                this.loadingMore = false;
            }
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.fetchMedia(true);
            }
        },

        notify(message, type = 'info', duration = 4500) {
            if (window.showLomoToast) {
                window.showLomoToast(message, type, duration);
            }
        },

        isVideoPath(path) {
            return /\.(mp4|webm|mov)$/i.test(path || '');
        },

        isVideoItem(item) {
            return (item?.mime_type || '').startsWith('video/');
        },

        selectItem(item) {
            this.selectedId = item.id;
            this.selectedPath = item.path;
            this.selectedFilename = item.filename;
        },

        confirmSelection() {
            if (this.selectedPath) {
                this.currentPath = this.selectedPath;
                this.libraryOpen = false;
            }
        },

        async uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.uploading = true;
            try {
                const media = await this._doUpload([file]);
                if (media.length) {
                    this.currentPath = media[0].path;
                }
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        },

        async uploadFromModal(event) {
            const files = Array.from(event.target.files);
            if (!files.length) return;
            this.modalUploading = true;
            try {
                await this._doUpload(files);
                await this.fetchMedia();
            } finally {
                this.modalUploading = false;
                event.target.value = '';
            }
        },

        async _doUpload(files) {
            const maxBytes = this.maxMb * 1024 * 1024;
            const oversized = files.find(file => file.size > maxBytes);
            if (oversized) {
                this.notify(`${oversized.name} is too large. Maximum file size is ${this.maxMb}MB.`, 'warning');
                return [];
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const formData = new FormData();
            files.forEach(f => formData.append('files[]', f));
            try {
                const res = await fetch('/admin/media', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!res.ok) {
                    let message = `Upload failed. Files can be up to ${this.maxMb}MB.`;
                    try {
                        const data = await res.json();
                        message = Object.values(data.errors || {}).flat()[0] || data.message || message;
                    } catch (error) {
                        console.error('Upload response parse error:', error);
                    }
                    throw new Error(message);
                }

                const data = await res.json();
                const uploaded = data.media || [];
                if (uploaded.length) {
                    this.notify(`${uploaded.length} ${this.kind === 'video' ? 'video' : 'image'}${uploaded.length === 1 ? '' : 's'} uploaded successfully.`, 'success', 3000);
                }
                return data.media || [];
            } catch (e) {
                console.error('Upload error:', e);
                this.notify(e.message || 'Upload failed. Please try again.', 'error');
                return [];
            }
        },
    }));
});
</script>
@endPushOnce
