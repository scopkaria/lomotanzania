<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.workers.admins') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            Edit Administrator — {{ $admin->name }}
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.workers.admin-update', $admin) }}" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green" placeholder="Leave blank to keep current">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
                        <option value="">— No department —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $admin->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <input type="text" name="bio" value="{{ old('bio', $admin->bio) }}" maxlength="255" placeholder="Short description" class="w-full rounded-xl border-gray-300 text-sm focus:border-brand-green focus:ring-brand-green">
            </div>

            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                <strong>Role: Administrator</strong> — Can manage content, chats, bookings, and inquiries.
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.workers.admins') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-green text-white rounded-xl text-sm font-medium hover:bg-brand-green/90 transition">Update Administrator</button>
            </div>
        </form>
    </div>
</x-app-layout>
