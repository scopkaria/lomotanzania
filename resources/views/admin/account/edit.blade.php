<x-app-layout>
    <x-slot name="header">Account Settings</x-slot>

    <div class="max-w-4xl space-y-8">

        {{-- Profile Section --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Profile Information</h2>
                <p class="text-sm text-gray-500 mt-1">Update your name, phone, and profile image.</p>
            </div>
            <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')

                <div class="flex items-center gap-5">
                    <div class="shrink-0">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-20 h-20 rounded-full bg-[#083321] flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#083321]/10 file:text-[#083321] hover:file:bg-[#083321]/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-gray-400">(optional)</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio <span class="text-gray-400">(shown to visitors in chat)</span></label>
                    <input type="text" name="bio" value="{{ old('bio', $user->bio) }}" placeholder="e.g. Safari specialist with 5+ years experience" maxlength="255" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                    <select name="language" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm max-w-xs">
                        <option value="en" @selected($user->language === 'en')>English</option>
                        <option value="fr" @selected($user->language === 'fr')>French</option>
                        <option value="de" @selected($user->language === 'de')>German</option>
                        <option value="es" @selected($user->language === 'es')>Spanish</option>
                    </select>
                </div>

                {{-- Notification Preferences --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notification Preferences</label>
                    <div class="space-y-2">
                        @php $prefs = $user->notification_preferences ?? []; @endphp
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="notification_preferences[email_inquiries]" value="1" @checked($prefs['email_inquiries'] ?? true) class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            Email me on new inquiries
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="notification_preferences[email_bookings]" value="1" @checked($prefs['email_bookings'] ?? true) class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            Email me on new bookings
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="notification_preferences[sound_alerts]" value="1" @checked($prefs['sound_alerts'] ?? true) class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            Play sound on new chat messages
                        </label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#083321] text-white rounded-lg text-sm font-medium hover:bg-[#083321]/90 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Email Change --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Email Address</h2>
                <p class="text-sm text-gray-500 mt-1">A verification email will be sent to the new address.</p>
            </div>
            <form action="{{ route('admin.account.update-email') }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-600">
                    Current email: <strong>{{ $user->email }}</strong>
                    @if($user->pending_email)
                        <span class="ml-2 text-amber-600"> — Pending change to: {{ $user->pending_email }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Email</label>
                        <input type="email" name="email" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition">
                        Send Verification Email
                    </button>
                </div>
            </form>
        </div>

        {{-- Password --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Change Password</h2>
                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters with mixed case and numbers.</p>
            </div>
            <form action="{{ route('admin.account.update-password') }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm max-w-md">
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#083321] focus:ring-[#083321] text-sm">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                        Change Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
