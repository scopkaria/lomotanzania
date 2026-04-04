<x-app-layout>
    <x-slot name="header">Safari Plans</x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destinations</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Budget</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $plan->first_name }} {{ $plan->last_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $plan->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if(is_array($plan->destinations))
                                {{ implode(', ', array_slice($plan->destinations, 0, 2)) }}
                                @if(count($plan->destinations) > 2)
                                    <span class="text-gray-400">+{{ count($plan->destinations) - 2 }}</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $plan->budget_range ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $plan->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.safari-plans.show', $plan) }}" class="text-brand-gold hover:underline text-sm font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No safari plans submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($plans->hasPages())
        <div class="mt-6">
            {{ $plans->links() }}
        </div>
    @endif
</x-app-layout>
