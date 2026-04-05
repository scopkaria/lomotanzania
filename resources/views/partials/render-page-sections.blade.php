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
@endif
