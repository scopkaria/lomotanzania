<x-app-layout>
    <x-slot name="header">Safari Plan Details</x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.safari-plans.index') }}" class="text-sm text-brand-gold hover:underline">&larr; Back to all plans</a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
            {{-- Contact Info --}}
            <div class="p-6">
                <h3 class="text-sm font-bold text-brand-dark uppercase tracking-wide mb-4">Contact Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Name</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $safariPlan->first_name }} {{ $safariPlan->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:{{ $safariPlan->email }}" class="text-brand-gold hover:underline">{{ $safariPlan->email }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->country_code }} {{ $safariPlan->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Contact Methods</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if(is_array($safariPlan->contact_methods))
                                {{ implode(', ', $safariPlan->contact_methods) }}
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Wants Updates</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->wants_updates ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Submitted</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->created_at->format('M d, Y \a\t H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Safari Preferences --}}
            <div class="p-6">
                <h3 class="text-sm font-bold text-brand-dark uppercase tracking-wide mb-4">Safari Preferences</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($safariPlan->safariPackage)
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">From Safari</dt>
                        <dd class="mt-1 text-sm font-medium text-brand-gold">{{ $safariPlan->safariPackage->title }}</dd>
                    </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Destinations</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @if(is_array($safariPlan->destinations))
                                @foreach($safariPlan->destinations as $dest)
                                    <span class="inline-block px-3 py-1 bg-brand-green/5 text-brand-green text-xs font-medium rounded-full">{{ $dest }}</span>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Travel Months</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ is_array($safariPlan->months) ? implode(', ', $safariPlan->months) : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Travel Group</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->travel_group ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Interests</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ is_array($safariPlan->interests) ? implode(', ', $safariPlan->interests) : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Budget Range</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->budget_range ?? '—' }}</dd>
                    </div>
                    @if($safariPlan->know_destination)
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wide">Know Destination?</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $safariPlan->know_destination }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Delete --}}
        <div class="mt-6 flex justify-end">
            <form action="{{ route('admin.safari-plans.destroy', $safariPlan) }}" method="POST"
                  onsubmit="return confirm('Delete this safari plan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete Plan</button>
            </form>
        </div>
    </div>
</x-app-layout>
