<x-app-layout>
    <div class="max-w-6xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menu Builder</h1>
            <p class="mt-1 text-sm text-gray-500">Refine the website navigation without breaking the current design. Drag to reorder, enable or disable items, and add custom links.</p>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.5fr,1fr]">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Current navigation</h2>
                    <p class="mt-1 text-sm text-gray-500">These items appear in the main website menu. "Trekking" is intentionally removed.</p>
                </div>

                <div id="menu-sortable" class="divide-y divide-gray-100">
                    @foreach($menuItems as $item)
                        <div class="menu-row px-4 py-4" data-id="{{ $item->id }}">
                            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                                <div class="flex items-start gap-3">
                                    <button type="button" class="mt-1 cursor-move rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-gray-400" aria-label="Drag item">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h.01M8 12h.01M8 18h.01M16 6h.01M16 12h.01M16 18h.01"/></svg>
                                    </button>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $item->label }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($item->slug)
                                                Core item: <span class="font-medium">{{ $item->slug }}</span>
                                            @else
                                                Custom link: <span class="break-all">{{ $item->url }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <form action="{{ route('admin.appearance.menu.update', $item) }}" method="POST" class="grid gap-3 rounded-xl bg-gray-50 p-3 sm:grid-cols-[1.3fr,1fr,auto] xl:min-w-[420px]">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Label</label>
                                        <input type="text" name="label" value="{{ $item->label }}" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                                    </div>

                                    @if(!$item->slug)
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">URL</label>
                                            <input type="text" name="url" value="{{ $item->url }}" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                                        </div>
                                    @else
                                        <div class="flex items-center gap-4 pt-6 text-xs text-gray-600">
                                            <label class="inline-flex items-center gap-2">
                                                <input type="hidden" name="is_enabled" value="0">
                                                <input type="checkbox" name="is_enabled" value="1" {{ $item->is_enabled ? 'checked' : '' }} class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                                                Enabled
                                            </label>
                                            <label class="inline-flex items-center gap-2">
                                                <input type="hidden" name="open_in_new_tab" value="0">
                                                <input type="checkbox" name="open_in_new_tab" value="1" {{ $item->open_in_new_tab ? 'checked' : '' }} class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                                                New tab
                                            </label>
                                        </div>
                                    @endif

                                    <div class="flex items-end gap-2">
                                        @if(!$item->slug)
                                            <div class="flex flex-col gap-2 text-xs text-gray-600">
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="hidden" name="is_enabled" value="0">
                                                    <input type="checkbox" name="is_enabled" value="1" {{ $item->is_enabled ? 'checked' : '' }} class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                                                    Enabled
                                                </label>
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="hidden" name="open_in_new_tab" value="0">
                                                    <input type="checkbox" name="open_in_new_tab" value="1" {{ $item->open_in_new_tab ? 'checked' : '' }} class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                                                    New tab
                                                </label>
                                            </div>
                                        @endif

                                        <button type="submit" class="rounded-xl bg-[#083321] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0a4a30]">Save</button>
                                    </div>
                                </form>
                            </div>

                            @if(!$item->slug)
                                <div class="mt-3 flex justify-end">
                                    <form action="{{ route('admin.appearance.menu.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this custom menu item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Remove</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <form id="menu-order-form" action="{{ route('admin.appearance.menu.sort') }}" method="POST" class="border-t border-gray-100 px-6 py-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="ordered_ids" id="ordered_ids" value="{{ $menuItems->pluck('id')->implode(',') }}">
                    <button type="submit" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Save drag order</button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Add custom link</h2>
                    <p class="mt-1 text-sm text-gray-500">Useful for seasonal campaigns, promos, or partner pages.</p>

                    <form action="{{ route('admin.appearance.menu.store') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Label</label>
                            <input type="text" name="label" placeholder="e.g. Special Offers" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">URL</label>
                            <input type="text" name="url" placeholder="/en/page/offers or https://example.com" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]" required>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                            <input type="hidden" name="open_in_new_tab" value="0">
                            <input type="checkbox" name="open_in_new_tab" value="1" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                            Open in new tab
                        </label>
                        <button type="submit" class="w-full rounded-xl bg-[#083321] px-4 py-3 text-sm font-semibold text-white hover:bg-[#0a4a30]">Add menu item</button>
                    </form>
                </div>

                <div class="rounded-2xl border border-[#083321]/10 bg-[#083321]/5 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#083321]">Menu rules</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        <li>• Core items can be reordered and disabled.</li>
                        <li>• "Experiences" uses the existing tour types system — no duplicate data.</li>
                        <li>• Custom items can be added or removed anytime.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sortable = document.getElementById('menu-sortable');
                const orderedIds = document.getElementById('ordered_ids');

                if (!sortable || !orderedIds || typeof Sortable === 'undefined') {
                    return;
                }

                new Sortable(sortable, {
                    animation: 180,
                    handle: '.cursor-move',
                    onSort() {
                        const ids = [...sortable.querySelectorAll('.menu-row')].map(row => row.dataset.id);
                        orderedIds.value = ids.join(',');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
