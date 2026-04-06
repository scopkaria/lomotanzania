<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SafariPackage;
use Illuminate\Http\Request;

class HeroSettingController extends Controller
{
    public function edit()
    {
        $settings = HeroSetting::instance();
        $allSafaris = SafariPackage::where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title', 'featured_image', 'slug']);

        $selectedIds = $settings->hero_safari_ids ?? [];

        $pages = Page::where('status', 'published')->orderBy('sort_order')->get(['id', 'title', 'slug', 'is_homepage']);

        // Which page IDs have an active hero section
        $heroPageIds = PageSection::where('section_type', 'hero')
            ->where('is_active', true)
            ->pluck('page_id')
            ->toArray();

        return view('admin.hero-settings.edit', compact('settings', 'allSafaris', 'selectedIds', 'pages', 'heroPageIds'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'background_video' => 'nullable|string|max:500',
            'video_poster'     => 'nullable|string|max:500',
            'overlay_opacity'  => 'required|numeric|min:0|max:1',
            'autoplay'         => 'boolean',
            'transition_speed' => 'required|integer|min:1000|max:30000',
            'hero_safari_ids'  => 'nullable|array',
            'hero_safari_ids.*'=> 'integer|exists:safari_packages,id',
            'button_text'      => 'nullable|array',
            'button_text.*'    => 'nullable|string|max:100',
            'button_link'      => 'nullable|string|max:500',
            'hero_pages'       => 'nullable|array',
            'hero_pages.*'     => 'integer|exists:pages,id',
        ]);

        $validated['autoplay'] = $request->boolean('autoplay');
        $validated['hero_safari_ids'] = $validated['hero_safari_ids'] ?? [];

        $settings = HeroSetting::instance();
        $settings->update(collect($validated)->except('hero_pages')->toArray());

        // Sync hero sections per page
        $enabledPageIds = $validated['hero_pages'] ?? [];
        $allPages = Page::where('status', 'published')->pluck('id');

        foreach ($allPages as $pageId) {
            $section = PageSection::where('page_id', $pageId)
                ->where('section_type', 'hero')
                ->first();

            if (in_array($pageId, $enabledPageIds)) {
                // Enable: create if missing, activate if exists
                if ($section) {
                    $section->update(['is_active' => true]);
                } else {
                    PageSection::create([
                        'page_id'      => $pageId,
                        'section_type' => 'hero',
                        'order'        => 0,
                        'is_active'    => true,
                        'data'         => [],
                    ]);
                }
            } else {
                // Disable
                if ($section) {
                    $section->update(['is_active' => false]);
                }
            }
        }

        return redirect()->route('admin.pages.hero-settings.edit')
            ->with('success', 'Hero settings updated successfully.');
    }
}
