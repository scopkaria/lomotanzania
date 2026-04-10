<x-app-layout>
    <x-slot name="header">Edit: {{ $accommodation->name }}</x-slot>

    <form action="{{ route('admin.accommodations.update', $accommodation) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="sticky top-0 z-40 mb-6 border-b border-gray-200 bg-white/95 px-4 py-4 backdrop-blur sm:px-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.accommodations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back to list
                    </a>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#FEBC11] px-4 py-2.5 text-sm font-semibold text-[#131414] shadow-sm transition hover:bg-yellow-400">
                    Update Accommodation
                </button>
            </div>
        </div>
        @include('admin.accommodations._form')
    </form>
</x-app-layout>