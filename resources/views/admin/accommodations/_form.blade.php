@php
    $editing = isset($accommodation) && $accommodation->exists;
    $destinationsJson = $destinations->map(fn ($destination) => [
        'id' => $destination->id,
        'name' => $destination->name,
        'country_id' => $destination->country_id,
    ])->values()->all();
@endphp

<div class="space-y-6" x-data="accommodationForm()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        Accommodation Details
                    </span>
                </h3>

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $accommodation->name ?? '') }}" required
                               placeholder="e.g. Serengeti Serena Safari Lodge"
                               class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                            Slug <span class="text-xs text-gray-400 font-normal">(auto-generated if empty)</span>
                        </label>
                        <input type="text" name="slug" id="slug"
                               value="{{ old('slug', $accommodation->slug ?? '') }}"
                               placeholder="serengeti-serena-safari-lodge"
                               class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] font-mono">
                        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="5"
                                  placeholder="Describe this accommodation…"
                                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('description', $accommodation->description ?? '') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="">Select category</option>
                                @foreach(['luxury' => 'Luxury', 'mid-range' => 'Mid-range', 'budget' => 'Budget', 'high-end' => 'High-end'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category', $accommodation->category ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select name="country_id" id="country_id" required x-model="selectedCountry"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-1">Destination <span class="text-red-500">*</span></label>
                            <select name="destination_id" id="destination_id" required x-model="selectedDestination"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                                <option value="">Select destination</option>
                                <template x-for="destination in filteredDestinations" :key="destination.id">
                                    <option :value="destination.id" x-text="destination.name"></option>
                                </template>
                            </select>
                            @error('destination_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Translations --}}
            @include('admin.partials.translation-tabs', [
                'model' => $accommodation ?? null,
                'fields' => [
                    ['name' => 'name', 'label' => 'Name', 'type' => 'text'],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 5],
                ],
            ])

            {{-- SEO Settings --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                @include('admin.partials.seo-panel', ['model' => $accommodation ?? null])
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">Gallery</h3>
                @include('admin.media.gallery-picker', [
                    'name'     => 'gallery_paths',
                    'existing' => $editing ? $accommodation->images : collect(),
                    'label'    => 'Gallery',
                ])
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="w-full px-5 py-2.5 bg-[#FEBC11] text-[#131414] text-sm font-semibold rounded-lg hover:bg-yellow-400 transition shadow-sm">
                    {{ $editing ? 'Update Accommodation' : 'Create Accommodation' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('accommodationForm', () => ({
        allDestinations: @json($destinationsJson),
        selectedCountry: '{{ old('country_id', $accommodation->country_id ?? '') }}',
        selectedDestination: '{{ old('destination_id', $accommodation->destination_id ?? '') }}',

        get filteredDestinations() {
            if (!this.selectedCountry) return this.allDestinations;
            return this.allDestinations.filter(destination => String(destination.country_id) === String(this.selectedCountry));
        },

        init() {
            this.$watch('selectedCountry', () => {
                if (!this.filteredDestinations.some(destination => String(destination.id) === String(this.selectedDestination))) {
                    this.selectedDestination = '';
                }
            });
        },
    }));
});
</script>