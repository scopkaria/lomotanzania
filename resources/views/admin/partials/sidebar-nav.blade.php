@once
    @push('styles')
        <style>
            input:not([type='checkbox']):not([type='radio']):not([type='range']),
            select,
            textarea {
                border-width: 1px;
                border-color: #083321;
                padding: 0.625rem 0.875rem;
                border-radius: 0.375rem;
                margin-bottom: 0.25rem;
                color: #131414;
                background-color: #fff;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }

            textarea {
                min-height: 120px;
                line-height: 1.6;
                resize: vertical;
            }

            input:not([type='checkbox']):not([type='radio']):not([type='range']):focus,
            select:focus,
            textarea:focus {
                border-color: #FEBC11 !important;
                box-shadow: 0 0 0 3px rgba(254, 188, 17, 0.15) !important;
                outline: none;
            }

            input:not([type='checkbox']):not([type='radio']):not([type='range'])::placeholder,
            textarea::placeholder {
                color: rgba(8, 51, 33, 0.4);
            }
        </style>
    @endpush
@endonce

@php
    $current = request()->route()?->getName();
    $isWorker = Auth::user()->role === 'worker';
@endphp

@if(!$isWorker)
{{-- Dashboard --}}
@php $active = $current && fnmatch('admin.dashboard', $current); @endphp
<a href="{{ route('admin.dashboard') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
    </svg>
    Dashboard
</a>
<a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
   target="_blank" rel="noopener noreferrer"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-600 hover:bg-gray-50 hover:text-gray-900">
    <svg class="w-5 h-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H19.5m0 0V12m0-6L10.5 15m-4.5 3h12a1.5 1.5 0 001.5-1.5v-12A1.5 1.5 0 0018 3H6a1.5 1.5 0 00-1.5 1.5v12A1.5 1.5 0 006 18z"/>
    </svg>
    View Website
</a>
@endif



<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Content</p>

{{-- Safari Section --}}
@php $safariActive = $current && (fnmatch('admin.safaris.*', $current) || fnmatch('admin.tour-types.*', $current) || fnmatch('admin.categories.*', $current)); @endphp
<div x-data="{ open: {{ $safariActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $safariActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $safariActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="flex-1 text-left">Safaris</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.safaris.index'; @endphp
        <a href="{{ route('admin.safaris.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">All Safaris</a>
        @php $a = $current === 'admin.safaris.create'; @endphp
        <a href="{{ route('admin.safaris.create') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Add New</a>
        @php $a = $current && fnmatch('admin.tour-types.*', $current); @endphp
        <a href="{{ route('admin.tour-types.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Experiences</a>
        @php $a = $current && fnmatch('admin.categories.*', $current); @endphp
        <a href="{{ route('admin.categories.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Budget</a>
    </div>
</div>

{{-- Accommodation Section --}}
@php $accommodationActive = $current && fnmatch('admin.accommodations.*', $current); @endphp
<div x-data="{ open: {{ $accommodationActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $accommodationActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $accommodationActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M4.5 18V9.75m0 8.25h15m-15 0v-3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V18m-15-8.25 4.816-4.012a1.125 1.125 0 0 1 1.44 0l4.819 4.012m-6.259 0V21m6-11.25V21"/>
        </svg>
        <span class="flex-1 text-left">Accommodation</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.accommodations.index'; @endphp
        <a href="{{ route('admin.accommodations.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">All Accommodations</a>
        @php $a = $current === 'admin.accommodations.create'; @endphp
        <a href="{{ route('admin.accommodations.create') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Add New</a>
    </div>
</div>

{{-- Destinations --}}
@php $active = $current && fnmatch('admin.destinations.*', $current); @endphp
<a href="{{ route('admin.destinations.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    Destinations
</a>

{{-- Countries --}}
@php $active = $current && fnmatch('admin.countries.*', $current); @endphp
<a href="{{ route('admin.countries.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21V3h18v18H3zM3 9h18M3 15h18M9 3v18M15 3v18"/></svg>
    Countries
</a>

<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Publishing</p>

{{-- Pages Section --}}
@php $pagesActive = $current && (fnmatch('admin.pages.*', $current)); @endphp
<div x-data="{ open: {{ $pagesActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $pagesActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $pagesActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
        <span class="flex-1 text-left">Pages</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.pages.index'; @endphp
        <a href="{{ route('admin.pages.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">All Pages</a>
        @php $a = $current === 'admin.pages.create'; @endphp
        <a href="{{ route('admin.pages.create') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Add New Page</a>
        @php $a = $current && fnmatch('admin.pages.hero-settings.*', $current); @endphp
        <a href="{{ route('admin.pages.hero-settings.edit') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Hero Settings</a>
    </div>
</div>

{{-- Blog Section --}}
@php $blogActive = $current && (fnmatch('admin.posts.*', $current) || fnmatch('admin.blog-categories.*', $current)); @endphp
<div x-data="{ open: {{ $blogActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $blogActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $blogActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5"/></svg>
        <span class="flex-1 text-left">Blog</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.posts.index'; @endphp
        <a href="{{ route('admin.posts.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">All Posts</a>
        @php $a = $current === 'admin.posts.create'; @endphp
        <a href="{{ route('admin.posts.create') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Add New</a>
        @php $a = $current && fnmatch('admin.blog-categories.*', $current); @endphp
        <a href="{{ route('admin.blog-categories.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Categories</a>
    </div>
</div>

{{-- Testimonials --}}
@php $active = $current && fnmatch('admin.testimonials.*', $current); @endphp
<a href="{{ route('admin.testimonials.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    Testimonials
</a>

{{-- Media Library --}}
@php $active = $current && fnmatch('admin.media.*', $current); @endphp
<a href="{{ route('admin.media.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21zm16.5-13.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
    Media Library
</a>

<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Business</p>

{{-- Live Chat --}}
@php $chatActive = $current && fnmatch('admin.chat.*', $current); @endphp
<a href="{{ route('admin.chat.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $chatActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $chatActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
    <span class="flex-1">Live Chat</span>
    <span x-data="{ count: 0 }" x-init="
        fetch('{{ route('admin.chat.unread-count') }}').then(r => r.json()).then(d => count = d.count);
        setInterval(() => fetch('{{ route('admin.chat.unread-count') }}').then(r => r.json()).then(d => count = d.count), 15000);
    " x-show="count > 0" x-text="count" class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none"></span>
</a>

{{-- Team Management --}}
@if(Auth::user()->isSuperAdmin())
@php $teamActive = $current && fnmatch('admin.workers.*', $current); @endphp
<div x-data="{ open: {{ $teamActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $teamActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $teamActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        <span class="flex-1 text-left">Team</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.workers.admins'; @endphp
        <a href="{{ route('admin.workers.admins') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Administrators</a>
        @php $a = $current === 'admin.workers.index'; @endphp
        <a href="{{ route('admin.workers.index') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Workers</a>
        @php $a = $current === 'admin.workers.departments'; @endphp
        <a href="{{ route('admin.workers.departments') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Departments</a>
    </div>
</div>
@endif

{{-- Inquiries --}}
@php $active = $current && fnmatch('admin.inquiries.*', $current); @endphp
<a href="{{ route('admin.inquiries.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5-1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.981l7.5-4.039a2.25 2.25 0 012.134 0l7.5 4.039a2.25 2.25 0 011.183 1.98V15.75z"/></svg>
    <span class="flex-1">Inquiries</span>
    @php $newInquiries = \App\Models\Inquiry::where('status','new')->count(); @endphp
    @if($newInquiries > 0)
        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $newInquiries }}</span>
    @endif
</a>

{{-- Bookings --}}
@php $active = $current && fnmatch('admin.bookings.*', $current); @endphp
<a href="{{ route('admin.bookings.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
    Bookings
</a>

{{-- Agents --}}
@if(!$isWorker)
@php $active = $current && fnmatch('admin.agents.*', $current); @endphp
<a href="{{ route('admin.agents.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    <span class="flex-1">Agents</span>
    @php $pendingAgents = \App\Models\Agent::where('status','pending')->count(); @endphp
    @if($pendingAgents > 0)
        <span class="bg-amber-400 text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingAgents }}</span>
    @endif
</a>
@endif

{{-- Safari Plans --}}
@php $active = $current && fnmatch('admin.safari-plans.*', $current); @endphp
<a href="{{ route('admin.safari-plans.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
    Safari Plans
</a>

{{-- Safari Requests --}}
@php $active = $current && fnmatch('admin.safari-requests.*', $current); @endphp
<a href="{{ route('admin.safari-requests.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
    <span class="flex-1">Safari Requests</span>
    @php $newRequests = \App\Models\SafariRequest::where('status','new')->count(); @endphp
    @if($newRequests > 0)
        <span class="bg-amber-400 text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $newRequests }}</span>
    @endif
</a>

<p class="px-3 pt-4 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Settings</p>

@if(!$isWorker)
{{-- Notifications --}}
@php $active = $current && fnmatch('admin.notifications.*', $current); @endphp
<a href="{{ route('admin.notifications.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
    <span class="flex-1">Notifications</span>
    <span x-data="{ count: 0 }" x-init="
        fetch('{{ route('admin.notifications.fetch') }}').then(r => r.json()).then(d => count = d.filter(n => !n.read_at).length);
        setInterval(() => fetch('{{ route('admin.notifications.fetch') }}').then(r => r.json()).then(d => count = d.filter(n => !n.read_at).length), 15000);
    " x-show="count > 0" x-text="count" class="bg-[#FEBC11] text-[#131414] text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none"></span>
</a>
@endif

{{-- Account Settings --}}
@php $active = $current && fnmatch('admin.account.*', $current); @endphp
<a href="{{ route('admin.account.edit') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    Account Settings
</a>

{{-- Hero Settings (moved under Pages) --}}
{{-- @php $active = $current && fnmatch('admin.hero-settings.*', $current); @endphp
<a href="{{ route('admin.hero-settings.edit') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-2.625 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125M19.125 12h1.5m0 0c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m1.5 2.625V12"/></svg>
    Hero Settings
</a> --}}

@if(!$isWorker)
{{-- Planner Settings --}}
@php $active = $current && fnmatch('admin.planner-settings.*', $current); @endphp
<a href="{{ route('admin.planner-settings.edit') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
    Planner Settings
</a>

{{-- Languages --}}
@php $active = $current && fnmatch('admin.languages.*', $current); @endphp
<a href="{{ route('admin.languages.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
    Languages
</a>

{{-- Settings --}}
@php $active = $current && fnmatch('admin.settings.*', $current); @endphp
<a href="{{ route('admin.settings.edit') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.975 6.975 0 011.084.626c.296.212.66.29 1.015.174l1.226-.425c.517-.18 1.09.04 1.364.516l1.296 2.244c.274.476.153 1.08-.277 1.387l-1.013.856c-.299.252-.446.634-.425 1.024a6.29 6.29 0 010 .724c-.021.39.126.772.425 1.024l1.013.856c.43.307.551.91.277 1.387l-1.296 2.244a1.124 1.124 0 01-1.364.516l-1.226-.425c-.355-.116-.72-.038-1.015.174a6.97 6.97 0 01-1.084.626c-.332.184-.582.496-.645.87l-.213 1.281c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.282c-.062-.374-.312-.686-.644-.87a6.975 6.975 0 01-1.084-.626c-.296-.212-.66-.29-1.015-.174l-1.226.425a1.124 1.124 0 01-1.364-.516l-1.296-2.244a1.124 1.124 0 01.277-1.387l1.013-.856c.299-.252.446-.634.425-1.025a6.41 6.41 0 010-.723c.02-.391-.126-.773-.425-1.025l-1.013-.856a1.124 1.124 0 01-.277-1.387l1.296-2.244a1.124 1.124 0 011.364-.516l1.226.425c.355.116.72.038 1.015-.174a6.97 6.97 0 011.084-.626c.332-.184.582-.496.644-.87l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    Settings
</a>

{{-- Appearance / Menu Builder --}}
@php $active = $current && fnmatch('admin.appearance.menu.*', $current); @endphp
<a href="{{ route('admin.appearance.menu.index') }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    Menu Builder
</a>

{{-- SEO Section --}}
@php $seoActive = $current && fnmatch('admin.seo.*', $current); @endphp
<div x-data="{ open: {{ $seoActive ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                   {{ $seoActive ? 'bg-[#083321]/10 text-[#083321] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5 shrink-0 {{ $seoActive ? 'text-[#083321]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <span class="flex-1 text-left">SEO</span>
        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open" x-collapse class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
        @php $a = $current === 'admin.seo.dashboard'; @endphp
        <a href="{{ route('admin.seo.dashboard') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Dashboard</a>
        @php $a = $current === 'admin.seo.keywords'; @endphp
        <a href="{{ route('admin.seo.keywords') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Keywords</a>
        @php $a = $current === 'admin.seo.rankings'; @endphp
        <a href="{{ route('admin.seo.rankings') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Rankings</a>
        @php $a = $current === 'admin.seo.search-engines'; @endphp
        <a href="{{ route('admin.seo.search-engines') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Search Engines</a>
        @php $a = $current === 'admin.seo.pages'; @endphp
        <a href="{{ route('admin.seo.pages') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">SEO Pages</a>
        @php $a = $current === 'admin.seo.markets'; @endphp
        <a href="{{ route('admin.seo.markets') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">GEO Markets</a>
        @php $a = $current === 'admin.seo.link-rules'; @endphp
        <a href="{{ route('admin.seo.link-rules') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Link Rules</a>
        @php $a = $current === 'admin.seo.image-seo'; @endphp
        <a href="{{ route('admin.seo.image-seo') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Image SEO</a>
        @php $a = $current === 'admin.seo.alerts'; @endphp
        <a href="{{ route('admin.seo.alerts') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Alerts</a>
        @php $a = $current === 'admin.seo.authors'; @endphp
        <a href="{{ route('admin.seo.authors') }}" class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ $a ? 'text-[#083321] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">Authors (E-E-A-T)</a>
    </div>
</div>
@endif
