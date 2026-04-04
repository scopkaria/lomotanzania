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
        $featuredSafaris = SafariPackage::where('featured', true)
            ->where('status', 'published')
            ->orderBy('featured_order')
            ->get(['id', 'title', 'featured_order', 'featured_label', 'featured_image', 'slug']);

        return view('admin.hero-settings.edit', compact('settings', 'featuredSafaris'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'background_video' => 'nullable|string|max:500',
            'video_poster'     => 'nullable|string|max:500',
            'overlay_opacity'  => 'required|numeric|min:0|max:1',
            'autoplay'         => 'boolean',
            'transition_speed' => 'required|integer|min:1000|max:30000',
        ]);

        $validated['autoplay'] = $request->boolean('autoplay');

        $settings = HeroSetting::instance();
        $settings->update($validated);

        return redirect()->route('admin.pages.hero-settings.edit')
            ->with('success', 'Hero settings updated successfully.');
    }
}
