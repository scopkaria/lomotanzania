<x-app-layout>
    <x-slot name="header">Edit: {{ $destination->name }}</x-slot>

    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="sticky top-0 z-40 mb-6 border-b border-gray-200 bg-white/95 px-4 py-4 backdrop-blur sm:px-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back to list
                    </a>
                    <a href="{{ route('destinations.show', ['locale' => app()->getLocale(), 'slug' => $destination->slug]) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-[#083321]/5 px-3 py-1.5 text-sm font-medium text-[#083321] transition hover:bg-[#083321]/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        View on site
                    </a>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#FEBC11] px-4 py-2.5 text-sm font-semibold text-[#131414] shadow-sm transition hover:bg-yellow-400">
                    Update Destination
                </button>
            </div>
        </div>
        @include('admin.destinations._form')
    </form>

</x-app-layout>
