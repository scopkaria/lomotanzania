{{-- UPDATED: render sections with design & animation settings from section data --}}
@if(isset($sections) && $sections->count())
    @foreach($sections as $section)
        @php
            $sectionData = $sectionDataMap[$section->id] ?? [];
            $partialName = str_replace('_', '-', $section->section_type);

            // ADDED: read design & animation settings from section JSON data
            $sData = $section->data ?? [];
            $animType   = $sData['animation_type'] ?? '';
            $animSpeed  = $sData['animation_speed'] ?? 'medium';
            $animDelay  = $sData['animation_delay'] ?? 0;
            $animEnabled = ($sData['animation_enabled'] ?? '1') !== '0';

            // Design overrides
            $designMarginTop    = $sData['design_margin_top'] ?? '';
            $designMarginBottom = $sData['design_margin_bottom'] ?? '';
            $designPadding      = $sData['design_padding'] ?? '';
            $designBgColor      = $sData['design_bg_color'] ?? '';
            $designTextColor    = $sData['design_text_color'] ?? '';
            $designAlign        = $sData['design_align'] ?? '';

            // Build wrapper styles
            $wrapperStyles = [];
            if ($designMarginTop)    $wrapperStyles[] = "margin-top:{$designMarginTop}";
            if ($designMarginBottom) $wrapperStyles[] = "margin-bottom:{$designMarginBottom}";
            if ($designPadding)      $wrapperStyles[] = "padding:{$designPadding}";
            if ($designBgColor)      $wrapperStyles[] = "background-color:{$designBgColor}";
            if ($designTextColor)    $wrapperStyles[] = "color:{$designTextColor}";
            $wrapperStyle = implode(';', $wrapperStyles);

            // Build wrapper attributes
            $wrapperAttrs = '';
            if ($animType && $animEnabled) {
                $wrapperAttrs .= ' data-animate="' . e($animType) . '"';
                if ($animSpeed && $animSpeed !== 'medium') {
                    $wrapperAttrs .= ' data-anim-speed="' . e($animSpeed) . '"';
                }
                if ($animDelay > 0) {
                    $wrapperAttrs .= ' data-anim-delay="' . (int) $animDelay . '"';
                }
            }
        @endphp

        @if(view()->exists('sections.' . $partialName))
            @if($wrapperStyle || $wrapperAttrs || $designAlign)
                <div {!! $wrapperAttrs !!}
                     @if($wrapperStyle) style="{{ $wrapperStyle }}" @endif
                     @if($designAlign) class="text-{{ $designAlign }}" @endif>
                    @include('sections.' . $partialName, ['section' => $section, 'sectionData' => $sectionData])
                </div>
            @else
                @include('sections.' . $partialName, ['section' => $section, 'sectionData' => $sectionData])
            @endif
        @endif
    @endforeach
@endif
