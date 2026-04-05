@foreach($destinations as $destination)
    <article class="dest-card group flex h-full flex-col overflow-hidden border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <a href="{{ route('destinations.show', ['locale' => app()->getLocale(), 'slug' => $destination->slug]) }}" class="block">
            <div class="aspect-[4/3] w-full overflow-hidden bg-gray-100">
                @if($destination->featured_image)
                    <img src="{{ asset('storage/' . $destination->featured_image) }}"
                         alt="{{ $destination->translated('name') }}"
                         loading="lazy"
                         class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                @else
                    <div class="flex h-full min-h-[220px] w-full items-center justify-center bg-gradient-to-br from-brand-green/20 to-brand-gold/20">
                        <svg class="h-12 w-12 text-brand-green/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                    </div>
                @endif
            </div>
        </a>

        <div class="flex flex-1 flex-col p-5">
            @if($destination->country)
                <p class="mb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-green/70">{{ $destination->country->name }}</p>
            @endif

            <h2 class="font-heading text-xl font-bold text-brand-dark transition-colors group-hover:text-brand-green">
                {{ $destination->translated('name') }}
            </h2>

            @if($destination->translated('description'))
                <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">{{ Str::limit(strip_tags($destination->translated('description')), 160) }}</p>
            @endif

            <span class="mt-auto pt-4 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-brand-green transition-all group-hover:gap-3">
                Explore destination
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>
    </article>
@endforeach
