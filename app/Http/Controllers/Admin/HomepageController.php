<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    protected array $locales = ['en', 'fr', 'de', 'es'];

    protected array $sectionTypes = [
        'hero'             => 'Hero Slider',
        'featured_safaris' => 'Featured Safaris',
        'destinations'     => 'Destinations',
        'why_choose_us'    => 'Why Choose Us',
        'testimonials'     => 'Testimonials',
        'cta'              => 'Call to Action',
        'blog'             => 'Latest Blog',
    ];

    public function index()
    {
        $page = Page::firstOrCreate(
            ['type' => 'homepage'],
            [
                'title'  => ['en' => 'Homepage'],
                'slug'   => 'homepage',
                'status' => 'published',
            ]
        );

        $sections = $page->pageSections()->with('heroSlides')->get();

        $sectionsJson = $sections->map(function ($s) {
            return [
                'id'           => $s->id,
                'section_type' => $s->section_type,
                'is_active'    => $s->is_active,
                'data'         => $s->data ?? (object) [],
                'slides'       => $s->section_type === 'hero'
                    ? $s->heroSlides->map(function ($h) {
                        return [
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
                        ];
                    })->toArray()
                    : [],
            ];
        });

        return view('admin.homepage.index', [
            'page'         => $page,
            'sections'     => $sections,
            'sectionsJson' => $sectionsJson,
            'sectionTypes' => $this->sectionTypes,
            'locales'      => $this->locales,
        ]);
    }

    public function update(Request $request)
    {
        $page = Page::where('type', 'homepage')->firstOrFail();

        $existingIds = $page->pageSections()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($request->input('sections', []) as $idx => $sectionData) {
            if (empty($sectionData['section_type'])) continue;

            $attrs = [
                'section_type' => $sectionData['section_type'],
                'order'        => (int) $idx,
                'is_active'    => ! empty($sectionData['is_active']),
                'data'         => $this->processData($sectionData),
            ];

            if (! empty($sectionData['id']) && in_array($sectionData['id'], $existingIds)) {
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

        // Delete removed sections
        $toDelete = array_diff($existingIds, $submittedIds);
        if (! empty($toDelete)) {
            PageSection::whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('admin.homepage.index')
            ->with('success', 'Homepage updated successfully.');
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

            if (! empty($slideData['id']) && in_array($slideData['id'], $existingIds)) {
                $slide = HeroSlide::findOrFail($slideData['id']);
                $slide->update($attrs);
                $submittedIds[] = $slide->id;
            } else {
                $slide = $section->heroSlides()->create($attrs);
                $submittedIds[] = $slide->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (! empty($toDelete)) {
            HeroSlide::whereIn('id', $toDelete)->delete();
        }
    }

    protected function processData(array $sectionData): array
    {
        $data = $sectionData['data'] ?? [];

        // Clean up: ensure numeric fields are integers
        foreach (['count'] as $numField) {
            if (isset($data[$numField])) {
                $data[$numField] = (int) $data[$numField];
            }
        }

        return $data;
    }

    /**
     * Handle section image uploads via AJAX.
     */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:4096']);

        $path = $request->file('image')->store('homepage', 'public');

        return response()->json([
            'path' => $path,
            'url'  => asset('storage/' . $path),
        ]);
    }
}
