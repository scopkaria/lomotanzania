<div class="max-w-2xl space-y-6" x-data="locationSearch()">

    {{-- Location Search --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">
            <span class="inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                Location Search
            </span>
        </h3>

        <div class="relative">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <input type="text" x-model="query" @input.debounce.300ms="search()" @focus="showDropdown = results.length > 0"
                       @keydown.escape="showDropdown = false" @keydown.arrow-down.prevent="highlightNext()" @keydown.arrow-up.prevent="highlightPrev()"
                       @keydown.enter.prevent="selectHighlighted()"
                       placeholder="Search location (e.g. Serengeti National Park)"
                       class="w-full py-3 pl-9 pr-10 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                       autocomplete="off">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg x-show="loading" class="w-4 h-4 text-[#FEBC11] animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <button x-show="query && !loading" type="button" @click="clearSearch()" class="text-gray-300 hover:text-gray-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Dropdown --}}
            <div x-show="showDropdown && results.length > 0" x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click.outside="showDropdown = false"
                 class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                <ul class="max-h-64 overflow-y-auto">
                    <template x-for="(result, index) in results" :key="index">
                        <li @click="selectResult(result)" @mouseenter="highlighted = index"
                            class="px-4 py-3 text-sm cursor-pointer transition"
                            :class="highlighted === index ? 'bg-[#F9F7F3]' : 'hover:bg-[#F9F7F3]'">
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 mt-0.5 shrink-0" :class="highlighted === index ? 'text-[#FEBC11]' : 'text-gray-400'"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="result.name"></p>
                                    <p class="text-xs text-gray-400 truncate" x-text="result.fullAddress"></p>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
                <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                    <p class="text-[10px] text-gray-400 flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        Powered by Mapbox
                    </p>
                </div>
            </div>

            {{-- Error message --}}
            <div x-show="searchError" x-transition class="mt-2">
                <p class="text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span x-text="searchError"></span>
                </p>
            </div>

            {{-- Selected location indicator --}}
            <div x-show="selectedLocation" x-transition class="mt-2 flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Location set
                </span>
                <span class="text-gray-400" x-text="selectedLocation"></span>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">Search to auto-fill name, latitude and longitude. You can still edit fields manually.</p>
    </div>

    {{-- Basic Info --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Basic Information</h3>

        <div class="space-y-4">
            <div>
                <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                <select name="country_id" id="country_id" required
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                    <option value="">Select a country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected(old('country_id', $destination->country_id ?? '') == $country->id)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $destination->name ?? '') }}" required
                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $destination->slug ?? '') }}"
                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                       placeholder="Auto-generated from name">
                @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- UPDATED: Rich text editor for description --}}
            <div>
                @include('admin.partials.rich-editor', [
                    'name'  => 'description',
                    'id'    => 'destination_description',
                    'value' => old('description', $destination->description ?? ''),
                    'label' => 'Description',
                    'rows'  => 'large',
                    'placeholder' => 'Write a detailed SEO-friendly description of this destination...',
                ])
            </div>
        </div>
    </div>

    {{-- Translations --}}
    @include('admin.partials.translation-tabs', [
        'model' => $destination ?? null,
        'fields' => [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'richtext', 'rows' => 6],
        ],
    ])

    {{-- Coordinates --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">
            <span class="inline-flex items-center gap-2">
                Coordinates
                <span x-show="selectedLocation" class="inline-flex items-center gap-1 text-[10px] font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Auto-filled
                </span>
            </span>
        </h3>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $destination->latitude ?? '') }}"
                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                       placeholder="-6.3690">
                @error('latitude') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $destination->longitude ?? '') }}"
                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                       placeholder="34.8888">
                @error('longitude') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Featured Image --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        @include('admin.media.picker', [
            'name'  => 'featured_image',
            'value' => old('featured_image', $destination->featured_image ?? ''),
            'label' => 'Featured Image',
        ])
    </div>

    {{-- SEO Settings --}}
    @include('admin.partials.seo-panel', ['model' => $destination ?? null])

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit" class="px-5 py-2.5 bg-[#FEBC11] text-[#131414] text-sm font-semibold rounded-lg hover:bg-yellow-400 transition shadow-sm">
            {{ isset($destination) ? 'Update Destination' : 'Create Destination' }}
        </button>
        <a href="{{ route('admin.destinations.index') }}" class="px-5 py-2.5 text-sm text-gray-500 hover:text-gray-700">Cancel</a>
    </div>

</div>

<script>
const MAPBOX_TOKEN = @json(config('services.mapbox.token'));
console.log('[Mapbox] Token loaded:', MAPBOX_TOKEN ? 'Yes (' + MAPBOX_TOKEN.substring(0, 10) + '...)' : 'MISSING');

document.addEventListener('alpine:init', () => {
    Alpine.data('locationSearch', () => ({
        query: '',
        results: [],
        loading: false,
        showDropdown: false,
        highlighted: -1,
        selectedLocation: '',
        searchError: '',
        abortController: null,

        async search() {
            const q = this.query.trim();
            if (q.length < 3) {
                this.results = [];
                this.showDropdown = false;
                this.searchError = '';
                return;
            }

            if (this.abortController) this.abortController.abort();
            this.abortController = new AbortController();

            this.loading = true;
            this.highlighted = -1;
            this.searchError = '';

            try {
                const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(q)}.json?access_token=${MAPBOX_TOKEN}&limit=8&types=poi,place,locality,region,district`;
                console.log('[Mapbox] Searching:', q);
                const res = await fetch(url, { signal: this.abortController.signal });

                if (!res.ok) {
                    const errBody = await res.text();
                    console.error('[Mapbox] API error:', res.status, errBody);
                    this.searchError = `Mapbox API error (${res.status}). Check console for details.`;
                    this.results = [];
                    this.showDropdown = false;
                    return;
                }

                const data = await res.json();
                console.log('[Mapbox] Results:', data.features?.length || 0);

                const parkKeywords = /national park|game reserve|conservation area|wildlife|safari|crater|ngorongoro|serengeti/i;
                const mapped = (data.features || []).map(f => ({
                    name: f.text,
                    fullAddress: f.place_name,
                    lat: f.center[1],
                    lng: f.center[0],
                    isPark: parkKeywords.test(f.place_name) || (f.properties && parkKeywords.test(f.properties.category || '')),
                }));
                mapped.sort((a, b) => (b.isPark ? 1 : 0) - (a.isPark ? 1 : 0));

                this.results = mapped.slice(0, 5);

                this.showDropdown = this.results.length > 0;
            } catch (e) {
                if (e.name !== 'AbortError') {
                    console.error('[Mapbox] Search failed:', e);
                    this.searchError = 'Search failed. Check your internet connection.';
                }
            } finally {
                this.loading = false;
            }
        },

        selectResult(result) {
            document.getElementById('name').value = result.name;
            document.getElementById('latitude').value = result.lat;
            document.getElementById('longitude').value = result.lng;

            document.getElementById('name').dispatchEvent(new Event('input', { bubbles: true }));
            document.getElementById('latitude').dispatchEvent(new Event('input', { bubbles: true }));
            document.getElementById('longitude').dispatchEvent(new Event('input', { bubbles: true }));

            this.query = result.fullAddress;
            this.selectedLocation = `${result.lat.toFixed(4)}, ${result.lng.toFixed(4)}`;
            this.showDropdown = false;
            this.results = [];
            this.searchError = '';
        },

        clearSearch() {
            this.query = '';
            this.results = [];
            this.showDropdown = false;
            this.selectedLocation = '';
            this.searchError = '';
        },

        highlightNext() {
            if (this.highlighted < this.results.length - 1) this.highlighted++;
        },

        highlightPrev() {
            if (this.highlighted > 0) this.highlighted--;
        },

        selectHighlighted() {
            if (this.highlighted >= 0 && this.highlighted < this.results.length) {
                this.selectResult(this.results[this.highlighted]);
            }
        },
    }));
});
</script>
