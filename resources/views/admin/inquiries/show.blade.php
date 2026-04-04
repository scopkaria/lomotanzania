<x-app-layout>
    <x-slot name="header">Inquiry from {{ $inquiry->name }}</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to inquiries
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Contact Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Full Name</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Email</p>
                        <a href="mailto:{{ $inquiry->email }}" class="text-sm font-semibold text-brand-gold hover:underline">{{ $inquiry->email }}</a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Country</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->country ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Trip Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Trip Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Inquiry Type</p>
                        <p class="text-sm font-semibold text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($inquiry->inquiry_type ?? 'inquiry') === 'booking' ? 'bg-brand-gold/20 text-brand-dark' : 'bg-blue-50 text-blue-700' }}">
                                {{ ucfirst($inquiry->inquiry_type ?? 'inquiry') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Safari Package</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->safariPackage?->title ?? 'General Inquiry' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Travel Date</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->travel_date?->format('M d, Y') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Number of People</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $inquiry->number_of_people ?? '—' }}</p>
                    </div>
                    @if($inquiry->contact_methods)
                        <div class="sm:col-span-2">
                            <p class="text-xs text-gray-400 mb-1">Preferred Contact Methods</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($inquiry->contact_methods as $method)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_', ' ', $method)) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Message --}}
            @if($inquiry->message)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Message</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($inquiry->message)) !!}
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Status</h3>

                <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <select name="status"
                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:border-brand-green focus:ring-brand-green/20 mb-4">
                        <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="booked" {{ $inquiry->status === 'booked' ? 'selected' : '' }}>Booked</option>
                    </select>

                    <button type="submit"
                            class="w-full bg-brand-gold text-brand-dark font-semibold py-2.5 rounded-xl hover:bg-yellow-400 transition text-sm">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Timeline</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-brand-gold shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Inquiry received</p>
                            <p class="text-xs text-gray-400">{{ $inquiry->created_at->format('M d, Y \\a\\t g:i A') }}</p>
                        </div>
                    </div>
                    @if($inquiry->updated_at->ne($inquiry->created_at))
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></div>
                            <div>
                                <p class="font-medium text-gray-900">Last updated</p>
                                <p class="text-xs text-gray-400">{{ $inquiry->updated_at->format('M d, Y \\a\\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-heading text-lg font-bold text-brand-dark mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="mailto:{{ $inquiry->email }}"
                       class="flex items-center gap-2 w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        Send Email
                    </a>
                    @if($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}"
                           class="flex items-center gap-2 w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            Call
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
