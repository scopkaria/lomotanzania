<x-app-layout>
    <x-slot name="header">Media Library</x-slot>

    <div x-data="mediaLibrary(@js($media->pluck('id')->values()), {{ (int) config('uploads.max_upload_mb', 20) }})" x-init="init()">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Media Library</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $media->total() }} file(s) total &middot; Upload and manage images, videos, and files up to {{ (int) config('uploads.max_upload_mb', 20) }}MB each.</p>
            </div>
            <button @click="showUpload = !showUpload"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#FEBC11] text-[#131414] text-sm font-bold rounded-lg hover:bg-yellow-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Upload Files
            </button>
        </div>

        {{-- Bulk action bar --}}
        <div x-show="selected.length > 0" x-transition class="mb-4 flex items-center gap-3 px-4 py-3 bg-[#083321]/5 border border-[#083321]/20 rounded-xl">
            <span class="text-sm font-semibold text-[#083321]" x-text="selected.length + ' selected'"></span>
            <button @click="bulkDelete()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                Delete Selected
            </button>
            <button @click="selected = []" class="text-xs text-gray-500 hover:text-gray-700">Clear Selection</button>
            <label class="ml-auto flex items-center gap-1.5 cursor-pointer">
                <input type="checkbox" @change="toggleSelectAll($event)" :checked="selected.length === allIds.length && allIds.length > 0" class="w-4 h-4 rounded border-gray-300 text-[#FEBC11] focus:ring-[#FEBC11]">
                <span class="text-xs text-gray-600">Select All</span>
            </label>
        </div>

        {{-- Upload area (AJAX with progress) --}}
        <div x-show="showUpload" x-collapse class="mb-6">
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-white hover:border-[#FEBC11] transition"
                 @dragover.prevent="dragOver = true"
                 @dragleave.prevent="dragOver = false"
                 @drop.prevent="handleDrop($event)"
                 :class="dragOver && 'border-[#FEBC11] bg-yellow-50'">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                <p class="text-sm text-gray-500 mb-3">Drag and drop files here, or click to browse. Images, videos, documents, and archives are supported.</p>
                <input type="file" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.mp4,.webm,.mov" class="hidden" x-ref="fileInput" @change="uploadFiles($refs.fileInput.files)">
                <button type="button" @click="$refs.fileInput.click()" class="px-6 py-2 bg-[#083321] text-white text-sm font-bold rounded-lg hover:bg-[#083321]/90 transition">Browse Files</button>
            </div>
            {{-- Upload progress --}}
            <template x-for="(up, ui) in uploads" :key="ui">
                <div class="mt-2 flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-2">
                    <span class="text-xs text-gray-600 truncate max-w-[200px]" x-text="up.name"></span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#FEBC11] rounded-full transition-all" :style="'width:' + up.progress + '%'"></div>
                    </div>
                    <span class="text-xs font-semibold" :class="up.progress === 100 ? 'text-green-600' : 'text-gray-400'" x-text="up.progress + '%'"></span>
                </div>
            </template>
        </div>

        {{-- Search --}}
        <div class="mb-6">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search media..."
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.media.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        {{-- Media grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($media as $item)
                <div class="group relative bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition"
                     :class="selected.includes({{ $item->id }}) && 'ring-2 ring-[#FEBC11]'">
                    {{-- Select checkbox --}}
                    <label class="absolute top-2 left-2 z-10 cursor-pointer" @click.stop>
                        <input type="checkbox" value="{{ $item->id }}" :checked="selected.includes({{ $item->id }})"
                               @change="toggleSelect({{ $item->id }})"
                               class="w-4 h-4 rounded border-gray-300 text-[#FEBC11] focus:ring-[#FEBC11] shadow-sm opacity-0 group-hover:opacity-100 transition"
                               :class="selected.includes({{ $item->id }}) && '!opacity-100'">
                    </label>

                    <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($item->isImage())
                            <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->alt_text ?? $item->filename }}" class="w-full h-full object-cover" loading="lazy">
                        @elseif($item->isVideo())
                            <div class="relative h-full w-full bg-[#131414]">
                                <video src="{{ asset('storage/' . $item->path) }}" class="w-full h-full object-cover" muted playsinline preload="metadata"></video>
                                <span class="absolute bottom-2 right-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-[#131414]">Video</span>
                            </div>
                        @else
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                <p class="text-[10px] text-gray-400 truncate">{{ $item->filename }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Overlay actions --}}
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        <button type="button" @click="openDetail({ id: {{ $item->id }}, url: '{{ asset('storage/' . $item->path) }}', filename: '{{ e($item->filename) }}', alt_text: '{{ e($item->alt_text ?? '') }}', size: '{{ $item->size >= 1048576 ? number_format($item->size / 1048576, 1) . ' MB' : number_format($item->size / 1024, 1) . ' KB' }}', mime: '{{ $item->mime_type }}', path: '{{ $item->path }}', created: '{{ $item->created_at->format('M d, Y') }}' })"
                                class="p-2 bg-white/90 rounded-lg hover:bg-white transition" title="Details">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                        <button type="button" @click="copyPath('{{ $item->path }}')" class="p-2 bg-white/90 rounded-lg hover:bg-white transition" title="Copy path">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                        </button>
                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this file permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 bg-red-500/90 rounded-lg hover:bg-red-500 transition" title="Delete">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>

                    <div class="px-2 py-2">
                        <p class="text-[11px] text-gray-500 truncate">{{ $item->filename }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                    <p class="text-gray-400 text-sm">No media files yet. Upload some!</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination + count --}}
        <div class="mt-6 flex items-center justify-between">
            <p class="text-xs text-gray-400">Showing {{ $media->firstItem() ?? 0 }}&ndash;{{ $media->lastItem() ?? 0 }} of {{ $media->total() }}</p>
            <div>{{ $media->links() }}</div>
        </div>

        {{-- Detail Modal (enhanced) --}}
        <div x-show="detailOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display:none;">
            <div @click.outside="detailOpen = false" class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900">Media Details</h3>
                        <button @click="detailOpen = false" class="p-1 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <template x-if="detailItem.mime && detailItem.mime.startsWith('image/')">
                        <img :src="detailItem.url" :alt="detailItem.alt_text" class="w-full rounded-lg mb-4 max-h-64 object-contain bg-gray-50">
                    </template>

                    <template x-if="detailItem.mime && detailItem.mime.startsWith('video/')">
                        <video :src="detailItem.url" controls class="w-full rounded-lg mb-4 max-h-64 bg-[#131414]"></video>
                    </template>

                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-xs font-medium text-gray-400 uppercase">Filename</dt><dd class="text-gray-700 mt-0.5" x-text="detailItem.filename"></dd></div>
                        <div><dt class="text-xs font-medium text-gray-400 uppercase">Size</dt><dd class="text-gray-700 mt-0.5" x-text="detailItem.size"></dd></div>
                        <div><dt class="text-xs font-medium text-gray-400 uppercase">Type</dt><dd class="text-gray-700 mt-0.5" x-text="detailItem.mime"></dd></div>
                        <div><dt class="text-xs font-medium text-gray-400 uppercase">Uploaded</dt><dd class="text-gray-700 mt-0.5" x-text="detailItem.created || '—'"></dd></div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase">File URL</dt>
                            <dd class="flex items-center gap-2 mt-0.5">
                                <code class="text-xs bg-gray-50 px-2 py-1 rounded flex-1 truncate" x-text="detailItem.url"></code>
                                <button type="button" @click="copyToClipboard(detailItem.url)" class="shrink-0 p-1.5 bg-gray-100 rounded hover:bg-gray-200 transition" title="Copy URL">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                </button>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase">Path (for forms)</dt>
                            <dd class="flex items-center gap-2 mt-0.5">
                                <code class="text-xs bg-gray-50 px-2 py-1 rounded flex-1 truncate" x-text="detailItem.path"></code>
                                <button type="button" @click="copyToClipboard(detailItem.path)" class="shrink-0 p-1.5 bg-gray-100 rounded hover:bg-gray-200 transition" title="Copy path">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                </button>
                            </dd>
                        </div>
                    </dl>

                    {{-- Alt text form --}}
                    <form :action="'{{ url('admin/media') }}/' + detailItem.id" method="POST" class="mt-4">
                        @csrf @method('PUT')
                        <label class="text-xs font-medium text-gray-400 uppercase">Alt Text</label>
                        <div class="flex gap-2 mt-1">
                            <input type="text" name="alt_text" :value="detailItem.alt_text" class="flex-1 text-sm rounded-lg border-gray-200 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]" placeholder="Describe this image...">
                            <button type="submit" class="px-3 py-2 bg-[#083321] text-white text-xs font-bold rounded-lg hover:bg-[#083321]/90 transition">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function mediaLibrary(allIds = [], maxUploadMb = 20) {
            return {
                showUpload: false,
                dragOver: false,
                detailOpen: false,
                detailItem: {},
                selected: [],
                uploads: [],
                allIds: allIds || [],
                maxUploadMb: maxUploadMb || 20,

                init() {},

                notify(message, type = 'info', duration = 4500) {
                    if (window.showLomoToast) {
                        window.showLomoToast(message, type, duration);
                    }
                },

                openDetail(data) { this.detailItem = data; this.detailOpen = true; },

                toggleSelect(id) {
                    const i = this.selected.indexOf(id);
                    if (i === -1) this.selected.push(id); else this.selected.splice(i, 1);
                },

                toggleSelectAll(event) {
                    if (event.target.checked) {
                        this.selected = [...this.allIds];
                    } else {
                        this.selected = [];
                    }
                },

                async bulkDelete() {
                    if (!this.selected.length) return;
                    const confirmed = await window.showLomoConfirm({
                        title: 'Delete selected files',
                        message: 'Permanently delete ' + this.selected.length + ' file(s)? This cannot be undone.',
                        confirmText: 'Delete files',
                        tone: 'danger',
                    });

                    if (!confirmed) return;

                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    try {
                        const res = await fetch('/admin/media/bulk-destroy', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ ids: this.selected }),
                        });

                        if (!res.ok) {
                            let message = 'Delete failed.';
                            try {
                                const data = await res.json();
                                message = data.message || message;
                            } catch (error) {
                                console.error('Delete response parse error:', error);
                            }
                            throw new Error(message);
                        }

                        this.notify('Selected files deleted.', 'success', 3000);
                        window.setTimeout(() => window.location.reload(), 250);
                    } catch (e) {
                        this.notify(e.message || 'Delete failed.', 'error');
                        console.error(e);
                    }
                },

                copyPath(path) { this.copyToClipboard(path); },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.notify('Copied to clipboard.', 'success', 2000);
                    }).catch(() => {
                        this.notify('Unable to copy that value.', 'error');
                    });
                },

                handleDrop(e) {
                    this.dragOver = false;
                    if (e.dataTransfer.files.length) this.uploadFiles(e.dataTransfer.files);
                },

                async uploadFiles(files) {
                    if (!files || !files.length) return;

                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const maxBytes = this.maxUploadMb * 1024 * 1024;
                    const queue = Array.from(files).filter(file => {
                        if (file.size > maxBytes) {
                            this.notify(`${file.name} is too large. Maximum file size is ${this.maxUploadMb}MB.`, 'warning');
                            return false;
                        }
                        return true;
                    });

                    if (!queue.length) {
                        return;
                    }

                    let uploadedCount = 0;

                    for (let i = 0; i < queue.length; i++) {
                        const file = queue[i];
                        const upIdx = this.uploads.length;
                        this.uploads.push({ name: file.name, progress: 0 });
                        const fd = new FormData();
                        fd.append('files[]', file);

                        try {
                            const xhr = new XMLHttpRequest();
                            await new Promise((resolve, reject) => {
                                xhr.upload.addEventListener('progress', (e) => {
                                    if (e.lengthComputable) this.uploads[upIdx].progress = Math.round((e.loaded / e.total) * 100);
                                });
                                xhr.addEventListener('load', () => {
                                    if (xhr.status < 200 || xhr.status >= 300) {
                                        let message = `Upload failed. Files can be up to ${this.maxUploadMb}MB.`;
                                        try {
                                            const data = JSON.parse(xhr.responseText || '{}');
                                            message = Object.values(data.errors || {}).flat()[0] || data.message || message;
                                        } catch (error) {
                                            console.error('Upload response parse error:', error);
                                        }
                                        reject(new Error(message));
                                        return;
                                    }

                                    this.uploads[upIdx].progress = 100;
                                    resolve();
                                });
                                xhr.addEventListener('error', () => reject(new Error('Upload failed. Please try again.')));
                                xhr.open('POST', '/admin/media');
                                xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                xhr.setRequestHeader('Accept', 'application/json');
                                xhr.send(fd);
                            });

                            uploadedCount += 1;
                        } catch (e) {
                            this.notify(e.message || 'Upload failed. Please try again.', 'error');
                            console.error('Upload error:', e);
                        }
                    }

                    if (uploadedCount > 0) {
                        this.notify(`${uploadedCount} file${uploadedCount === 1 ? '' : 's'} uploaded successfully.`, 'success', 3000);
                        window.setTimeout(() => window.location.reload(), 400);
                    }
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
*** Delete File: c:\wamp64\www\lomo\resources\views\partials\chat-widget.blade.php.bak
*** Delete File: c:\wamp64\www\lomo\resources\views\admin\safaris\_form.blade.php.bak
*** Delete File: c:\wamp64\www\lomo\resources\views\admin\partials\sidebar-nav.blade.php.bak
*** Delete File: c:\wamp64\www\lomo\resources\views\admin\pages\index.blade.php.bak
*** Delete File: c:\wamp64\www\lomo\resources\views\admin\pages\form.blade.php.bak
*** Delete File: c:\wamp64\www\lomo\resources\views\admin\dashboard.blade.php.bak
