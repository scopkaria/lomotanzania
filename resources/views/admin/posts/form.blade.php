<x-app-layout>
    <x-slot name="header">{{ $post ? 'Edit Post' : 'New Post' }}</x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $post ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          enctype="multipart/form-data"
          x-data="{ langTab: 'en' }">
        @csrf
        @if($post) @method('PUT') @endif

        <div class="sticky top-0 z-40 mb-6 border-b border-gray-200 bg-white/95 px-4 py-4 backdrop-blur sm:px-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back to posts
                    </a>
                    @if($post && $post->status === 'published')
                        <a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 rounded-lg bg-[#083321]/5 px-3 py-1.5 text-sm font-medium text-[#083321] transition hover:bg-[#083321]/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            View on site
                        </a>
                    @endif
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#FEBC11] px-4 py-2.5 text-sm font-semibold text-[#131414] shadow-sm transition hover:bg-yellow-400">
                    {{ $post ? 'Update Post' : 'Create Post' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ====== LEFT COLUMN ====== --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Title --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-5">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Title</h3>
                        @include('admin.partials.auto-translate-button')
                    </div>

                    <div class="flex gap-1 mb-4 border-b border-gray-100">
                        @foreach($locales as $code)
                            <button type="button" @click="langTab = '{{ $code }}'"
                                    :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2.5 text-sm border-b-2 transition uppercase">{{ $code }}</button>
                        @endforeach
                    </div>

                    @foreach($locales as $code)
                        <div x-show="langTab === '{{ $code }}'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Title ({{ strtoupper($code) }}) @if($code === 'en') <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="text" name="title[{{ $code }}]"
                                   value="{{ old("title.{$code}", $post ? ($post->title[$code] ?? '') : '') }}"
                                   placeholder="Post title in {{ strtoupper($code) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm"
                                   @if($code === 'en') required @endif>
                        </div>
                    @endforeach
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">Content</h3>

                    <div class="flex gap-1 mb-4 border-b border-gray-100">
                        @foreach($locales as $code)
                            <button type="button" @click="langTab = '{{ $code }}'"
                                    :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2.5 text-sm border-b-2 transition uppercase">{{ $code }}</button>
                        @endforeach
                    </div>

                    @foreach($locales as $code)
                        <div x-show="langTab === '{{ $code }}'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content ({{ strtoupper($code) }})</label>
                                @include('admin.partials.rich-editor', [
                                    'name'        => "content[{$code}]",
                                    'id'          => "content_{$code}",
                                    'value'       => old("content.{$code}", $post ? ($post->content[$code] ?? '') : ''),
                                    'rows'        => 'large',
                                    'placeholder' => "Write your blog post content in " . strtoupper($code) . "...",
                                    'label'       => null,
                                ])
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt ({{ strtoupper($code) }})</label>
                                <textarea name="excerpt[{{ $code }}]" rows="3" maxlength="500"
                                          placeholder="Short excerpt for listing pages"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">{{ old("excerpt.{$code}", $post ? ($post->excerpt[$code] ?? '') : '') }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SEO Meta --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                            SEO Meta
                        </span>
                    </h3>

                    <div class="flex gap-1 mb-4 border-b border-gray-100">
                        @foreach($locales as $code)
                            <button type="button" @click="langTab = '{{ $code }}'"
                                    :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2.5 text-sm border-b-2 transition uppercase">{{ $code }}</button>
                        @endforeach
                    </div>

                    @foreach($locales as $code)
                        <div x-show="langTab === '{{ $code }}'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title ({{ strtoupper($code) }})</label>
                                <input type="text" name="meta_title[{{ $code }}]"
                                       value="{{ old("meta_title.{$code}", $post ? ($post->meta['meta_title'][$code] ?? '') : '') }}"
                                       placeholder="SEO title for {{ strtoupper($code) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description ({{ strtoupper($code) }})</label>
                                <textarea name="meta_description[{{ $code }}]" rows="2" maxlength="160"
                                          placeholder="SEO description for {{ strtoupper($code) }} (max 160 chars)"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">{{ old("meta_description.{$code}", $post ? ($post->meta['meta_description'][$code] ?? '') : '') }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ====== RIGHT SIDEBAR ====== --}}
            <div class="space-y-6">

                {{-- Publish --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">Publish</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                                <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', $post && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-xs text-gray-400 font-normal">(auto if empty)</span>
                            </label>
                            <input type="text" name="slug"
                                   value="{{ old('slug', $post->slug ?? '') }}"
                                   placeholder="auto-generated"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11]">
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex gap-3">
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-yellow-400 transition">
                                {{ $post ? 'Update' : 'Publish' }}
                            </button>
                            @if($post && $post->status === 'published')
                                <a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    View
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Category --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">Category</h3>
                    <select name="blog_category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('blog_category_id', $post->blog_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name['en'] ?? $cat->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">Featured Image</h3>

                    @include('admin.media.picker', [
                        'name'  => 'featured_image',
                        'value' => old('featured_image', $post->featured_image ?? ''),
                        'label' => '',
                    ])
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
