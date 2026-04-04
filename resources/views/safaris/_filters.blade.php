{{-- ═══════════════════════════════════════════════════ --}}
{{-- FILTER SIDEBAR SECTIONS --}}
{{-- Shared between desktop sidebar and mobile drawer --}}
{{-- ═══════════════════════════════════════════════════ --}}

{{-- ─── 1. COUNTRY ─── --}}
<div class="mb-6" x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left group">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Country</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3 space-y-2 max-h-[200px] overflow-y-auto pr-1">
        @foreach($countries as $country)
            <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox"
                       class="filter-check w-4 h-4 rounded border-gray-300"
                       :checked="selectedCountries.includes('{{ $country->slug }}')"
                       @change="toggleFilter(selectedCountries, '{{ $country->slug }}')">
                <span class="text-sm text-brand-dark/70 group-hover/item:text-brand-dark transition">{{ $country->name }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 my-4"></div>

{{-- ─── 2. TRAVEL STYLE (Tour Types) ─── --}}
<div class="mb-6" x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Travel Style</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3 space-y-2">
        @foreach($tourTypes as $type)
            <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox"
                       class="filter-check w-4 h-4 rounded border-gray-300"
                       :checked="selectedTourTypes.includes('{{ $type->slug }}')"
                       @change="toggleFilter(selectedTourTypes, '{{ $type->slug }}')">
                <span class="text-sm text-brand-dark/70 group-hover/item:text-brand-dark transition">{{ $type->translated('name') }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 my-4"></div>

{{-- ─── 3. EXPERIENCES / INTERESTS (Categories) ─── --}}
<div class="mb-6" x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Experiences</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3 space-y-2">
        @foreach($categories as $cat)
            <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox"
                       class="filter-check w-4 h-4 rounded border-gray-300"
                       :checked="selectedCategories.includes('{{ $cat->slug }}')"
                       @change="toggleFilter(selectedCategories, '{{ $cat->slug }}')">
                <span class="text-sm text-brand-dark/70 group-hover/item:text-brand-dark transition">{{ $cat->translated('name') }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 my-4"></div>

{{-- ─── 4. MONTH ─── --}}
<div class="mb-6" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Travel Month</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3">
        <div class="grid grid-cols-3 gap-1.5">
            @foreach(['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'] as $num => $label)
                <button type="button"
                        @click="toggleFilter(selectedMonths, '{{ $num }}')"
                        :class="selectedMonths.includes('{{ $num }}') ? 'bg-brand-green text-white border-brand-green' : 'bg-white text-brand-dark/60 border-gray-200 hover:border-brand-dark/30'"
                        class="px-2 py-1.5 text-xs font-medium border rounded transition-all">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<div class="border-t border-gray-100 my-4"></div>

{{-- ─── 5. DURATION ─── --}}
<div class="mb-6" x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Duration</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3 space-y-2">
        @foreach(['1_3' => '1–3 Days', '4_7' => '4–7 Days', '8_12' => '8–12 Days', '12_plus' => '12+ Days'] as $val => $label)
            <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox"
                       class="filter-check w-4 h-4 rounded border-gray-300"
                       :checked="selectedDurations.includes('{{ $val }}')"
                       @change="toggleFilter(selectedDurations, '{{ $val }}')">
                <span class="text-sm text-brand-dark/70 group-hover/item:text-brand-dark transition">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 my-4"></div>

{{-- ─── 6. PRICE RANGE ─── --}}
<div class="mb-2" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center justify-between w-full text-left">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-brand-dark/70">Price Range</h3>
        <svg class="w-4 h-4 text-brand-dark/30 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-collapse class="mt-3 space-y-2">
        @foreach(['under_2k' => 'Under $2,000', '2k_5k' => '$2,000 – $5,000', '5k_10k' => '$5,000 – $10,000', 'over_10k' => '$10,000+'] as $val => $label)
            <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox"
                       class="filter-check w-4 h-4 rounded border-gray-300"
                       :checked="selectedPrices.includes('{{ $val }}')"
                       @change="toggleFilter(selectedPrices, '{{ $val }}')">
                <span class="text-sm text-brand-dark/70 group-hover/item:text-brand-dark transition">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>
