<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
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

        return view('admin.hero-settings.edit', compact('settings', 'allSafaris', 'selectedIds'));
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
        ]);

        $validated['autoplay'] = $request->boolean('autoplay');
        $validated['hero_safari_ids'] = $validated['hero_safari_ids'] ?? [];

        $settings = HeroSetting::instance();
        $settings->update($validated);

        return redirect()->route('admin.pages.hero-settings.edit')
            ->with('success', 'Hero settings updated successfully.');
    }
}
