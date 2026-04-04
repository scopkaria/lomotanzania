<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::firstOrCreate([], [
            'site_name' => 'Lomo Tanzania Safari',
        ]);

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'             => 'required|string|max:255',
            'tagline'               => 'nullable|string|max:255',
            'logo'                  => 'nullable|string|max:500',
            'meta_description'      => 'nullable|string|max:500',
            'default_og_image'      => 'nullable|string|max:500',
            'google_analytics_id'   => 'nullable|string|max:50',
            'google_search_console' => 'nullable|string|max:100',
            'notification_email'    => 'nullable|email|max:255',
            'notify_inquiry'        => 'nullable|boolean',
            'notify_safari_request' => 'nullable|boolean',
            'notify_safari_plan'    => 'nullable|boolean',
        ]);

        $setting = Setting::firstOrCreate([], [
            'site_name' => 'Lomo Tanzania Safari',
        ]);

        $setting->site_name             = $validated['site_name'];
        $setting->tagline               = $validated['tagline'] ?? null;
        $setting->meta_description      = $validated['meta_description'] ?? null;
        $setting->google_analytics_id   = $validated['google_analytics_id'] ?? null;
        $setting->google_search_console = $validated['google_search_console'] ?? null;
        $setting->notification_email    = $validated['notification_email'] ?? null;
        $setting->notify_inquiry        = $request->boolean('notify_inquiry');
        $setting->notify_safari_request = $request->boolean('notify_safari_request');
        $setting->notify_safari_plan    = $request->boolean('notify_safari_plan');

        $setting->logo_path        = $validated['logo'] ?: null;
        $setting->default_og_image = $validated['default_og_image'] ?: null;

        $setting->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }
}
