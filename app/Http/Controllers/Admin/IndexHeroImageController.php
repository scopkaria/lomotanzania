<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndexHeroImage;
use Illuminate\Http\Request;

class IndexHeroImageController extends Controller
{
    /**
     * Default sections that should always exist.
     */
    private const SECTIONS = [
        'blog'         => 'Blog Index',
        'safaris'      => 'Safaris Index',
        'destinations' => 'Destinations Index',
        'experiences'  => 'Experiences Index',
        'countries'    => 'Countries Index',
    ];

    public function edit()
    {
        // Ensure all sections exist in DB
        foreach (self::SECTIONS as $key => $label) {
            IndexHeroImage::firstOrCreate(
                ['section_key' => $key],
                ['label' => $label]
            );
        }

        $heroes = IndexHeroImage::orderByRaw("FIELD(section_key, 'blog','safaris','destinations','experiences','countries')")
            ->get()
            ->keyBy('section_key');

        return view('admin.index-hero-images.edit', compact('heroes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sections'              => 'required|array',
            'sections.*.image_path' => 'nullable|string|max:500',
            'sections.*.title'      => 'nullable|string|max:255',
            'sections.*.subtitle'   => 'nullable|string|max:255',
        ]);

        foreach ($request->input('sections', []) as $key => $data) {
            if (! array_key_exists($key, self::SECTIONS)) {
                continue;
            }

            IndexHeroImage::updateOrCreate(
                ['section_key' => $key],
                [
                    'label'      => self::SECTIONS[$key],
                    'image_path' => $data['image_path'] ?? null,
                    'title'      => $data['title'] ?? null,
                    'subtitle'   => $data['subtitle'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.pages.index-hero-images.edit')
            ->with('success', 'Index hero images updated successfully.');
    }
}
