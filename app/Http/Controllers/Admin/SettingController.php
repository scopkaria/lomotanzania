<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

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
            'site_name'                 => 'required|string|max:255',
            'tagline'                   => 'nullable|string|max:255',
            'logo'                      => 'nullable|string|max:500',
            'favicon'                   => 'nullable|string|max:500',
            'logo_width'                => 'nullable|integer|min:90|max:280',
            'header_color'              => ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'meta_description'          => 'nullable|string|max:500',
            'default_og_image'          => 'nullable|string|max:500',
            'google_analytics_id'       => 'nullable|string|max:50',
            'google_search_console'     => 'nullable|string|max:100',
            'bing_webmaster_code'       => 'nullable|string|max:100',
            'yandex_verification_code'  => 'nullable|string|max:100',
            'baidu_verification_code'   => 'nullable|string|max:100',
            'notification_email'        => 'nullable|email|max:255',
            'notify_inquiry'            => 'nullable|boolean',
            'notify_safari_request'     => 'nullable|boolean',
            'notify_safari_plan'        => 'nullable|boolean',
            'phone_number'              => 'nullable|string|max:30',
            'whatsapp_number'           => 'nullable|string|max:30',
            'tripadvisor_url'           => 'nullable|url|max:500',
            'chat_greeting'             => 'nullable|string|max:500',
            'chat_enabled'              => 'nullable|boolean',
        ]);

        $setting = Setting::firstOrCreate([], [
            'site_name' => 'Lomo Tanzania Safari',
        ]);

        $setting->site_name                = $validated['site_name'];
        $setting->tagline                  = $validated['tagline'] ?? null;
        $setting->meta_description         = $validated['meta_description'] ?? null;
        $setting->google_analytics_id      = $validated['google_analytics_id'] ?? null;
        $setting->google_search_console    = $validated['google_search_console'] ?? null;
        $setting->bing_webmaster_code      = $validated['bing_webmaster_code'] ?? null;
        $setting->yandex_verification_code = $validated['yandex_verification_code'] ?? null;
        $setting->baidu_verification_code  = $validated['baidu_verification_code'] ?? null;
        $setting->notification_email       = $validated['notification_email'] ?? null;
        $setting->notify_inquiry           = $request->boolean('notify_inquiry');
        $setting->notify_safari_request    = $request->boolean('notify_safari_request');
        $setting->notify_safari_plan       = $request->boolean('notify_safari_plan');

        $setting->logo_path        = $validated['logo'] ?: null;
        $setting->favicon_path     = $validated['favicon'] ?? null;
        $setting->logo_width       = $validated['logo_width'] ?? 176;
        $setting->header_color     = $validated['header_color'] ?? '#083321';
        $setting->default_og_image = $validated['default_og_image'] ?: null;

        $setting->phone_number     = $validated['phone_number'] ?? null;
        $setting->whatsapp_number  = $validated['whatsapp_number'] ?? null;
        $setting->tripadvisor_url  = $validated['tripadvisor_url'] ?? null;
        $setting->chat_greeting    = $validated['chat_greeting'] ?? null;
        $setting->chat_enabled     = $request->boolean('chat_enabled');

        $setting->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }
}
