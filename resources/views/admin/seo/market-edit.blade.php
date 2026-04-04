<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Market Page</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('seo.market', ['locale' => 'en', 'slug' => $market->slug]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-sm hover:bg-blue-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Page ↗
            </a>
            <a href="{{ route('admin.seo.markets') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                ← Back to Markets
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 py-3 px-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.seo.markets.update', $market) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="rounded-xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
            <h2 class="text-lg font-semibold text-gray-800">Basic Info</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $market->title) }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $market->meta_title) }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $market->meta_keywords) }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('meta_description', $market->meta_description) }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Intro Content</label>
                    <textarea name="intro_content" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('intro_content', $market->intro_content) }}</textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" {{ $market->is_published ? 'checked' : '' }} class="rounded border-gray-300 text-[#083321] focus:ring-[#FEBC11]" id="is_published">
                    <label for="is_published" class="text-sm text-gray-700">Published</label>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white border border-gray-200 p-6 shadow-sm space-y-6">
            <h2 class="text-lg font-semibold text-gray-800">Travel Information</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Flights Info</label>
                <textarea name="flights_info" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('flights_info', $market->flights_info) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Visa Info</label>
                <textarea name="visa_info" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('visa_info', $market->visa_info) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Travel Tips</label>
                <textarea name="travel_tips" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('travel_tips', $market->travel_tips) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Best Routes</label>
                <textarea name="best_routes" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('best_routes', $market->best_routes) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pricing Info</label>
                <textarea name="pricing_info" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#FEBC11] focus:border-[#FEBC11]">{{ old('pricing_info', $market->pricing_info) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-xs text-gray-400">
                Source: {{ $market->source_market }} · Target: {{ $market->target_country }} · Views: {{ number_format($market->views) }}
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#083321] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#0a4a30] transition">
                Save Changes
            </button>
        </div>
    </form>
</x-app-layout>
