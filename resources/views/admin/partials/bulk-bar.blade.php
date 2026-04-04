{{--
    Bulk Action Bar — Sticky bottom bar for selected items

    Variables:
    $bulkRoute  - route to POST bulk action to
    $actions    - array [value => label] e.g. ['delete' => 'Delete Selected']
--}}

<div x-show="selectionCount > 0" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 bg-[#083321] text-white rounded-xl px-5 py-3 flex items-center gap-4 shadow-2xl">
    <div class="flex items-center gap-2">
        <span class="bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full" x-text="selectionCount"></span>
        <span class="text-sm font-medium whitespace-nowrap">selected</span>
    </div>
    <div class="flex items-center gap-2">
        <form x-ref="bulkForm" method="POST" action="{{ $bulkRoute }}">
            @csrf
            <input type="hidden" name="action" value="">
            <div class="flex items-center gap-2">
                @foreach($actions ?? ['delete' => 'Delete'] as $value => $label)
                    <button type="button" @click="submitBulk('{{ $value }}')"
                            class="{{ $value === 'delete' ? 'bg-red-500 hover:bg-red-600' : 'bg-white/20 hover:bg-white/30' }} text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>
    <button @click="selected = []; selectAll = false" class="text-white/50 hover:text-white ml-1" title="Clear selection">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
