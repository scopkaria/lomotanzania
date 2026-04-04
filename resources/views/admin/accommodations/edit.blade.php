<x-app-layout>
    <x-slot name="header">Edit Accommodation</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.accommodations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
    </div>

    <form action="{{ route('admin.accommodations.update', $accommodation) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.accommodations._form')
    </form>
</x-app-layout>