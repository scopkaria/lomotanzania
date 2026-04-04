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
@endphp

<div x-data="mediaPicker('{{ $fieldValue }}')" class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">{{ $fieldLabel }}</label>

    {{-- Preview --}}
    <div x-show="currentImage" x-transition class="relative inline-block group">
        <img :src="'/storage/' + currentImage"
             class="w-44 h-28 object-cover rounded-lg border border-gray-200 shadow-sm" alt="Preview">
        <button type="button" @click="removeImage()"
                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow hover:bg-red-600 transition opacity-0 group-hover:opacity-100">
            &times;
        </button>
    </div>

    {{-- Hidden input --}}
    <input type="hidden" name="{{ $fieldName }}" :value="currentImage">

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2">
        <button type="button" @click="openLibrary()"
                class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 hover:border-[#FEBC11] transition">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
            Select Image
        </button>
        <button type="button" @click="$refs.fileUpload.click()"
                class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Upload Image
        </button>
        <input type="file" x-ref="fileUpload" @change="uploadFile($event)" class="hidden" accept="image/*">
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
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchMedia()"
                           placeholder="Search images…"
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]">
                </div>
                <button type="button" @click="$refs.modalUpload.click()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11] text-[#131414] text-xs font-bold rounded-lg hover:bg-yellow-400 transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Upload
                </button>
                <input type="file" x-ref="modalUpload" @change="uploadFromModal($event)" class="hidden" accept="image/*" multiple>
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
                            <img :src="'/storage/' + item.path" :alt="item.alt_text || item.filename"
                                 class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
                <div x-show="libraryItems.length === 0 && !modalUploading" class="text-center py-12 text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                    No images found. Upload some!
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
                        Use Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaPicker', (initialValue) => ({
        currentImage: initialValue || '',
        libraryOpen: false,
        libraryItems: [],
        searchQuery: '',
        selectedId: null,
        selectedPath: '',
        selectedFilename: '',
        uploading: false,
        modalUploading: false,

        removeImage() {
            this.currentImage = '';
        },

        openLibrary() {
            this.libraryOpen = true;
            this.selectedId = null;
            this.selectedPath = '';
            this.selectedFilename = '';
            this.fetchMedia();
        },

        async fetchMedia() {
            try {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                const res = await fetch('/admin/media/json?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.libraryItems = data.media?.data || data.media || [];
            } catch (e) {
                console.error('Failed to load media:', e);
            }
        },

        selectItem(item) {
            this.selectedId = item.id;
            this.selectedPath = item.path;
            this.selectedFilename = item.filename;
        },

        confirmSelection() {
            if (this.selectedPath) {
                this.currentImage = this.selectedPath;
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
                    this.currentImage = media[0].path;
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
                if (!res.ok) throw new Error('Upload failed: ' + res.status);
                const data = await res.json();
                return data.media || [];
            } catch (e) {
                console.error('Upload error:', e);
                alert('Upload failed. Please try again.');
                return [];
            }
        },
    }));
});
</script>
@endPushOnce
