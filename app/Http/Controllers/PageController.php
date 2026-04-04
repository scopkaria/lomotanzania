<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\HasSeoData;
use App\Traits\LoadsSectionData;

class PageController extends Controller
{
    use HasSeoData, LoadsSectionData;

    public function show(string $locale, string $slug)
    {
        $page = Page::where('slug', $slug)->published()->firstOrFail();

        $sections = $page->activeSections()->with('heroSlides')->get();

        $sectionDataMap = [];
        foreach ($sections as $section) {
            $sectionDataMap[$section->id] = $this->loadSectionData($section);
        }

        return view('page', [
            'page'           => $page,
            'sections'       => $sections,
            'sectionDataMap' => $sectionDataMap,
        ] + $this->seoData($page, $page->translatedTitle($locale)));
    }
}
