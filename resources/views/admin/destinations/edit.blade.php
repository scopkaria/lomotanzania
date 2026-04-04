<x-app-layout>
    <x-slot name="header">Edit: {{ $destination->name }}</x-slot>

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
        <a href="{{ route('destinations.show', $destination->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-green text-white text-xs font-semibold rounded-lg hover:bg-green-800 transition shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            View Page
        </a>
    </div>

    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.destinations._form')
    </form>

</x-app-layout>
