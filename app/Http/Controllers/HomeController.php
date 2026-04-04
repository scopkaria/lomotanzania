<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\HeroSetting;
use App\Models\Page;
use App\Models\Post;
use App\Models\SafariPackage;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TourType;
use App\Traits\HasSeoData;
use App\Traits\LoadsSectionData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use HasSeoData, LoadsSectionData;

    public function index()
    {
        $page = Page::where('is_homepage', true)->first();
        $sections = $page ? $page->activeSections()->with('heroSlides')->get() : collect();

        // Load data for each section type
        $sectionDataMap = [];
        foreach ($sections as $section) {
            $sectionDataMap[$section->id] = $this->loadSectionData($section);
        }

        return view('pages.home', [
            'page'           => $page,
            'sections'       => $sections,
            'sectionDataMap' => $sectionDataMap,
        ] + $this->seoData($page, 'Luxury Safari Experiences in Tanzania'));
    }

    // loadSectionData() is provided by the LoadsSectionData trait

    public function safariIndex(Request $request)
    {
        $query = SafariPackage::where('status', 'published')
            ->with(['countries', 'itineraries.destination', 'tourType', 'categoryRelation']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('destinations', fn ($d) => $d->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('countries', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // Country filter (multi)
        if ($request->filled('countries')) {
            $countrySlugs = is_array($request->countries) ? $request->countries : explode(',', $request->countries);
            $query->whereHas('countries', fn ($q) => $q->whereIn('countries.slug', $countrySlugs));
        }

        // Tour type filter (multi)
        if ($request->filled('tour_types')) {
            $typeSlugs = is_array($request->tour_types) ? $request->tour_types : explode(',', $request->tour_types);
            $query->whereHas('tourType', fn ($q) => $q->whereIn('slug', $typeSlugs));
        }

        // Category/interest filter (multi)
        if ($request->filled('categories')) {
            $catSlugs = is_array($request->categories) ? $request->categories : explode(',', $request->categories);
            $query->whereHas('categoryRelation', fn ($q) => $q->whereIn('slug', $catSlugs));
        }

        // Duration filter
        if ($request->filled('duration')) {
            $durations = is_array($request->duration) ? $request->duration : explode(',', $request->duration);
            $durationExpr = DB::raw('CAST(duration AS UNSIGNED)');
            $query->where(function ($q) use ($durations, $durationExpr) {
                foreach ($durations as $d) {
                    match ($d) {
                        '1_3'     => $q->orWhereBetween($durationExpr, [1, 3]),
                        '4_7'     => $q->orWhereBetween($durationExpr, [4, 7]),
                        '8_12'    => $q->orWhereBetween($durationExpr, [8, 12]),
                        '12_plus' => $q->orWhere($durationExpr, '>', 12),
                        default   => null,
                    };
                }
            });
        }

        // Price filter
        if ($request->filled('price')) {
            $prices = is_array($request->price) ? $request->price : explode(',', $request->price);
            $query->where(function ($q) use ($prices) {
                foreach ($prices as $p) {
                    match ($p) {
                        'under_2k'  => $q->orWhere('price', '<', 2000),
                        '2k_5k'     => $q->orWhereBetween('price', [2000, 5000]),
                        '5k_10k'    => $q->orWhereBetween('price', [5000, 10000]),
                        'over_10k'  => $q->orWhere('price', '>', 10000),
                        default     => null,
                    };
                }
            });
        }

        $safaris = $query->latest()->paginate(12);

        // AJAX: return JSON for dynamic filtering
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'  => view('safaris._cards', ['safaris' => $safaris])->render(),
                'count' => $safaris->total(),
                'next'  => $safaris->nextPageUrl(),
            ]);
        }

        $countries  = Country::orderBy('name')->get();
        $tourTypes  = TourType::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('safaris.index', compact('safaris', 'countries', 'tourTypes', 'categories') + $this->seoData(null, __('messages.safari'), 'Browse our curated safari packages across Tanzania'));
    }

    public function show(string $locale, string $slug)
    {
        $safari = SafariPackage::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'destinations',
                'countries',
                'itineraries.destination',
                'itineraries.accommodationRelation.images',
                'testimonials' => fn($q) => $q->where('approved', true)->latest(),
            ])
            ->firstOrFail();

        $itineraryDestinationIds = $safari->itineraries
            ->pluck('destination_id')
            ->filter()
            ->unique()
            ->values();

        $relatedSafaris = SafariPackage::where('status', 'published')
            ->where('id', '!=', $safari->id)
            ->when(
                $itineraryDestinationIds->isNotEmpty(),
                fn ($query) => $query->whereHas('itineraries.destination', fn ($destinationQuery) => $destinationQuery->whereIn('destinations.id', $itineraryDestinationIds))
            )
            ->latest()
            ->limit(3)
            ->get();

        return view('safaris.show', compact('safari', 'relatedSafaris') + $this->seoData($safari, $safari->translated('title'), $safari->translated('short_description')));
    }

    public function country(string $locale, string $slug)
    {
        $country = Country::with([
            'destinations',
            'safariPackages' => fn ($q) => $q->where('status', 'published')->latest(),
        ])->where('slug', $slug)->firstOrFail();

        return view('pages.country', compact('country') + $this->seoData($country, $country->name));
    }

    public function tourType(string $locale, string $slug)
    {
        $tourType = TourType::where('slug', $slug)->firstOrFail();
        $safaris = SafariPackage::where('status', 'published')
            ->where('tour_type_id', $tourType->id)
            ->latest()
            ->get();

        return view('pages.tour-type', compact('tourType', 'safaris') + $this->seoData($tourType, $tourType->name));
    }

    public function category(string $locale, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $safaris = SafariPackage::where('status', 'published')
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('pages.category', compact('category', 'safaris') + $this->seoData($category, $category->name));
    }

    public function destination(string $locale, string $slug)
    {
        $destination = Destination::with(['country', 'safariPackages' => fn ($q) => $q->where('status', 'published')->latest()])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.destination', compact('destination') + $this->seoData($destination, $destination->translated('name'), $destination->translated('description')));
    }

    public function downloadPdf(string $locale, string $slug)
    {
        $safari = SafariPackage::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'countries',
                'destinations',
                'images',
                'itineraries' => fn ($q) => $q->orderBy('day_number'),
                'itineraries.destination',
                'itineraries.accommodationRelation.images',
            ])
            ->firstOrFail();

        $itineraryDays = $safari->itineraries->values();
        $routeStops = $this->buildPdfRouteStops($itineraryDays);
        $routeMap = $this->buildPdfRouteMap($routeStops);
        $accommodations = $itineraryDays
            ->pluck('accommodationRelation')
            ->filter()
            ->unique('id')
            ->values();
        $countryLabel = $safari->countries
            ->pluck('name')
            ->filter()
            ->join(' • ');

        $setting = Setting::first();

        $mapImageUri = $this->buildStaticMapImage($routeStops, $safari->id);

        return Pdf::loadView('pdf.safari', compact('safari', 'itineraryDays', 'routeStops', 'routeMap', 'accommodations', 'countryLabel', 'setting', 'mapImageUri'))
            ->setPaper('a4')
            ->download('safari-itinerary.pdf');
    }

    private function buildPdfRouteStops(Collection $itineraryDays): Collection
    {
        $groupedStops = [];

        foreach ($itineraryDays as $day) {
            $destination = $day->destination;

            if (! filled($destination?->latitude) || ! filled($destination?->longitude)) {
                continue;
            }

            $lastIndex = count($groupedStops) - 1;

            if ($lastIndex >= 0 && ($groupedStops[$lastIndex]['destination_id'] ?? null) === $destination->id) {
                $groupedStops[$lastIndex]['day_end'] = $day->day_number;
                $groupedStops[$lastIndex]['label'] = $groupedStops[$lastIndex]['day_start'] === $day->day_number
                    ? 'Day ' . $day->day_number . ' — ' . $destination->name
                    : 'Day ' . $groupedStops[$lastIndex]['day_start'] . '–' . $day->day_number . ' — ' . $destination->name;
                continue;
            }

            $groupedStops[] = [
                'destination_id' => $destination->id,
                'day_start' => $day->day_number,
                'day_end' => $day->day_number,
                'name' => $destination->name,
                'latitude' => (float) $destination->latitude,
                'longitude' => (float) $destination->longitude,
                'label' => 'Day ' . $day->day_number . ' — ' . $destination->name,
            ];
        }

        return collect($groupedStops)->values();
    }

    private function buildStaticMapImage(Collection $routeStops, int $safariId): ?string
    {
        if ($routeStops->isEmpty()) {
            return null;
        }

        $token = config('services.mapbox.token');

        if (! filled($token)) {
            return null;
        }

        $cachePath = storage_path('app/public/maps/safari-' . $safariId . '.png');

        if (file_exists($cachePath) && (time() - filemtime($cachePath)) < 86400) {
            return str_replace('\\', '/', realpath($cachePath));
        }

        $overlays = [];

        if ($routeStops->count() > 1) {
            $pathCoords = $routeStops->map(fn ($s) => $s['longitude'] . ',' . $s['latitude'])->implode(',');
            $overlays[] = 'path-5+FEBC11-1.0(' . $pathCoords . ')';
        }

        $total = $routeStops->count();

        foreach ($routeStops as $i => $stop) {
            $isTerminal = ($i === 0 || $i === $total - 1);
            $size = $isTerminal ? 'l' : 's';
            $color = $isTerminal ? 'FEBC11' : '083321';
            $overlays[] = "pin-{$size}+{$color}({$stop['longitude']},{$stop['latitude']})";
        }

        $overlay = implode(',', $overlays);

        // Calculate center and zoom from bounding box instead of using auto
        $lats = $routeStops->pluck('latitude');
        $lngs = $routeStops->pluck('longitude');
        $centerLat = ($lats->min() + $lats->max()) / 2;
        $centerLng = ($lngs->min() + $lngs->max()) / 2;
        $latSpan = max($lats->max() - $lats->min(), 0.01);
        $lngSpan = max($lngs->max() - $lngs->min(), 0.01);
        $maxSpan = max($latSpan, $lngSpan);
        // Zoom level: ~7 for 2° span, ~8 for 1°, ~6 for 4°
        $zoom = max(4, min(12, (int) round(8 - log($maxSpan, 2))));

        $url = "https://api.mapbox.com/styles/v1/mapbox/outdoors-v12/static/{$overlay}/{$centerLng},{$centerLat},{$zoom}/800x500@2x?access_token={$token}";

        try {
            $ctx = stream_context_create(['http' => ['timeout' => 15]]);
            $imageData = @file_get_contents($url, false, $ctx);

            if ($imageData !== false && strlen($imageData) > 1000) {
                $dir = dirname($cachePath);

                if (! is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                file_put_contents($cachePath, $imageData);

                return str_replace('\\', '/', realpath($cachePath));
            }
        } catch (\Throwable $e) {
            // Silently fail — template falls back to SVG map
        }

        return null;
    }

    private function buildPdfRouteMap(Collection $routeStops): array
    {
        $width = 420;
        $height = 280;
        $padding = 36;

        if ($routeStops->isEmpty()) {
            return [
                'width' => $width,
                'height' => $height,
                'polyline' => '',
                'nodes' => collect(),
            ];
        }

        $minLongitude = (float) $routeStops->min('longitude');
        $maxLongitude = (float) $routeStops->max('longitude');
        $minLatitude = (float) $routeStops->min('latitude');
        $maxLatitude = (float) $routeStops->max('latitude');

        $longitudeSpan = max($maxLongitude - $minLongitude, 0.01);
        $latitudeSpan = max($maxLatitude - $minLatitude, 0.01);
        $usableWidth = $width - ($padding * 2);
        $usableHeight = $height - ($padding * 2);
        $lastIndex = $routeStops->count() - 1;

        $nodes = $routeStops->values()->map(function (array $stop, int $index) use ($minLongitude, $minLatitude, $longitudeSpan, $latitudeSpan, $padding, $usableWidth, $usableHeight, $height, $width, $lastIndex) {
            $x = $padding + ((($stop['longitude'] - $minLongitude) / $longitudeSpan) * $usableWidth);
            $y = $height - $padding - ((($stop['latitude'] - $minLatitude) / $latitudeSpan) * $usableHeight);
            $labelY = $index % 2 === 0 ? $y - 18 : $y + 26;

            if ($labelY < 22) {
                $labelY = $y + 24;
            }

            if ($labelY > ($height - 10)) {
                $labelY = $y - 18;
            }

            $labelX = min(max($x + 10, 12), $width - 120);

            return $stop + [
                'x' => round($x, 2),
                'y' => round($y, 2),
                'label_x' => round($labelX, 2),
                'label_y' => round($labelY, 2),
                'is_terminal' => $index === 0 || $index === $lastIndex,
            ];
        });

        return [
            'width' => $width,
            'height' => $height,
            'polyline' => $nodes->map(fn (array $node) => $node['x'] . ',' . $node['y'])->implode(' '),
            'nodes' => $nodes,
        ];
    }
}
