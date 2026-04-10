<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Index Hero Images</h1>
                <p class="text-sm text-gray-500 mt-1">Configure the hero banner image, title, and subtitle for each index page.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.pages.index-hero-images.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach($heroes as $key => $hero)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                        {{ $hero->label }}
                    </h2>

                    <div class="space-y-5">
                        {{-- Image --}}
                        @include('admin.media.picker', [
                            'name'  => "sections[{$key}][image_path]",
                            'value' => $hero->image_path ?? '',
                            'label' => 'Hero Background Image',
                        ])

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hero Title</label>
                            <input type="text"
                                   name="sections[{{ $key }}][title]"
                                   value="{{ old("sections.{$key}.title", $hero->title) }}"
                                   placeholder="e.g. Explore Our Blog"
                                   class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-[#083321] focus:border-[#083321]">
                        </div>

                        {{-- Subtitle --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hero Subtitle</label>
                            <input type="text"
                                   name="sections[{{ $key }}][subtitle]"
                                   value="{{ old("sections.{$key}.subtitle", $hero->subtitle) }}"
                                   placeholder="e.g. Stories, tips, and guides from the African bush"
                                   class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-[#083321] focus:border-[#083321]">
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Save --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#083321] hover:bg-[#083321]/90 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
