{{--
    Table Toolbar — Search, Filters, Per-Page, Column Toggle, Create Button

    Variables:
    $search             - current search value (optional, defaults to request)
    $searchPlaceholder  - placeholder text
    $perPage            - current per_page value (optional, defaults to request)
    $createRoute        - route for Create button (optional)
    $createLabel        - label for Create button (optional)
    $columnList         - array [key => label] for column toggle (optional)
    $slot / $filters    - additional filter HTML (optional)
--}}

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap items-center gap-3">
        {{-- Search --}}
        <form method="GET" class="flex items-center">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                @if(is_string($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}"
                       placeholder="{{ $searchPlaceholder ?? 'Search...' }}"
                       class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-56 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
            </div>
        </form>

        {{-- Extra filters --}}
        @if(isset($filters))
            {{ $filters }}
        @endif
    </div>

    <div class="flex items-center gap-3">
        {{-- Per-page --}}
        <form method="GET">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
                @if(is_string($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <select name="per_page" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                @foreach([10, 25, 50, 100] as $pp)
                    <option value="{{ $pp }}" {{ (int)($perPage ?? request('per_page', 15)) === $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
                @endforeach
            </select>
        </form>

        {{-- Column toggle --}}
        @if(!empty($columnList))
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="flex items-center gap-1.5 text-sm text-gray-500 border border-gray-200 rounded-lg px-3 py-2 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                </svg>
                Columns
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-30">
                @foreach($columnList as $key => $label)
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" :checked="isVisible('{{ $key }}')" @change="toggleColumn('{{ $key }}')"
                               class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Create button --}}
        @if(!empty($createRoute))
        <a href="{{ $createRoute }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#083321] text-white text-sm font-medium rounded-lg hover:bg-[#083321]/90 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            {{ $createLabel ?? 'Add New' }}
        </a>
        @endif
    </div>
</div>
