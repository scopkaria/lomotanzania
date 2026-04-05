<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.workers.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            Departments
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Create Department --}}
        <div class="lg:col-span-1">
            <form method="POST" action="{{ route('admin.workers.departments.store') }}" class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
                @csrf
                <h3 class="font-semibold text-gray-800 text-sm">New Department</h3>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <input type="text" name="description" class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" value="#083321" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                        <span class="text-xs text-gray-400">Used for badges & UI</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-brand-green text-white rounded-lg text-sm font-medium hover:bg-brand-green/90 transition">Create Department</button>
            </form>
        </div>

        {{-- Departments List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">All Departments</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($departments as $dept)
                    <div class="px-5 py-4 flex items-center justify-between" x-data="{ editing: false }">
                        <div x-show="!editing" class="flex items-center gap-3">
                            <span class="w-4 h-4 rounded-full shrink-0" style="background-color: {{ $dept->color }}"></span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $dept->name }}</p>
                                <p class="text-xs text-gray-400">{{ $dept->description }} · {{ $dept->workers_count }} worker{{ $dept->workers_count !== 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        <div x-show="!editing" class="flex gap-2">
                            <button @click="editing = true" class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs hover:bg-gray-200 transition">Edit</button>
                            <form method="POST" action="{{ route('admin.workers.departments.destroy', $dept) }}" onsubmit="return confirm('Delete this department?')">
                                @csrf @method('DELETE')
                                <button class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition">Delete</button>
                            </form>
                        </div>
                        {{-- Inline edit form --}}
                        <form x-show="editing" x-cloak method="POST" action="{{ route('admin.workers.departments.update', $dept) }}" class="flex-1 flex items-center gap-3">
                            @csrf @method('PUT')
                            <input type="color" name="color" value="{{ $dept->color }}" class="w-8 h-8 rounded border border-gray-200 shrink-0">
                            <input type="text" name="name" value="{{ $dept->name }}" required class="flex-1 rounded-lg border-gray-300 text-sm">
                            <input type="text" name="description" value="{{ $dept->description }}" placeholder="Description" class="flex-1 rounded-lg border-gray-300 text-sm">
                            <button type="submit" class="px-3 py-1.5 bg-brand-green text-white rounded-lg text-xs hover:bg-brand-green/90">Save</button>
                            <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs">Cancel</button>
                        </form>
                    </div>
                    @empty
                    <div class="px-5 py-12 text-center text-gray-400 text-sm">No departments yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
