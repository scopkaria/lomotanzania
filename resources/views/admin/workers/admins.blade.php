<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Administrators</span>
            <div class="flex gap-2">
                <a href="{{ route('admin.workers.index') }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-200 transition">
                    Workers
                </a>
                <a href="{{ route('admin.workers.departments') }}" class="px-3 py-1.5 bg-brand-gold/10 text-brand-dark rounded-lg text-xs font-medium hover:bg-brand-gold/20 transition">
                    Departments
                </a>
                <a href="{{ route('admin.workers.admin-create') }}" class="px-3 py-1.5 bg-brand-green text-white rounded-lg text-xs font-medium hover:bg-brand-green/90 transition">
                    + Add Admin
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-center gap-2 text-sm text-blue-800">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><strong>Administrators</strong> can manage content, chats, and all business sections. Only Super Admin can manage team members.</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Administrator</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Department</th>
                    <th class="px-5 py-3 text-left">Phone</th>
                    <th class="px-5 py-3 text-left">Created</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $admin->name }}</p>
                                <p class="text-xs text-blue-600 font-medium">Admin</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $admin->email }}</td>
                    <td class="px-5 py-3">
                        @if($admin->department)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium" style="background-color: {{ $admin->department->color }}15; color: {{ $admin->department->color }}">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $admin->department->color }}"></span>
                                {{ $admin->department->name }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $admin->phone ?: '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.workers.admin-edit', $admin) }}" class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs hover:bg-gray-200 transition">Edit</a>
                            <form method="POST" action="{{ route('admin.workers.admin-destroy', $admin) }}" x-data
                                  @submit.prevent="if(confirm('Delete this administrator?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No administrators yet. Create your first admin account.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $admins->links() }}</div>
</x-app-layout>
