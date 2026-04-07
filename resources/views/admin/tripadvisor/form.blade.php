<x-app-layout>
    <x-slot name="header">{{ isset($review) ? 'Edit Review' : 'Add Review' }}</x-slot>

    <div class="max-w-2xl">
        <form method="POST"
              action="{{ isset($review) ? route('admin.tripadvisor.update', $review) : route('admin.tripadvisor.store') }}"
              class="space-y-6">
            @csrf
            @if(isset($review)) @method('PUT') @endif

            {{-- Reviewer Name --}}
            <div>
                <label for="reviewer_name" class="block text-sm font-medium text-gray-700 mb-1">Reviewer Name <span class="text-red-500">*</span></label>
                <input type="text" name="reviewer_name" id="reviewer_name"
                       value="{{ old('reviewer_name', $review->reviewer_name ?? '') }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]"
                       required>
                @error('reviewer_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Reviewer Location --}}
            <div>
                <label for="reviewer_location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="reviewer_location" id="reviewer_location"
                       value="{{ old('reviewer_location', $review->reviewer_location ?? '') }}"
                       placeholder="e.g. New York, USA"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
            </div>

            {{-- Review Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Review Title</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $review->title ?? '') }}"
                       placeholder="e.g. Amazing safari experience!"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
            </div>

            {{-- Review Text --}}
            <div>
                <label for="review_text" class="block text-sm font-medium text-gray-700 mb-1">Review Text <span class="text-red-500">*</span></label>
                <textarea name="review_text" id="review_text" rows="5"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]"
                          required>{{ old('review_text', $review->review_text ?? '') }}</textarea>
                @error('review_text') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Rating --}}
            <div x-data="{ rating: {{ old('rating', $review->rating ?? 5) }} }">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                <input type="hidden" name="rating" :value="rating">
                <div class="flex items-center gap-1">
                    <template x-for="star in 5" :key="star">
                        <button type="button" @click="rating = star" class="focus:outline-none">
                            <svg class="w-8 h-8 transition" :class="star <= rating ? 'text-[#FEBC11]' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    </template>
                    <span class="ml-2 text-sm text-gray-500" x-text="rating + ' / 5'"></span>
                </div>
                @error('rating') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Two columns: Date & Trip Type --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="review_date" class="block text-sm font-medium text-gray-700 mb-1">Review Date</label>
                    <input type="date" name="review_date" id="review_date"
                           value="{{ old('review_date', isset($review) && $review->review_date ? $review->review_date->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                </div>
                <div>
                    <label for="trip_type" class="block text-sm font-medium text-gray-700 mb-1">Trip Type</label>
                    <select name="trip_type" id="trip_type"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                        <option value="">— Select —</option>
                        @foreach(['Family', 'Couples', 'Solo', 'Friends', 'Business'] as $type)
                            <option value="{{ $type }}" {{ old('trip_type', $review->trip_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Two columns: Display Order & Published --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                    <input type="number" name="display_order" id="display_order" min="0"
                           value="{{ old('display_order', $review->display_order ?? 0) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="published" value="0">
                        <input type="checkbox" name="published" value="1"
                               {{ old('published', $review->published ?? false) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                        <span class="text-sm font-medium text-gray-700">Publish immediately</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    {{ isset($review) ? 'Update Review' : 'Add Review' }}
                </button>
                <a href="{{ route('admin.tripadvisor.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
