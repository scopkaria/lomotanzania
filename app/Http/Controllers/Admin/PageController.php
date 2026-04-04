<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use HasBulkActions;

    protected array $locales = ['en', 'fr', 'de', 'es'];

    protected function bulkModel(): string { return Page::class; }
    protected function allowedBulkActions(): array { return ['delete', 'publish', 'draft']; }

    /**
     * All section types available for the page builder.
     */
    protected array $sectionTypes = [
        'hero'                  => 'Hero Slider',
        'split_hero'            => 'Split Hero',
        'featured_safaris'      => 'Featured Safaris',
        'safari_grid'           => 'Safari Grid',
        'destinations'          => 'Destinations',
        'destination_showcase'  => 'Destination Showcase',
        'why_choose_us'         => 'Why Choose Us',
        'icon_features'         => 'Icon Features',
        'testimonials'          => 'Testimonials',
        'testimonial_slider'    => 'Testimonial Slider',
        'cta'                   => 'Call to Action',
        'cta_banner'            => 'CTA Banner',
        'blog'                  => 'Latest Blog',
        'text'                  => 'Text Block',
        'image_text'            => 'Image + Text',
        'gallery'               => 'Gallery',
        'image_gallery'         => 'Image Gallery',
        'safari_list'           => 'Safari List',
        'map'                   => 'Map Section',
        'html'                  => 'Custom HTML',
        'highlight'             => 'Highlight Section',
        'two_column_feature'    => 'Two Column Feature',
        'experience_grid'       => 'Experience Grid',
    ];

    // ─── INDEX ──────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Page::withCount('pageSections')
            ->orderBy('is_homepage', 'desc')
            ->orderBy('sort_order')
            ->orderBy('title->en');

        if ($request->filled('search')) {
            $query->where('title->en', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->paginate($request->integer('per_page', 20))->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    // ─── CREATE / STORE ─────────────────────────────────────

    public function create()
    {
        return view('admin.pages.form', [
            'page'         => null,
            'sectionsJson' => [],
            'locales'      => $this->locales,
            'sectionTypes' => $this->sectionTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePage($request);

        $slug = $validated['slug'] ?: Str::slug($validated['title']['en']);
        $slug = $this->ensureUniqueSlug($slug);

        if ($request->boolean('is_homepage')) {
            Page::where('is_homepage', true)->update(['is_homepage' => false, 'type' => 'page']);
        }

        $page = Page::create([
            'title'           => $validated['title'],
            'slug'            => $slug,
            'status'          => $validated['status'],
            'is_homepage'     => $request->boolean('is_homepage'),
            'template'        => $validated['template'] ?? 'default',
            'layout'          => $validated['layout'] ?? 'full_width',
            'bg_color'        => $validated['bg_color'] ?? null,
            'section_spacing' => $validated['section_spacing'] ?? 'normal',
            'sort_order'      => $validated['sort_order'] ?? 0,
            'meta'            => $validated['meta'] ?? [],
            'type'            => $request->boolean('is_homepage') ? 'homepage' : 'page',
        ]);

        $this->syncSections($page, $request->input('sections', []));

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    // ─── EDIT / UPDATE ──────────────────────────────────────

    public function edit(Page $page)
    {
        $sections = $page->pageSections()->with('heroSlides')->get();

        $sectionsJson = $sections->map(fn ($s) => [
            'id'           => $s->id,
            'section_type' => $s->section_type,
            'is_active'    => $s->is_active,
            'data'         => $s->data ?? (object) [],
            'slides'       => $s->section_type === 'hero'
                ? $s->heroSlides->map(fn ($h) => [
                    'id'           => $h->id,
                    'label'        => $h->label ?? (object) [],
                    'title'        => $h->title ?? (object) [],
                    'subtitle'     => $h->subtitle ?? (object) [],
                    'image'        => $h->image,
                    'button_text'  => $h->button_text ?? (object) [],
                    'button_link'  => $h->button_link,
                    'next_up_text' => $h->next_up_text ?? (object) [],
                    'bg_color'     => $h->bg_color,
                    'bg_image'     => $h->bg_image,
                    'image_alt'    => $h->image_alt,
                ])->toArray()
                : [],
        ]);

        return view('admin.pages.form', [
            'page'         => $page,
            'sectionsJson' => $sectionsJson,
            'locales'      => $this->locales,
            'sectionTypes' => $this->sectionTypes,
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $this->validatePage($request, $page->id);

        $slug = $validated['slug'] ?: Str::slug($validated['title']['en']);
        if ($slug !== $page->slug) {
            $slug = $this->ensureUniqueSlug($slug, $page->id);
        }

        if ($request->boolean('is_homepage') && !$page->is_homepage) {
            Page::where('is_homepage', true)->where('id', '!=', $page->id)->update(['is_homepage' => false, 'type' => 'page']);
        }

        $page->update([
            'title'           => $validated['title'],
            'slug'            => $slug,
            'status'          => $validated['status'],
            'is_homepage'     => $request->boolean('is_homepage'),
            'template'        => $validated['template'] ?? 'default',
            'layout'          => $validated['layout'] ?? 'full_width',
            'bg_color'        => $validated['bg_color'] ?? null,
            'section_spacing' => $validated['section_spacing'] ?? 'normal',
            'sort_order'      => $validated['sort_order'] ?? 0,
            'meta'            => $validated['meta'] ?? [],
            'type'            => $request->boolean('is_homepage') ? 'homepage' : 'page',
        ]);

        $this->syncSections($page, $request->input('sections', []));

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    // ─── DESTROY ────────────────────────────────────────────

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    // ─── IMAGE UPLOAD ───────────────────────────────────────

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:4096']);
        $path = $request->file('image')->store('pages', 'public');
        return response()->json(['path' => $path, 'url' => asset('storage/' . $path)]);
    }

    // ─── SECTION SYNC ───────────────────────────────────────

    protected function syncSections(Page $page, array $sectionsInput): void
    {
        $existingIds = $page->pageSections()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($sectionsInput as $idx => $sectionData) {
            if (empty($sectionData['section_type'])) continue;

            $attrs = [
                'section_type' => $sectionData['section_type'],
                'order'        => (int) $idx,
                'is_active'    => !empty($sectionData['is_active']),
                'data'         => $this->processData($sectionData),
            ];

            if (!empty($sectionData['id']) && in_array($sectionData['id'], $existingIds)) {
                $section = PageSection::findOrFail($sectionData['id']);
                $section->update($attrs);
            } else {
                $section = $page->pageSections()->create($attrs);
            }

            $submittedIds[] = $section->id;

            if ($sectionData['section_type'] === 'hero') {
                $this->syncHeroSlides($section, $sectionData['slides'] ?? []);
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            PageSection::whereIn('id', $toDelete)->delete();
        }
    }

    protected function syncHeroSlides(PageSection $section, array $slides): void
    {
        $existingIds = $section->heroSlides()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($slides as $idx => $slideData) {
            $attrs = [
                'label'        => $slideData['label'] ?? [],
                'title'        => $slideData['title'] ?? [],
                'subtitle'     => $slideData['subtitle'] ?? [],
                'image'        => $slideData['image'] ?? null,
                'button_text'  => $slideData['button_text'] ?? [],
                'button_link'  => $slideData['button_link'] ?? null,
                'next_up_text' => $slideData['next_up_text'] ?? [],
                'bg_color'     => $slideData['bg_color'] ?? null,
                'bg_image'     => $slideData['bg_image'] ?? null,
                'image_alt'    => $slideData['image_alt'] ?? null,
                'order'        => (int) $idx,
            ];

            if (!empty($slideData['id']) && in_array($slideData['id'], $existingIds)) {
                $slide = HeroSlide::findOrFail($slideData['id']);
                $slide->update($attrs);
                $submittedIds[] = $slide->id;
            } else {
                $slide = $section->heroSlides()->create($attrs);
                $submittedIds[] = $slide->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            HeroSlide::whereIn('id', $toDelete)->delete();
        }
    }

    protected function processData(array $sectionData): array
    {
        $data = $sectionData['data'] ?? [];

        foreach (['count'] as $numField) {
            if (isset($data[$numField])) {
                $data[$numField] = (int) $data[$numField];
            }
        }

        return $data;
    }

    // ─── VALIDATION ─────────────────────────────────────────

    protected function validatePage(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'            => 'required|array',
            'title.en'         => 'required|string|max:255',
            'title.fr'         => 'nullable|string|max:255',
            'title.de'         => 'nullable|string|max:255',
            'title.es'         => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug' . ($ignoreId ? ",{$ignoreId}" : ''),
            'status'           => 'required|in:draft,published',
            'template'         => 'nullable|string|max:50',
            'layout'           => 'nullable|string|in:full_width,boxed',
            'bg_color'         => 'nullable|string|max:20',
            'section_spacing'  => 'nullable|string|in:none,compact,normal,wide',
            'sort_order'       => 'nullable|integer',
            'meta'             => 'nullable|array',
            'meta.description' => 'nullable|string|max:500',
            'meta.keywords'    => 'nullable|string|max:500',
            'sections'         => 'nullable|array',
        ]);
    }

    protected function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $query = Page::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        if (!$query->exists()) return $slug;

        $i = 2;
        while (Page::where('slug', "{$slug}-{$i}")->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $i++;
        }
        return "{$slug}-{$i}";
    }
}
