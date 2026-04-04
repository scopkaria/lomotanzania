<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\SafariImage;
use App\Models\SafariPackage;
use App\Models\TourType;
use App\Models\Category;
use App\Traits\HasBulkActions;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SafariController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return SafariPackage::class; }
    protected function allowedBulkActions(): array { return ['delete', 'publish', 'draft']; }

    public function index(Request $request)
    {
        $query = SafariPackage::with('destinations');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $safaris = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();

        return view('admin.safaris.index', compact('safaris'));
    }

    public function create()
    {
        $countries    = Country::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        $accommodations = Accommodation::orderBy('name')->get();
        $tourTypes    = TourType::orderBy('name')->get();
        $categories   = Category::orderBy('name')->get();

        return view('admin.safaris.create', compact('countries', 'destinations', 'accommodations', 'tourTypes', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:safari_packages,slug',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'overview_title'    => 'nullable|string|max:255',
            'highlights'        => 'nullable|array',
            'highlights.*'      => 'nullable|string|max:255',
            'highlights_title'  => 'nullable|string|max:255',
            'highlights_intro'  => 'nullable|string|max:500',
            'duration'          => 'nullable|string|max:100',
            'tour_type'         => 'nullable|string|max:100',
            'tour_type_id'      => 'nullable|integer|exists:tour_types,id',
            'category'          => 'nullable|string|max:100',
            'category_id'       => 'nullable|integer|exists:categories,id',
            'difficulty'        => 'nullable|string|max:50',
            'seasonal_pricing'  => 'nullable|array',
            'seasonal_pricing.low.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.low.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.low.pax_6' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_6' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_6' => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'video_url'         => 'nullable|url|max:500',
            'status'            => 'required|in:draft,published',
            'featured'          => 'boolean',
            'featured_order'    => 'nullable|integer|min:0',
            'featured_label'    => 'nullable|string|max:100',
            'featured_image'    => 'nullable|string|max:500',
            'countries'         => 'nullable|array',
            'countries.*'       => 'integer|exists:countries,id',
            'itinerary'         => 'nullable|array',
            'itinerary.*.day_number'     => 'required_with:itinerary|integer|min:1',
            'itinerary.*.title'          => 'required_with:itinerary|string|max:255',
            'itinerary.*.description'    => 'nullable|string',
            'itinerary.*.destination_id' => 'nullable|integer|exists:destinations,id',
            'itinerary.*.accommodation_id' => 'nullable|integer|exists:accommodations,id',
            'itinerary.*.image_path'     => 'nullable|string|max:500',
            'itinerary.*.translations'   => 'nullable|array',
            'itinerary.*.translations.*.title'       => 'nullable|string|max:255',
            'itinerary.*.translations.*.description' => 'nullable|string',
            'included'         => 'nullable|array',
            'included.*'       => 'nullable|string|max:255',
            'excluded'         => 'nullable|array',
            'excluded.*'       => 'nullable|string|max:255',
            'inclusions_title' => 'nullable|string|max:255',
            'inclusions_intro' => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
            'focus_keyword'    => 'nullable|string|max:255',
            'translations'     => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated = $this->prepareSafariPayload($validated);

        $safari = SafariPackage::create(collect($validated)->except(['itinerary', 'countries', 'destinations', 'translations', 'focus_keyword'])->toArray());

        // Save translations
        $this->saveTranslations($safari, $request);

        // Sync countries
        $safari->countries()->sync($request->input('countries', []));

        // Itineraries
        if ($request->filled('itinerary')) {
            foreach ($request->input('itinerary') as $index => $day) {
                if (! empty($day['title'])) {
                    $itinerary = Itinerary::create([
                        'safari_package_id' => $safari->id,
                        'day_number'        => $day['day_number'],
                        'title'             => $day['title'],
                        'description'       => $day['description'] ?? null,
                        'destination_id'    => $day['destination_id'] ?: null,
                        'accommodation_id'  => $day['accommodation_id'] ?: null,
                        'image_path'        => $day['image_path'] ?? null,
                    ]);

                    $this->saveItineraryTranslations($itinerary, $day);
                }
            }
        }

        // Auto-sync destinations from itinerary days
        $destinationIds = $safari->itineraries()->whereNotNull('destination_id')->pluck('destination_id')->unique()->values()->all();
        $safari->destinations()->sync($destinationIds);

        return redirect()->route('admin.safaris.index')
            ->with('success', 'Safari package created successfully.');
    }

    public function edit(SafariPackage $safari)
    {
        $safari->load(['countries', 'destinations', 'itineraries' => fn ($q) => $q->with('accommodationRelation')->orderBy('day_number')]);

        $countries    = Country::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        $accommodations = Accommodation::orderBy('name')->get();
        $tourTypes    = TourType::orderBy('name')->get();
        $categories   = Category::orderBy('name')->get();

        return view('admin.safaris.edit', compact('safari', 'countries', 'destinations', 'accommodations', 'tourTypes', 'categories'));
    }

    public function update(Request $request, SafariPackage $safari)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:safari_packages,slug,' . $safari->id,
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'overview_title'    => 'nullable|string|max:255',
            'highlights'        => 'nullable|array',
            'highlights.*'      => 'nullable|string|max:255',
            'highlights_title'  => 'nullable|string|max:255',
            'highlights_intro'  => 'nullable|string|max:500',
            'duration'          => 'nullable|string|max:100',
            'tour_type'         => 'nullable|string|max:100',
            'tour_type_id'      => 'nullable|integer|exists:tour_types,id',
            'category'          => 'nullable|string|max:100',
            'category_id'       => 'nullable|integer|exists:categories,id',
            'difficulty'        => 'nullable|string|max:50',
            'seasonal_pricing'  => 'nullable|array',
            'seasonal_pricing.low.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.low.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.low.pax_6' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.mid.pax_6' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_2' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_4' => 'nullable|numeric|min:0',
            'seasonal_pricing.high.pax_6' => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'video_url'         => 'nullable|url|max:500',
            'status'            => 'required|in:draft,published',
            'featured'          => 'boolean',
            'featured_order'    => 'nullable|integer|min:0',
            'featured_label'    => 'nullable|string|max:100',
            'featured_image'    => 'nullable|string|max:500',
            'countries'         => 'nullable|array',
            'countries.*'       => 'integer|exists:countries,id',
            'itinerary'         => 'nullable|array',
            'itinerary.*.day_number'     => 'required_with:itinerary|integer|min:1',
            'itinerary.*.title'          => 'required_with:itinerary|string|max:255',
            'itinerary.*.description'    => 'nullable|string',
            'itinerary.*.destination_id' => 'nullable|integer|exists:destinations,id',
            'itinerary.*.accommodation_id' => 'nullable|integer|exists:accommodations,id',
            'itinerary.*.image_path'     => 'nullable|string|max:500',
            'itinerary.*.translations'   => 'nullable|array',
            'itinerary.*.translations.*.title'       => 'nullable|string|max:255',
            'itinerary.*.translations.*.description' => 'nullable|string',
            'included'         => 'nullable|array',
            'included.*'       => 'nullable|string|max:255',
            'excluded'         => 'nullable|array',
            'excluded.*'       => 'nullable|string|max:255',
            'inclusions_title' => 'nullable|string|max:255',
            'inclusions_intro' => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
            'focus_keyword'    => 'nullable|string|max:255',
            'translations'     => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated = $this->prepareSafariPayload($validated);

        $safari->update(collect($validated)->except(['itinerary', 'countries', 'destinations', 'translations', 'focus_keyword'])->toArray());

        // Save translations
        $this->saveTranslations($safari, $request);

        // Sync countries
        $safari->countries()->sync($request->input('countries', []));

        // Sync itineraries — delete old, recreate new
        $safari->itineraries()->delete();
        if ($request->filled('itinerary')) {
            foreach ($request->input('itinerary') as $index => $day) {
                if (! empty($day['title'])) {
                    $itinerary = Itinerary::create([
                        'safari_package_id' => $safari->id,
                        'day_number'        => $day['day_number'],
                        'title'             => $day['title'],
                        'description'       => $day['description'] ?? null,
                        'destination_id'    => $day['destination_id'] ?: null,
                        'accommodation_id'  => $day['accommodation_id'] ?: null,
                        'image_path'        => $day['image_path'] ?? null,
                    ]);

                    $this->saveItineraryTranslations($itinerary, $day);
                }
            }
        }

        // Auto-sync destinations from itinerary days
        $destinationIds = $safari->itineraries()->whereNotNull('destination_id')->pluck('destination_id')->unique()->values()->all();
        $safari->destinations()->sync($destinationIds);

        return redirect()->back()
            ->with('success', 'Safari updated successfully.');
    }

    public function destroy(SafariPackage $safari)
    {
        // Cleanup files
        if ($safari->featured_image) {
            Storage::disk('public')->delete($safari->featured_image);
        }
        foreach ($safari->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $safari->delete(); // cascades itineraries, images, testimonials

        return redirect()->route('admin.safaris.index')
            ->with('success', 'Safari package deleted.');
    }

    protected function prepareSafariPayload(array $validated): array
    {
        $validated = $this->normalizeListFields($validated);
        $validated['description'] = $this->sanitizeRichText($validated['description'] ?? null);
        $validated['price'] = null;
        $validated['map_embed'] = null;
        $validated['overview_title'] = filled($validated['overview_title'] ?? null)
            ? trim((string) $validated['overview_title'])
            : null;
        $validated['seasonal_pricing'] = $this->normalizeSeasonalPricing($validated['seasonal_pricing'] ?? []);

        return $validated;
    }

    protected function normalizeListFields(array $validated): array
    {
        foreach (['highlights', 'included', 'excluded'] as $field) {
            $items = collect($validated[$field] ?? [])
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values();

            $validated[$field] = $items->isNotEmpty() ? $items->all() : null;
        }

        return $validated;
    }

    protected function normalizeSeasonalPricing(array $pricing): ?array
    {
        $normalized = [];

        foreach (['low', 'mid', 'high'] as $season) {
            $values = [];

            foreach (['pax_2', 'pax_4', 'pax_6'] as $band) {
                $value = $pricing[$season][$band] ?? null;
                $values[$band] = filled($value) ? (float) $value : null;
            }

            if (collect($values)->filter(fn ($value) => $value !== null)->isNotEmpty()) {
                $normalized[$season] = $values;
            }
        }

        return $normalized !== [] ? $normalized : null;
    }

    protected function sanitizeRichText(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return null;
        }

        $allowedTags = ['p', 'br', 'strong', 'em', 'b', 'i', 'u', 'ul', 'ol', 'li', 'a', 'h3', 'h4', 'blockquote'];
        $allowedAttributes = ['a' => ['href', 'target', 'rel']];

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previousState = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="safari-description-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        $root = $dom->getElementById('safari-description-root');

        if (! $root) {
            return strip_tags($html, '<p><br><strong><em><b><i><u><ul><ol><li><a><h3><h4><blockquote>');
        }

        $nodes = [];
        foreach ($root->getElementsByTagName('*') as $node) {
            $nodes[] = $node;
        }

        for ($index = count($nodes) - 1; $index >= 0; $index--) {
            $node = $nodes[$index];

            if (! in_array($node->nodeName, $allowedTags, true)) {
                $this->unwrapNode($node);
                continue;
            }

            if ($node->hasAttributes()) {
                for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                    $attribute = $node->attributes->item($i);
                    $isAllowedAttribute = in_array($attribute->nodeName, $allowedAttributes[$node->nodeName] ?? [], true);

                    if (! $isAllowedAttribute) {
                        $node->removeAttribute($attribute->nodeName);
                    }
                }
            }

            if ($node->nodeName === 'a') {
                $href = $this->sanitizeLinkHref($node->getAttribute('href'));

                if ($href === null) {
                    $node->removeAttribute('href');
                    $node->removeAttribute('target');
                    $node->removeAttribute('rel');
                } else {
                    $node->setAttribute('href', $href);
                    if ($node->getAttribute('target') === '_blank') {
                        $node->setAttribute('rel', 'noopener noreferrer');
                    } else {
                        $node->removeAttribute('target');
                        $node->removeAttribute('rel');
                    }
                }
            }
        }

        $cleanHtml = '';
        foreach ($root->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        return trim($cleanHtml) ?: null;
    }

    protected function unwrapNode(\DOMNode $node): void
    {
        while ($node->firstChild) {
            $node->parentNode?->insertBefore($node->firstChild, $node);
        }

        $node->parentNode?->removeChild($node);
    }

    protected function sanitizeLinkHref(?string $href): ?string
    {
        $href = trim((string) $href);

        if ($href === '') {
            return null;
        }

        if (Str::startsWith($href, ['/', '#'])) {
            return $href;
        }

        return preg_match('/^(https?:|mailto:|tel:)/i', $href) ? $href : null;
    }

    protected function saveTranslations(SafariPackage $safari, Request $request): void
    {
        $locales = ['en', 'fr', 'de', 'es'];
        $fields  = ['title', 'short_description', 'description', 'highlights', 'overview_title', 'highlights_title', 'highlights_intro', 'inclusions_title', 'inclusions_intro'];

        foreach ($fields as $field) {
            $translations = [];
            foreach ($locales as $locale) {
                $value = $request->input("translations.{$locale}.{$field}");
                if (filled($value)) {
                    $translations[$locale] = $value;
                }
            }
            if (! empty($translations)) {
                $safari->setTranslations($field, $translations);
            }
        }

        $safari->save();
    }

    protected function saveItineraryTranslations(Itinerary $itinerary, array $day): void
    {
        if (empty($day['translations'])) {
            return;
        }

        foreach (['title', 'description'] as $field) {
            $translations = [];
            foreach (['fr', 'de', 'es'] as $locale) {
                $value = $day['translations'][$locale][$field] ?? null;
                if (filled($value)) {
                    $translations[$locale] = $value;
                }
            }
            if (! empty($translations)) {
                $itinerary->setTranslations($field, $translations);
            }
        }

        $itinerary->save();
    }
}
