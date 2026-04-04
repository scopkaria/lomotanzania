<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryNotification;
use App\Models\Destination;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomTourController extends Controller
{
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
}
