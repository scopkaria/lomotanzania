<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $category->exists ? 'Edit' : 'Create' }} Tour Category</h1>

        <form action="{{ $category->exists ? route('admin.tour-categories.update', $category) : route('admin.tour-categories.store') }}"
              method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            @csrf
            @if($category->exists) @method('PUT') @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Auto-generated from name">
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @include('admin.media.picker', ['name' => 'featured_image', 'value' => old('featured_image', $category->featured_image ?? ''), 'label' => 'Featured Image'])

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', $category->display_order ?? 0) }}" min="0">
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-green-700 text-white text-sm font-medium rounded-lg hover:bg-green-800 transition">
                    {{ $category->exists ? 'Update' : 'Create' }} Category
                </button>
                <a href="{{ route('admin.tour-categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
