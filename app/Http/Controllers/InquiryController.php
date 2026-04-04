<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'safari_package_id' => 'nullable|exists:safari_packages,id',
            'inquiry_type'      => 'nullable|string|in:booking,inquiry',
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'country'           => 'nullable|string|max:255',
            'travel_date'       => 'nullable|date|after_or_equal:today',
            'number_of_people'  => 'nullable|integer|min:1|max:999',
            'message'           => 'nullable|string|max:5000',
            'contact_methods'   => 'nullable|array|max:2',
            'contact_methods.*' => 'string|in:email,phone,whatsapp,video_call',
        ]);

        $inquiry = Inquiry::create($validated);

        Mail::to(config('mail.admin_address', config('mail.from.address')))
            ->send(new NewInquiryNotification($inquiry));

        if ($request->expectsJson()) {
            return response()->json(['success' => true], 201);
        }

        return back()->with('inquiry_sent', true);
    }
}
