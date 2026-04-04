<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('admin.languages.index') }}" class="text-sm text-gray-500 hover:text-brand-gold">← Back to Languages</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $language ? 'Edit Language' : 'Add Language' }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 max-w-xl">
        <form method="POST"
              action="{{ $language ? route('admin.languages.update', $language) : route('admin.languages.store') }}">
            @csrf
            @if($language) @method('PUT') @endif

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Language Name</label>
                        <input type="text" name="name" value="{{ old('name', $language->name ?? '') }}" required
                               placeholder="e.g. French"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input type="text" name="code" value="{{ old('code', $language->code ?? '') }}" required
                               placeholder="e.g. fr" maxlength="5"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Native Name</label>
                        <input type="text" name="native_name" value="{{ old('native_name', $language->native_name ?? '') }}"
                               placeholder="e.g. Français"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Flag Emoji</label>
                        <input type="text" name="flag" value="{{ old('flag', $language->flag ?? '') }}"
                               placeholder="e.g. 🇫🇷" maxlength="10"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $language->sort_order ?? 0) }}" min="0"
                           class="w-32 rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $language->is_active ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_default" value="0">
                        <input type="checkbox" name="is_default" value="1"
                               {{ old('is_default', $language->is_default ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                        <span class="text-sm text-gray-700">Default Language</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2 bg-brand-dark text-white text-sm rounded-lg hover:bg-gray-800 transition-colors">
                    {{ $language ? 'Update Language' : 'Create Language' }}
                </button>
                <a href="{{ route('admin.languages.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
