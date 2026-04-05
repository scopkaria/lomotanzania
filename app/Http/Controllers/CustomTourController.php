<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryNotification;
use App\Models\Destination;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\Setting;
use App\Traits\LoadsSectionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CustomTourController extends Controller
{
    use LoadsSectionData;
    public function create()
    {
        $destinations = Destination::orderBy('name')->get(['id', 'name']);

        return view('custom-tour', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'travel_date' => 'nullable|date|after:today',
            'number_of_people' => 'nullable|integer|min:1|max:100',
            'destinations' => 'nullable|array',
            'destinations.*' => 'exists:destinations,id',
            'message' => 'nullable|string|max:5000',
        ]);

        $destinationNames = [];
        if (! empty($validated['destinations'])) {
            $destinationNames = Destination::whereIn('id', $validated['destinations'])->pluck('name')->toArray();
        }

        $inquiry = Inquiry::create([
            'inquiry_type' => 'custom_tour',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'number_of_people' => $validated['number_of_people'] ?? null,
            'message' => trim(
                (! empty($destinationNames) ? "Destinations of interest: " . implode(', ', $destinationNames) . "\n\n" : '')
                . ($validated['message'] ?? '')
            ) ?: null,
            'status' => 'new',
        ]);

        $adminEmail = config('mail.admin_address');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewInquiryNotification($inquiry));
        }

        return redirect()->route('custom-tour')->with('success', 'Thank you! We\'ve received your request and will get back to you shortly.');
    }

    public function contact()
    {
        $setting = Setting::first();
        $page = Page::published()->where('slug', 'contact')->first();
        $sections = $page ? $page->activeSections()->with('heroSlides')->get() : collect();
        $sectionDataMap = [];

        foreach ($sections as $section) {
            $sectionDataMap[$section->id] = $this->loadSectionData($section);
        }

        return view('contact', compact('setting', 'page', 'sections', 'sectionDataMap'));
    }

    public function contactStore(Request $request)
    {
        if ($request->filled('website')) {
            throw ValidationException::withMessages([
                'spam' => 'Spam protection triggered. Please try again.',
            ]);
        }

        $startedAt = (int) $request->input('form_started_at', 0);
        if ($startedAt > 0 && (now()->timestamp - $startedAt) < 3) {
            throw ValidationException::withMessages([
                'spam' => 'Please take a moment to complete the form before submitting.',
            ]);
        }

        $rateLimitKey = 'contact-form:' . $request->ip() . '|' . strtolower((string) $request->input('email'));
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            throw ValidationException::withMessages([
                'spam' => 'Too many contact requests. Please wait a few minutes and try again.',
            ]);
        }
        RateLimiter::hit($rateLimitKey, 600);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'country_code'    => 'required|string|max:10',
            'phone'           => 'required|string|max:50',
            'subject'         => 'nullable|string|max:255',
            'message'         => 'required|string|max:5000',
            'form_started_at' => 'nullable|integer',
            'website'         => 'nullable|string|max:255',
        ]);

        $phone = trim($validated['country_code'] . ' ' . preg_replace('/\s+/', ' ', $validated['phone']));

        $inquiry = Inquiry::create([
            'inquiry_type' => 'contact',
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $phone,
            'message' => (!empty($validated['subject']) ? "Subject: {$validated['subject']}\n\n" : '') . $validated['message'],
            'status'  => 'new',
        ]);

        $adminEmail = config('mail.admin_address');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewInquiryNotification($inquiry));
        }

        return redirect()->route('contact', ['locale' => app()->getLocale()])->with('success', __('messages.contact_success') ?: 'Thank you for reaching out! We\'ll get back to you shortly.');
    }
}
