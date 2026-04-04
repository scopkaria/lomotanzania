{{-- Custom HTML Section --}}
@php
    $locale = app()->getLocale();
    $html = $section->getData('html', $locale);
@endphp

@if($html)
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-6">
        {!! $html !!}
    </div>
</section>
@endif
