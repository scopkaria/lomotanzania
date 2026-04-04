{{-- ============================================================
     Gallery Picker Component — Multi-image from Media Library
     Usage: @include('admin.media.gallery-picker', [
         'name'     => 'gallery_paths',
         'existing' => $accommodation->images ?? collect(),
         'label'    => 'Gallery Images',
     ])
     ============================================================ --}}

@php
    $fieldName  = $name ?? 'gallery_paths';
    $fieldLabel = $label ?? 'Gallery Images';
    $existingImages = isset($existing) ? $existing->map(fn($img) => [
        'id'   => $img->id,
        'path' => $img->image_path,
    ])->values()->all() : [];
@endphp

<div x-data="galleryPicker({{ json_encode($existingImages) }})" class="space-y-3">
    <label class="block text-sm font-medium text-gray-700">{{ $fieldLabel }}</label>

    {{-- Gallery Grid --}}
    <div x-show="images.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
        <template x-for="(img, idx) in images" :key="img.path + idx">
            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200">
                <img :src="'/storage/' + img.path" class="w-full h-full object-cover" alt="">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition"></div>
                <button type="button" @click="removeImage(idx)"
                        class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow opacity-0 group-hover:opacity-100 transition hover:bg-red-600">
                    &times;
                </button>
                <input type="hidden" :name="'{{ $fieldName }}[]'" :value="img.path">
            </div>
        </template>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2">
        <button type="button" @click="openLibrary()"
                class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-xs font-semibold text-gray-700 rounded-lg hover:bg-gray-50 hover:border-[#FEBC11] transition">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
            Select Images
        </button>
        <button type="button" @click="$refs.galleryUpload.click()"
                class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#FEBC11]/10 text-xs font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Upload Images
        </button>
        <input type="file" x-ref="galleryUpload" @change="uploadFiles($event)" class="hidden" accept="image/*" multiple>
        <span x-show="uploading" class="text-xs text-gray-400 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Uploading…
        </span>
    </div>
    <p class="text-xs text-gray-400">Select or upload multiple images for the gallery.</p>

    {{-- ═══ Library Modal (multi-select) ═══ --}}
    <div x-show="libraryOpen" x-transition.opacity
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4" style="display:none;">
        <div @click.outside="libraryOpen = false"
             class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-gray-900 text-base">Select Gallery Images</h3>
                <button type="button" @click="libraryOpen = false" class="p-1 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3 shrink-0">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchMedia()"
                           placeholder="Search images…"
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]">
                </div>
                <span class="text-xs text-gray-500 font-medium" x-show="selectedPaths.length > 0"
                      x-text="selectedPaths.length + ' selected'"></span>
            </div>

            <div class="flex-1 overflow-y-auto p-5">
                <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                    <template x-for="item in libraryItems" :key="item.id">
                        <button type="button" @click="toggleSelect(item)"
                                class="aspect-square rounded-lg overflow-hidden border-2 transition hover:shadow-md focus:outline-none relative"
                                :class="selectedPaths.includes(item.path) ? 'border-[#FEBC11] ring-2 ring-[#FEBC11]/30 shadow-md' : 'border-transparent hover:border-gray-200'">
                            <img :src="'/storage/' + item.path" :alt="item.alt_text || item.filename" class="w-full h-full object-cover">
                            <div x-show="selectedPaths.includes(item.path)"
                                 class="absolute top-1 right-1 w-5 h-5 bg-[#FEBC11] rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                        </button>
                    </template>
                </div>
                <div x-show="libraryItems.length === 0" class="text-center py-12 text-gray-400 text-sm">No images found.</div>
            </div>

            <div class="px-5 py-3 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                <button type="button" @click="libraryOpen = false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="button" @click="confirmGallerySelection()" :disabled="selectedPaths.length === 0"
                        class="px-4 py-2 bg-[#FEBC11] text-[#131414] text-sm font-bold rounded-lg hover:bg-yellow-400 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Add Selected
                </button>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('galleryPicker', (initial) => ({
        images: initial || [],
        libraryOpen: false,
        libraryItems: [],
        searchQuery: '',
        selectedPaths: [],
        uploading: false,

        removeImage(idx) {
            this.images.splice(idx, 1);
        },

        openLibrary() {
            this.libraryOpen = true;
            this.selectedPaths = [];
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

        toggleSelect(item) {
            const idx = this.selectedPaths.indexOf(item.path);
            if (idx >= 0) {
                this.selectedPaths.splice(idx, 1);
            } else {
                this.selectedPaths.push(item.path);
            }
        },

        confirmGallerySelection() {
            const existingPaths = this.images.map(i => i.path);
            this.selectedPaths.forEach(path => {
                if (!existingPaths.includes(path)) {
                    this.images.push({ id: null, path });
                }
            });
            this.libraryOpen = false;
        },

        async uploadFiles(event) {
            const files = Array.from(event.target.files);
            if (!files.length) return;
            this.uploading = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                const formData = new FormData();
                files.forEach(f => formData.append('files[]', f));
                const res = await fetch('/admin/media', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: formData,
                });
                if (!res.ok) throw new Error('Upload failed');
                const data = await res.json();
                (data.media || []).forEach(m => {
                    this.images.push({ id: null, path: m.path });
                });
            } catch (e) {
                console.error('Upload error:', e);
                alert('Upload failed. Please try again.');
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        },
    }));
});
</script>
@endPushOnce
