<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $safari->translated('title') }} - Safari Itinerary</title>
    <style>
        @font-face {
            font-family: 'LomoHeading';
            src: url('{{ public_path('fonts/georgiab.ttf') }}');
            font-weight: bold;
        }
        @font-face {
            font-family: 'LomoHeading';
            src: url('{{ public_path('fonts/georgia.ttf') }}');
            font-weight: normal;
        }
        @font-face {
            font-family: 'LomoBody';
            src: url('{{ public_path('fonts/arial.ttf') }}');
            font-weight: normal;
        }
        @font-face {
            font-family: 'LomoBody';
            src: url('{{ public_path('fonts/arialbd.ttf') }}');
            font-weight: bold;
        }

        /* ═══════════════════════════════════════
           LUXURY BROCHURE — BASE SYSTEM
        ═══════════════════════════════════════ */
        @page { margin: 0; size: A4 portrait; }
        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            font-family: 'LomoBody', Arial, sans-serif;
            color: #131414;
            font-size: 12px;
            line-height: 1.7;
        }

        img { border: none; display: block; }
        a { color: inherit; text-decoration: none; }

        /* ── Typography hierarchy ── */
        h1, h2, h3, h4 {
            margin: 0 0 12px;
            font-family: 'LomoHeading', Georgia, serif;
            font-weight: 700;
            color: #083321;
        }
        p { margin: 0 0 10px; }

        .h1 { font-size: 30px; line-height: 1.2; letter-spacing: 1px; color: #083321; margin-bottom: 14px; font-family: 'LomoHeading', Georgia, serif; font-weight: 700; }
        .h2 { font-size: 20px; line-height: 1.3; color: #083321; margin-bottom: 10px; font-family: 'LomoHeading', Georgia, serif; font-weight: 700; }
        .body-text { font-size: 12px; line-height: 1.7; color: #131414; }
        .small-text { font-size: 10px; line-height: 1.5; color: #777777; }
        .label-text { font-size: 9px; letter-spacing: 3px; text-transform: uppercase; color: #999888; margin-bottom: 8px; }
        .accent-line { width: 48px; height: 2px; background: #FEBC11; margin-bottom: 20px; }
        .accent-line-center { width: 48px; height: 2px; background: #FEBC11; margin: 0 auto 20px; }

        /* ── Layout ── */
        .page-break { page-break-before: always; }
        .page { position: relative; width: 794px; height: 1122px; overflow: hidden; }

        /* ── Content frame: 3-row table pins header/footer ── */
        .content-frame { width: 794px; height: 1122px; border-collapse: collapse; background: #faf8f4; }
        .content-frame td { padding: 0; }
        .content-frame .hdr-cell { height: 52px; vertical-align: top; }
        .content-frame .body-cell { vertical-align: top; height: 1026px; }
        .content-frame .ftr-cell { height: 44px; vertical-align: bottom; }

        /* ── Refined header ── */
        .page-header {
            padding: 14px 56px 10px;
            border-bottom: 1px solid #e8e2d6;
        }
        .page-header table { width: 100%; border-collapse: collapse; }
        .page-header td { vertical-align: middle; }
        .page-header .ph-logo img { height: 22px; width: auto; }
        .page-header .ph-logo-text {
            font-family: 'LomoHeading', Georgia, serif;
            font-size: 10px; font-weight: 700; color: #083321;
            letter-spacing: 3px; text-transform: uppercase;
        }
        .page-header .ph-title {
            text-align: right; font-size: 8px;
            letter-spacing: 2px; text-transform: uppercase; color: #aaa49a;
        }

        /* ── Refined footer ── */
        .page-footer {
            padding: 10px 56px 12px;
            border-top: 1px solid #e8e2d6;
        }
        .page-footer table { width: 100%; border-collapse: collapse; }
        .page-footer td { vertical-align: middle; font-size: 8px; color: #b0a898; letter-spacing: 1px; }
        .page-footer .pf-right { text-align: right; }

        /* ── Page body ── */
        .page-body { padding: 32px 56px 20px; }

        /* ═══════════════════════════════════════
           COVER PAGE
        ═══════════════════════════════════════ */
        .cover { background: #0a1f15; }
        .cover-overlay {
            position: absolute; top: 0; left: 0; width: 794px; height: 1122px;
            background: rgba(8,51,33,0.30);
        }
        .cover-gradient {
            position: absolute; bottom: 0; left: 0; width: 794px; height: 560px;
            background: rgba(8,51,33,0.55);
        }
        .cover-logo {
            position: absolute; top: 48px; left: 0; width: 794px; text-align: center;
        }
        .cover-logo img { display: inline-block; width: auto; height: 44px; }
        .cover-logo-text {
            font-family: 'LomoHeading', Georgia, serif;
            font-size: 14px; font-weight: 700; color: #ffffff;
            letter-spacing: 5px; text-transform: uppercase;
        }
        .cover-bottom {
            position: absolute; bottom: 90px; left: 80px; width: 634px;
            text-align: center; color: #ffffff;
        }
        .cover-country {
            margin-bottom: 14px; font-size: 10px;
            letter-spacing: 6px; text-transform: uppercase; color: #FEBC11;
        }
        .cover-title {
            font-family: 'LomoHeading', Georgia, serif;
            font-size: 34px; line-height: 1.2; color: #ffffff;
            letter-spacing: 1px; margin-bottom: 16px;
        }
        .cover-subtitle {
            margin: 0 auto; max-width: 460px;
            font-size: 11px; line-height: 1.8; color: rgba(255,255,255,0.75);
        }
        .cover-divider { width: 48px; height: 2px; background: #FEBC11; margin: 22px auto 0; }
        .cover-badge {
            margin-bottom: 18px; display: inline-block;
            padding: 4px 16px; border: 1px solid rgba(255,255,255,0.25);
            font-size: 9px; letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.7);
        }

        /* ═══════════════════════════════════════
           LISTS — INLINE ICONS
        ═══════════════════════════════════════ */
        .highlight-list, .route-list, .included-list, .excluded-list {
            margin: 0; padding: 0; list-style: none;
        }
        .highlight-list li {
            margin-bottom: 10px; font-size: 12px; line-height: 1.6; color: #131414;
        }
        .route-list li {
            margin-bottom: 8px; font-size: 11px; line-height: 1.5; color: #131414;
        }
        .included-list li, .excluded-list li {
            margin-bottom: 8px; font-size: 11px; line-height: 1.6; color: #131414;
        }
        .list-icon {
            font-family: DejaVu Sans, sans-serif;
            margin-right: 8px; font-size: 10px; display: inline;
        }
        .list-icon-star { color: #FEBC11; }
        .list-icon-check { color: #083321; }
        .list-icon-cross { color: #9d3822; }

        /* ═══════════════════════════════════════
           OVERVIEW
        ═══════════════════════════════════════ */
        .overview-copy {
            font-size: 12px; line-height: 1.8; color: #2d2a25;
        }
        .overview-copy p, .overview-copy ul, .overview-copy ol { margin: 0 0 12px; }
        .overview-copy ul, .overview-copy ol { padding-left: 20px; }

        /* ═══════════════════════════════════════
           MAP + ROUTE
        ═══════════════════════════════════════ */
        .map-intro {
            margin-bottom: 20px; font-size: 11px; line-height: 1.7; color: #777777;
        }
        .map-route-table { width: 100%; border-collapse: collapse; }
        .map-route-table td { vertical-align: top; }
        .map-cell { width: 66%; padding-right: 30px; }
        .route-cell { width: 34%; padding-left: 30px; border-left: 1px solid #e8e2d6; }
        .route-cell-title {
            margin-bottom: 12px; font-size: 9px;
            letter-spacing: 3px; text-transform: uppercase;
            color: #999888; font-weight: 700;
        }
        .map-static-img { width: 100%; height: auto; }

        /* ═══════════════════════════════════════
           ITINERARY
        ═══════════════════════════════════════ */
        .itinerary-heading {
            margin-bottom: 8px; text-align: center;
            font-size: 28px; letter-spacing: 2px;
            font-family: 'LomoHeading', Georgia, serif;
            color: #083321;
        }
        .itinerary-sub {
            text-align: center; font-size: 11px; color: #777777;
            line-height: 1.6; margin-bottom: 30px;
        }
        .itinerary-table { width: 100%; border-collapse: collapse; }
        .itinerary-table td { width: 50%; vertical-align: top; }
        .itinerary-row {
            margin-bottom: 28px; padding-bottom: 28px;
            border-bottom: 1px solid #ece6da;
            page-break-inside: avoid;
        }
        .itinerary-row:last-child { border-bottom: none; }
        .itinerary-text { padding-right: 18px; }
        .itinerary-image-wrap { padding-left: 18px; }
        .itinerary-table.is-reversed .itinerary-text { padding-right: 0; padding-left: 18px; }
        .itinerary-table.is-reversed .itinerary-image-wrap { padding-left: 0; padding-right: 18px; }

        .day-kicker {
            margin-bottom: 6px; font-size: 9px;
            letter-spacing: 4px; text-transform: uppercase; color: #FEBC11;
            font-weight: 700;
        }
        .day-title {
            margin-bottom: 10px; font-size: 17px; line-height: 1.3;
            font-family: 'LomoHeading', Georgia, serif; color: #083321;
        }
        .day-description { font-size: 11px; line-height: 1.75; color: #3d3a35; }
        .day-meta { margin-top: 14px; }
        .day-meta-item {
            margin-bottom: 4px; font-size: 10px; color: #777777;
        }
        .day-meta-label {
            font-weight: 700; color: #083321;
            text-transform: uppercase; letter-spacing: 1px; font-size: 8px;
        }
        .itinerary-img { width: 100%; height: auto; }
        .image-placeholder {
            height: 160px; background: #f0ebe2; color: #bbb5a8;
            text-align: center; font-size: 9px; line-height: 160px;
            text-transform: uppercase; letter-spacing: 3px;
        }

        /* ═══════════════════════════════════════
           ACCOMMODATION
        ═══════════════════════════════════════ */
        .accommodation-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .accommodation-cell { width: 46%; vertical-align: top; padding: 0 14px 14px; }
        .accommodation-cell:first-child { padding-left: 0; padding-right: 14px; }
        .accommodation-cell:last-child { padding-right: 0; padding-left: 14px; }
        .accommodation-card { background: #ffffff; page-break-inside: avoid; }
        .accommodation-image { height: 150px; overflow: hidden; }
        .accommodation-image img { width: 100%; height: auto; }
        .accommodation-body { padding: 14px 0 0; }
        .accommodation-title {
            margin-bottom: 6px; font-size: 14px;
            font-family: 'LomoHeading', Georgia, serif; color: #083321;
        }
        .accommodation-copy { font-size: 10px; line-height: 1.6; color: #777777; }

        /* ═══════════════════════════════════════
           INCLUSION
        ═══════════════════════════════════════ */
        .inclusion-table { width: 100%; border-collapse: collapse; }
        .inclusion-table td { width: 50%; vertical-align: top; }
        .inclusion-table td:first-child { padding-right: 20px; }
        .inclusion-table td:last-child { padding-left: 20px; }
        .inclusion-box { padding: 0; }
        .box-title {
            margin-bottom: 14px; font-size: 16px;
            font-family: 'LomoHeading', Georgia, serif; color: #083321;
        }

        /* ═══════════════════════════════════════
           CTA PAGE
        ═══════════════════════════════════════ */
        .cta-label {
            font-size: 9px; letter-spacing: 4px;
            text-transform: uppercase; color: rgba(255,255,255,0.4);
            margin-bottom: 16px;
        }
        .cta-title {
            margin-bottom: 18px; font-size: 30px;
            font-family: 'LomoHeading', Georgia, serif;
            color: #ffffff; line-height: 1.3;
        }
        .cta-copy {
            margin: 0 auto 36px; max-width: 420px;
            font-size: 12px; line-height: 1.8; color: rgba(255,255,255,0.65);
        }
        .cta-button {
            display: inline-block; margin: 0 6px;
            padding: 12px 28px; border: 1px solid rgba(255,255,255,0.3);
            font-size: 9px; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            color: #ffffff;
        }
        .cta-button.primary {
            background: #FEBC11; color: #083321;
            border-color: #FEBC11;
        }

        /* ═══════════════════════════════════════
           BACK COVER
        ═══════════════════════════════════════ */
        .back-logo img { display: inline-block; width: auto; height: 48px; margin-bottom: 24px; }
        .back-logo-text {
            font-family: 'LomoHeading', Georgia, serif;
            font-size: 16px; font-weight: 700; color: #ffffff;
            letter-spacing: 5px; text-transform: uppercase;
            margin-bottom: 24px;
        }
        .back-divider { width: 48px; height: 2px; background: #FEBC11; margin: 0 auto 28px; }
        .back-tagline {
            margin: 0 auto 20px; font-size: 11px;
            letter-spacing: 3px; text-transform: uppercase;
            color: rgba(255,255,255,0.35);
        }
        .back-title {
            margin-bottom: 12px; font-size: 22px;
            font-family: 'LomoHeading', Georgia, serif; color: #ffffff;
        }
        .back-subtitle {
            margin: 0 auto 28px; max-width: 400px;
            font-size: 11px; line-height: 1.7; color: rgba(255,255,255,0.5);
        }
        .back-button {
            display: inline-block; padding: 12px 32px;
            border: 1px solid rgba(255,255,255,0.3);
            font-size: 9px; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            color: #ffffff;
        }
        .back-end {
            margin-top: 36px; font-size: 9px;
            letter-spacing: 4px; text-transform: uppercase;
            color: rgba(255,255,255,0.25);
        }

        /* ── Pricing table ── */
        .pricing-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .pricing-table th {
            padding: 10px 12px; text-align: left;
            background: #083321; color: #ffffff;
            font-size: 9px; letter-spacing: 1px; text-transform: uppercase;
        }
        .pricing-table td {
            padding: 10px 12px; font-size: 11px; color: #131414;
            border-bottom: 1px solid #ece6da;
        }
    </style>
</head>
<body>
    @php
        /**
         * Resolve a storage-relative path to a local filesystem path DomPDF can read.
         */
        $resolveImage = function (?string $relativePath): ?string {
            if (! filled($relativePath)) return null;
            $path = storage_path('app/public/' . $relativePath);
            if (! file_exists($path)) return null;
            $real = realpath($path);
            return $real ? str_replace('\\', '/', $real) : null;
        };

        /**
         * Decode potentially multi-encoded HTML entities (up to 5 passes).
         */
        $decodeHtml = function (?string $html): string {
            if (! filled($html)) return '';
            $decoded = $html;
            for ($i = 0; $i < 5; $i++) {
                $next = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($next === $decoded) break;
                $decoded = $next;
            }
            return $decoded;
        };

        /**
         * Return clean plaintext (decode entities then strip HTML tags).
         */
        $cleanText = function (?string $html) use ($decodeHtml): string {
            return trim(strip_tags($decodeHtml($html)));
        };

        $coverImageUri  = $resolveImage($safari->featured_image);

        // Cover-fit: simulates object-fit: cover for DomPDF
        $coverStyle = 'position:absolute;top:0;left:0;width:794px;height:1122px;';
        if ($coverImageUri && ($coverSize = @getimagesize($coverImageUri))) {
            [$imgW, $imgH] = $coverSize;
            $imgRatio = $imgW / $imgH;
            $pageRatio = 794 / 1122;
            if ($imgRatio > $pageRatio) {
                $rH = 1122;
                $rW = (int) round(1122 * $imgRatio);
                $oX = (int) round((794 - $rW) / 2);
                $coverStyle = "position:absolute;top:0;left:{$oX}px;width:{$rW}px;height:{$rH}px;";
            } else {
                $rW = 794;
                $rH = (int) round(794 / $imgRatio);
                $oY = (int) round((1122 - $rH) / 2);
                $coverStyle = "position:absolute;top:{$oY}px;left:0;width:{$rW}px;height:{$rH}px;";
            }
        }

        $overviewHtml   = $decodeHtml($safari->full_description ?? $safari->description);
        $highlights     = is_array($safari->highlights) ? array_values(array_filter($safari->highlights)) : [];
        $included       = is_array($safari->included)   ? array_values(array_filter($safari->included))   : [];
        $excluded       = is_array($safari->excluded)    ? array_values(array_filter($safari->excluded))   : [];
        $pricing        = is_array($safari->seasonal_pricing) ? $safari->seasonal_pricing : [];
        $currency       = $safari->currency ?: 'USD';
        $countryText    = $countryLabel ?: 'Tanzania';
        $logoUri        = $resolveImage(optional($setting ?? null)->logo_path);
        $siteName       = optional($setting ?? null)->site_name ?: 'Lomo Tanzania Safari';
        $staticMapUri   = $mapImageUri ?? null;
        $durationText   = $safari->duration ? $safari->duration . ' Days' : '';
        $pageNum        = 0;
    @endphp


    {{-- ═══════════════════════════════════════════════
         PAGE 1 — COVER
    ═══════════════════════════════════════════════ --}}
    <div class="page cover">
        @if($coverImageUri)
            <img src="{{ $coverImageUri }}" style="{{ $coverStyle }}" alt="">
        @endif
        <div class="cover-overlay"></div>
        <div class="cover-gradient"></div>

        <div class="cover-logo">
            @if($logoUri)
                <img src="{{ $logoUri }}" alt="Logo">
            @else
                <span class="cover-logo-text">{{ $siteName }}</span>
            @endif
        </div>

        <div class="cover-bottom">
            <div class="cover-country">{{ $countryText }}</div>
            @if(filled($durationText))
                <div class="cover-badge">{{ $durationText }}</div>
            @endif
            <div class="cover-title">{{ $safari->translated('title') }}</div>
            @if(filled($safari->translated('short_description')))
                <div class="cover-subtitle">{{ $cleanText($safari->translated('short_description')) }}</div>
            @endif
            <div class="cover-divider"></div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════
         PAGE 2 — OVERVIEW + HIGHLIGHTS
    ═══════════════════════════════════════════════ --}}
    @php $pageNum++; @endphp
    <table class="page-break content-frame">
        <tr><td class="hdr-cell">
            <div class="page-header"><table><tr>
                <td class="ph-logo">
                    @if($logoUri)<img src="{{ $logoUri }}" alt="Logo">@else<span class="ph-logo-text">{{ $siteName }}</span>@endif
                </td>
                <td class="ph-title">{{ $safari->translated('title') }}</td>
            </tr></table></div>
        </td></tr>
        <tr><td class="body-cell">
            <div class="page-body">
                <div class="label-text">Editorial Overview</div>
                <div class="h1">{{ $safari->translated('overview_title') ?: 'Tour Overview' }}</div>
                <div class="accent-line"></div>
                <div class="overview-copy">{!! $overviewHtml !!}</div>

                @if(count($highlights))
                    <div style="margin-top: 30px;">
                        <div class="label-text">Signature Moments</div>
                        <div class="h2">Highlights</div>
                        <div class="accent-line"></div>
                        <ul class="highlight-list">
                            @foreach($highlights as $highlight)
                                <li><span class="list-icon list-icon-star">&#9733;</span>{{ $highlight }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </td></tr>
        <tr><td class="ftr-cell">
            <div class="page-footer"><table><tr>
                <td>{{ $siteName }}</td>
                <td class="pf-right">Page {{ $pageNum }}</td>
            </tr></table></div>
        </td></tr>
    </table>


    {{-- ═══════════════════════════════════════════════
         PAGE 3 — JOURNEY ROUTE
    ═══════════════════════════════════════════════ --}}
    @php $pageNum++; @endphp
    <table class="page-break content-frame">
        <tr><td class="hdr-cell">
            <div class="page-header"><table><tr>
                <td class="ph-logo">
                    @if($logoUri)<img src="{{ $logoUri }}" alt="Logo">@else<span class="ph-logo-text">{{ $siteName }}</span>@endif
                </td>
                <td class="ph-title">{{ $safari->translated('title') }}</td>
            </tr></table></div>
        </td></tr>
        <tr><td class="body-cell">
            <div class="page-body">
                <div class="label-text">Safari Route</div>
                <div class="h1">Journey Route</div>
                <div class="accent-line"></div>
                <div class="map-intro">Follow your journey across Tanzania's iconic landscapes</div>

                <table class="map-route-table">
                    <tr>
                        <td class="map-cell">
                            @if($staticMapUri)
                                <img src="{{ $staticMapUri }}" class="map-static-img" alt="Route Map">
                            @else
                                <div style="background: #f0ebe2; padding: 24px; min-height: 260px;">
                                    <div class="label-text">Safari Route Overview</div>
                                    @if($routeStops->count())
                                        @foreach($routeStops as $index => $stop)
                                            <div style="margin-bottom: 12px; padding-left: 24px; position: relative;">
                                                <div style="position: absolute; left: 0; top: 3px; width: 10px; height: 10px; background: {{ ($index === 0 || $index === $routeStops->count() - 1) ? '#FEBC11' : '#083321' }};"></div>
                                                @if($index < $routeStops->count() - 1)
                                                    <div style="position: absolute; left: 4px; top: 15px; width: 2px; height: 16px; background: #083321;"></div>
                                                @endif
                                                <div style="font-size: 12px; font-weight: 700; color: #083321; font-family: 'LomoHeading', Georgia, serif;">{{ $stop['name'] }}</div>
                                                <div style="font-size: 10px; color: #777777;">{{ $stop['label'] }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="small-text">Route map will appear once destinations are assigned.</p>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="route-cell">
                            <div class="route-cell-title">Route Summary</div>
                            @if($routeStops->count())
                                <ul class="route-list">
                                    @foreach($routeStops as $stop)
                                        <li>{{ $stop['label'] }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="small-text">Route summary will appear once destinations are assigned.</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </td></tr>
        <tr><td class="ftr-cell">
            <div class="page-footer"><table><tr>
                <td>{{ $siteName }}</td>
                <td class="pf-right">Page {{ $pageNum }}</td>
            </tr></table></div>
        </td></tr>
    </table>


    {{-- ═══════════════════════════════════════════════
         PAGE 4+ — ITINERARY
    ═══════════════════════════════════════════════ --}}
    @php $pageNum++; @endphp
    <div class="page-break" style="background: #faf8f4; padding: 0;">
        <div class="page-header"><table><tr>
            <td class="ph-logo">
                @if($logoUri)<img src="{{ $logoUri }}" alt="Logo">@else<span class="ph-logo-text">{{ $siteName }}</span>@endif
            </td>
            <td class="ph-title">{{ $safari->translated('title') }}</td>
        </tr></table></div>

        <div class="page-body">
            <div class="itinerary-heading">Itinerary</div>
            <div class="accent-line-center"></div>
            <div class="itinerary-sub">Your day-by-day journey through {{ $countryText }}</div>

            @foreach($itineraryDays as $day)
                @php
                    $accommodationImagePath = $day->accommodationRelation ? optional($day->accommodationRelation->images->first())->image_path : null;
                    $dayImagePath = $day->image_path ?: $accommodationImagePath ?: $safari->featured_image;
                    $dayImageUri = $resolveImage($dayImagePath);
                    $dayDesc = $decodeHtml($day->translated('description'));
                    $isReversed = $loop->even;
                @endphp
                <div class="itinerary-row">
                    <table class="itinerary-table{{ $isReversed ? ' is-reversed' : '' }}">
                        <tr>
                            @if(! $isReversed)
                                <td class="itinerary-text">
                                    <div class="day-kicker">Day {{ $day->day_number }}</div>
                                    <div class="day-title">{{ $day->translated('title') ?: ('Safari Day ' . $day->day_number) }}</div>
                                    @if(filled($dayDesc))
                                        <div class="day-description">{!! $dayDesc !!}</div>
                                    @endif
                                    <div class="day-meta">
                                        @if($day->destination)
                                            <div class="day-meta-item"><span class="day-meta-label">Destination</span> &middot; {{ $day->destination->translated('name') }}</div>
                                        @endif
                                        @if($day->accommodationRelation)
                                            <div class="day-meta-item"><span class="day-meta-label">Accommodation</span> &middot; {{ $day->accommodationRelation->translated('name') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="itinerary-image-wrap">
                                    @if($dayImageUri)
                                        <img src="{{ $dayImageUri }}" class="itinerary-img" alt="">
                                    @else
                                        <div class="image-placeholder">Safari Detail</div>
                                    @endif
                                </td>
                            @else
                                <td class="itinerary-image-wrap">
                                    @if($dayImageUri)
                                        <img src="{{ $dayImageUri }}" class="itinerary-img" alt="">
                                    @else
                                        <div class="image-placeholder">Safari Detail</div>
                                    @endif
                                </td>
                                <td class="itinerary-text">
                                    <div class="day-kicker">Day {{ $day->day_number }}</div>
                                    <div class="day-title">{{ $day->translated('title') ?: ('Safari Day ' . $day->day_number) }}</div>
                                    @if(filled($dayDesc))
                                        <div class="day-description">{!! $dayDesc !!}</div>
                                    @endif
                                    <div class="day-meta">
                                        @if($day->destination)
                                            <div class="day-meta-item"><span class="day-meta-label">Destination</span> &middot; {{ $day->destination->translated('name') }}</div>
                                        @endif
                                        @if($day->accommodationRelation)
                                            <div class="day-meta-item"><span class="day-meta-label">Accommodation</span> &middot; {{ $day->accommodationRelation->translated('name') }}</div>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════
         ACCOMMODATION
    ═══════════════════════════════════════════════ --}}
    @php $pageNum++; @endphp
    <table class="page-break content-frame">
        <tr><td class="hdr-cell">
            <div class="page-header"><table><tr>
                <td class="ph-logo">
                    @if($logoUri)<img src="{{ $logoUri }}" alt="Logo">@else<span class="ph-logo-text">{{ $siteName }}</span>@endif
                </td>
                <td class="ph-title">{{ $safari->translated('title') }}</td>
            </tr></table></div>
        </td></tr>
        <tr><td class="body-cell">
            <div class="page-body">
                <div class="label-text">Where You'll Stay</div>
                <div class="h1">Accommodation</div>
                <div class="accent-line"></div>

                @if($accommodations->count())
                    @foreach($accommodations->chunk(2) as $chunk)
                        <table class="accommodation-table">
                            <tr>
                                @foreach($chunk as $accommodation)
                                    @php $cardImageUri = $resolveImage(optional($accommodation->images->first())->image_path); @endphp
                                    <td class="accommodation-cell">
                                        <div class="accommodation-card">
                                            <div class="accommodation-image">
                                                @if($cardImageUri)
                                                    <img src="{{ $cardImageUri }}" alt="{{ $accommodation->translated('name') }}">
                                                @else
                                                    <div class="image-placeholder">Accommodation</div>
                                                @endif
                                            </div>
                                            <div class="accommodation-body">
                                                <div class="accommodation-title">{{ $accommodation->translated('name') }}</div>
                                                <div class="accommodation-copy">{{ \Illuminate\Support\Str::limit(trim(strip_tags((string) $accommodation->translated('description'))), 100) ?: 'Selected for comfort and proximity to the day\'s experience.' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                                @if($chunk->count() < 2)
                                    <td class="accommodation-cell"></td>
                                @endif
                            </tr>
                        </table>
                    @endforeach
                @else
                    <p class="small-text">Accommodation recommendations will be finalized with your chosen travel dates.</p>
                @endif
            </div>
        </td></tr>
        <tr><td class="ftr-cell">
            <div class="page-footer"><table><tr>
                <td>{{ $siteName }}</td>
                <td class="pf-right">Page {{ $pageNum }}</td>
            </tr></table></div>
        </td></tr>
    </table>


    {{-- ═══════════════════════════════════════════════
         INCLUDED / EXCLUDED + PRICING
    ═══════════════════════════════════════════════ --}}
    @php $pageNum++; @endphp
    <table class="page-break content-frame">
        <tr><td class="hdr-cell">
            <div class="page-header"><table><tr>
                <td class="ph-logo">
                    @if($logoUri)<img src="{{ $logoUri }}" alt="Logo">@else<span class="ph-logo-text">{{ $siteName }}</span>@endif
                </td>
                <td class="ph-title">{{ $safari->translated('title') }}</td>
            </tr></table></div>
        </td></tr>
        <tr><td class="body-cell">
            <div class="page-body">
                <div class="label-text">Package Details</div>
                <div class="h1">What's Included</div>
                <div class="accent-line"></div>

                <table class="inclusion-table">
                    <tr>
                        <td>
                            <div class="inclusion-box">
                                <div class="box-title">Included</div>
                                @if(count($included))
                                    <ul class="included-list">
                                        @foreach($included as $item)<li><span class="list-icon list-icon-check">&#10003;</span>{{ $item }}</li>@endforeach
                                    </ul>
                                @else
                                    <p class="small-text">Inclusions will be confirmed with your final proposal.</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="inclusion-box">
                                <div class="box-title">Excluded</div>
                                @if(count($excluded))
                                    <ul class="excluded-list">
                                        @foreach($excluded as $item)<li><span class="list-icon list-icon-cross">&#10007;</span>{{ $item }}</li>@endforeach
                                    </ul>
                                @else
                                    <p class="small-text">Items not included will be outlined before confirmation.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                @if(! empty($pricing))
                    <div style="margin-top: 30px;">
                        <div class="label-text">Indicative Pricing</div>
                        <table class="pricing-table">
                            <thead>
                                <tr>
                                    <th>Season</th>
                                    <th>2 Pax</th>
                                    <th>4 Pax</th>
                                    <th>6 Pax</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pricing as $seasonKey => $season)
                                    @php
                                        $seasonName = is_string($seasonKey) ? ucfirst($seasonKey) . ' Season' : ($season['season'] ?? $season['label'] ?? 'Season');
                                        $pax2 = $season['pax_2'] ?? $season['2_pax'] ?? $season['price_2pax'] ?? null;
                                        $pax4 = $season['pax_4'] ?? $season['4_pax'] ?? $season['price_4pax'] ?? null;
                                        $pax6 = $season['pax_6'] ?? $season['6_pax'] ?? $season['price_6pax'] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $seasonName }}</td>
                                        <td>{{ filled($pax2) ? $currency . ' ' . number_format((float) $pax2, 0) : '—' }}</td>
                                        <td>{{ filled($pax4) ? $currency . ' ' . number_format((float) $pax4, 0) : '—' }}</td>
                                        <td>{{ filled($pax6) ? $currency . ' ' . number_format((float) $pax6, 0) : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </td></tr>
        <tr><td class="ftr-cell">
            <div class="page-footer"><table><tr>
                <td>{{ $siteName }}</td>
                <td class="pf-right">Page {{ $pageNum }}</td>
            </tr></table></div>
        </td></tr>
    </table>


    {{-- ═══════════════════════════════════════════════
         CTA PAGE
    ═══════════════════════════════════════════════ --}}
    <div class="page-break page" style="background: #083321;">
        <div style="padding: 400px 100px 0; text-align: center;">
            <div class="cta-label">Bespoke Planning</div>
            <div class="cta-title">Ready to plan your journey?</div>
            <div class="cta-copy">Share your preferred travel dates and let us tailor this experience around your pace, interests, and preferences.</div>
            <div>
                <a href="{{ url('/' . app()->getLocale() . '/safaris/' . $safari->slug) }}" class="cta-button primary">View This Tour</a>
                <a href="{{ url('/' . app()->getLocale() . '/custom-tour') }}" class="cta-button">Plan Your Safari</a>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════
         BACK COVER
    ═══════════════════════════════════════════════ --}}
    <div class="page-break page" style="background: #083321;">
        <div style="padding: 380px 100px 0; text-align: center; color: #ffffff;">
            <div class="back-logo">
                @if($logoUri)
                    <img src="{{ $logoUri }}" alt="Logo">
                @else
                    <div class="back-logo-text">{{ $siteName }}</div>
                @endif
            </div>
            <div class="back-divider"></div>
            <div class="back-tagline">Less on Ourselves, More on Others</div>
            <div class="back-title">{{ $safari->translated('title') }}</div>
            @if(filled($safari->translated('short_description')))
                <div class="back-subtitle">{{ $cleanText($safari->translated('short_description')) }}</div>
            @endif
            <a href="{{ url('/' . app()->getLocale() . '/safaris/' . $safari->slug) }}" class="back-button">View Itinerary</a>
            <div class="back-end">End of Itinerary</div>
        </div>
    </div>

</body>
</html>
