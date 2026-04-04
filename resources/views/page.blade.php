@extends('layouts.app')

@section('title', $page->translatedTitle())

@push('jsonld')
@if(!empty($page->meta['schema_type']))
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@type' => $page->meta['schema_type'],
    '@@context' => 'https://schema.org',
    'name' => $page->meta['schema_name'] ?? $page->translatedTitle(),
    'description' => $page->meta['schema_description'] ?? $page->meta_description,
    'url' => request()->url(),
    'areaServed' => $page->meta['schema_area'] ?? null,
    'founder' => isset($page->meta['schema_founder']) ? ['@@type' => 'Person', 'name' => $page->meta['schema_founder']] : null,
    'image' => $page->og_image ? asset('storage/' . $page->og_image) : null,
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endpush

@section('content')
<div class="min-h-screen">
    @if(isset($sections) && $sections->count())
        @foreach($sections as $section)
            @php
                $sectionData = $sectionDataMap[$section->id] ?? [];
                $partialName = str_replace('_', '-', $section->section_type);
            @endphp

            @if(view()->exists('sections.' . $partialName))
                @include('sections.' . $partialName, ['section' => $section, 'sectionData' => $sectionData])
            @endif
        @endforeach
    @else
        {{-- Fallback if no sections --}}
        <section class="py-20 text-center">
            <div class="max-w-4xl mx-auto px-6">
                <h1 class="font-heading text-4xl font-bold text-[#131414] mb-4">{{ $page->translatedTitle() }}</h1>
                <p class="text-gray-500">This page has no content sections yet.</p>
            </div>
        </section>
    @endif
</div>
@endsection
