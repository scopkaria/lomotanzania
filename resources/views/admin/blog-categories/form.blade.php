<x-app-layout>
    <x-slot name="header">{{ $category ? 'Edit Category' : 'New Blog Category' }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.blog-categories.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $category ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}">
        @csrf
        @if($category) @method('PUT') @endif

        <div class="max-w-lg">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-5" x-data="{ langTab: 'en' }">

                {{-- Language tabs --}}
                <div class="flex gap-1 border-b border-gray-100 mb-4">
                    @foreach(['en','fr','de','es'] as $code)
                        <button type="button" @click="langTab = '{{ $code }}'"
                                :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                class="px-4 py-2.5 text-sm border-b-2 transition uppercase">{{ $code }}</button>
                    @endforeach
                </div>

                @foreach(['en','fr','de','es'] as $code)
                    <div x-show="langTab === '{{ $code }}'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Name ({{ strtoupper($code) }}) @if($code === 'en') <span class="text-red-500">*</span> @endif
                        </label>
                        <input type="text" name="name[{{ $code }}]"
                               value="{{ old("name.{$code}", $category ? ($category->name[$code] ?? '') : '') }}"
                               placeholder="Category name in {{ strtoupper($code) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm"
                               @if($code === 'en') required @endif>
                    </div>
                @endforeach

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug"
                           value="{{ old('slug', $category->slug ?? '') }}"
                           placeholder="auto-generated"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                </div>

                <div class="pt-3 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-yellow-400 transition">
                        {{ $category ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
