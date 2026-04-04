<div class="max-w-2xl space-y-6">

    {{-- Basic Info --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Testimonial Details</h3>

        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Guest Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name ?? '') }}" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="safari_package_id" class="block text-sm font-medium text-gray-700 mb-1">Safari Package</label>
                <select name="safari_package_id" id="safari_package_id"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    <option value="">— None —</option>
                    @foreach($safaris as $safari)
                        <option value="{{ $safari->id }}" @selected(old('safari_package_id', $testimonial->safari_package_id ?? '') == $safari->id)>
                            {{ $safari->title }}
                        </option>
                    @endforeach
                </select>
                @error('safari_package_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="5" required
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">{{ old('message', $testimonial->message ?? '') }}</textarea>
                @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                <select name="rating" id="rating" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5) == $i)>
                            {{ $i }} {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }}
                        </option>
                    @endfor
                </select>
                @error('rating') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Approval --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Visibility</h3>

        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="approved" value="0">
            <input type="checkbox" name="approved" value="1"
                   @checked(old('approved', $testimonial->approved ?? false))
                   class="rounded border-gray-300 text-brand-gold shadow-sm focus:ring-brand-gold">
            <span class="text-sm text-gray-700">Approved &amp; visible on website</span>
        </label>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit" class="px-5 py-2.5 bg-brand-gold text-brand-dark text-sm font-semibold rounded-lg hover:bg-yellow-400 transition shadow-sm">
            {{ isset($testimonial) ? 'Update Testimonial' : 'Create Testimonial' }}
        </button>
        <a href="{{ route('admin.testimonials.index') }}" class="px-5 py-2.5 text-sm text-gray-500 hover:text-gray-700">Cancel</a>
    </div>

</div>
