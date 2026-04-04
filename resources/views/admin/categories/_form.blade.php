@php $editing = isset($category) && $category->exists; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- LEFT COLUMN --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    Basic Information
                </span>
            </h3>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $category->name ?? '') }}"
                       placeholder="e.g. Luxury"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                    Slug <span class="text-xs text-gray-400 font-normal">(auto-generated if empty)</span>
                </label>
                <input type="text" name="slug" id="slug"
                       value="{{ old('slug', $category->slug ?? '') }}"
                       placeholder="luxury"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm font-mono">
                @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          placeholder="Brief description of this category…"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Translations --}}
        @include('admin.partials.translation-tabs', [
            'model' => $category ?? null,
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
            ],
        ])

        {{-- SEO Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            @include('admin.partials.seo-panel', ['model' => $category ?? null])
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">Media</h3>
            @include('admin.media.picker', [
                'name'  => 'featured_image',
                'value' => old('featured_image', $category->featured_image ?? ''),
                'label' => 'Featured Image',
            ])
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="w-full px-6 py-2.5 bg-[#FEBC11] text-[#131414] text-sm font-semibold rounded-lg hover:bg-yellow-400 transition shadow-sm">
                {{ $editing ? 'Update Category' : 'Create Category' }}
            </button>
        </div>
    </div>
</div>
