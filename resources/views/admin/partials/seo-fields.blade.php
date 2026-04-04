{{-- SEO Fields Partial for Admin Forms --}}
{{-- Usage: @include('admin.partials.seo-fields', ['model' => $safari]) --}}

@php
    $model = $model ?? null;
@endphp

<div class="pt-6 border-t border-gray-200">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <h3 class="text-lg font-bold text-gray-900">SEO Settings</h3>
    </div>

    {{-- Meta Title --}}
    <div class="mb-4">
        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
        <input type="text" name="meta_title" id="meta_title"
               value="{{ old('meta_title', $model->meta_title ?? '') }}"
               placeholder="Custom title for search engines (leave empty to use page title)"
               maxlength="70"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
        <p class="mt-1 text-xs text-gray-400">Recommended: 50-60 characters. Leave empty to use the default title.</p>
        @error('meta_title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Meta Description --}}
    <div class="mb-4">
        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
        <textarea name="meta_description" id="meta_description" rows="3" maxlength="500"
                  placeholder="Describe this page for Google search results..."
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
        <p class="mt-1 text-xs text-gray-400">Recommended: 150-160 characters.</p>
        @error('meta_description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Meta Keywords --}}
    <div class="mb-4">
        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
        <input type="text" name="meta_keywords" id="meta_keywords"
               value="{{ old('meta_keywords', $model->meta_keywords ?? '') }}"
               placeholder="safari, tanzania, serengeti, wildlife"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
        <p class="mt-1 text-xs text-gray-400">Comma-separated keywords.</p>
        @error('meta_keywords')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- OG Image --}}
    <div>
        @include('admin.media.picker', [
            'name'  => 'og_image',
            'value' => old('og_image', $model->og_image ?? ''),
            'label' => 'Social Share Image (OG Image)',
        ])
        <p class="mt-1 text-xs text-gray-400">Recommended: 1200x630px. Falls back to featured image, then site default.</p>
    </div>
</div>
