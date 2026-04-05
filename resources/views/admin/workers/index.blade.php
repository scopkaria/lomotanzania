<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Workers</span>
            <div class="flex gap-2">
                <a href="{{ route('admin.workers.departments') }}" class="px-3 py-1.5 bg-brand-gold/10 text-brand-dark rounded-lg text-xs font-medium hover:bg-brand-gold/20 transition">
                    Departments
                </a>
                <a href="{{ route('admin.workers.create') }}" class="px-3 py-1.5 bg-brand-green text-white rounded-lg text-xs font-medium hover:bg-brand-green/90 transition">
                    + Add Worker
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Worker</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Department</th>
                    <th class="px-5 py-3 text-left">Active Chats</th>
                    <th class="px-5 py-3 text-left">Created</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workers as $worker)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand-green/10 flex items-center justify-center text-brand-green text-sm font-bold">
                                {{ strtoupper(substr($worker->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $worker->name }}</p>
                                @if($worker->phone)
                                    <p class="text-xs text-gray-400">{{ $worker->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $worker->email }}</td>
                    <td class="px-5 py-3">
                        @if($worker->department)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium" style="background-color: {{ $worker->department->color }}15; color: {{ $worker->department->color }}">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $worker->department->color }}"></span>
                                {{ $worker->department->name }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $worker->chat_sessions_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $worker->chat_sessions_count }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $worker->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.workers.edit', $worker) }}" class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs hover:bg-gray-200 transition">Edit</a>
                            <form method="POST" action="{{ route('admin.workers.destroy', $worker) }}" onsubmit="return confirm('Delete this worker?')">
                                @csrf @method('DELETE')
                                <button class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No workers yet. Create your first worker account.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $workers->links() }}</div>
</x-app-layout>
