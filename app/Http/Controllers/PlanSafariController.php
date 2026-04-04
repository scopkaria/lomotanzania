<?php

namespace App\Http\Controllers;

use App\Mail\SafariPlanNotification;
use App\Models\Destination;
use App\Models\PlannerSetting;
use App\Models\SafariPackage;
use App\Models\SafariPlan;
use App\Models\TourType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PlanSafariController extends Controller
{
    public function create(Request $request)
    {
        $safariId = $request->integer('safari_id');
        $safari = null;
        $preselectedDestinations = [];

        if ($safariId) {
            $safari = SafariPackage::with('itineraries')->find($safariId);
            if ($safari) {
                $preselectedDestinations = $safari->itineraries
                    ->pluck('destination_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
            }
        }

        $destinations = Destination::orderBy('name')->get(['id', 'name', 'featured_image']);
        $tourTypes = TourType::orderBy('name')->get(['id', 'name']);
        $settings = PlannerSetting::all()->keyBy('step_key');

        $budgetSetting = $settings->get('budget');
        $budgetRanges = $budgetSetting?->options ?? [
            '$2,000 – $5,000',
            '$5,000 – $10,000',
            '$10,000 – $20,000',
            '$20,000+',
        ];

        return view('plan-safari', compact(
            'safari',
            'safariId',
            'preselectedDestinations',
            'destinations',
            'tourTypes',
            'settings',
            'budgetRanges',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'safari_id' => 'nullable|integer|exists:safari_packages,id',
            'destinations' => 'required|array|min:1',
            'destinations.*' => 'integer|exists:destinations,id',
            'months' => 'required|array|min:1',
            'months.*' => 'string|max:20',
            'travel_group' => 'required|string|in:Solo,Couple,Family,Friends,Group',
            'interests' => 'nullable|array',
            'interests.*' => 'string|max:100',
            'budget_range' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:50',
            'contact_methods' => 'required|array|min:1|max:2',
            'contact_methods.*' => 'string|in:Email,WhatsApp,Phone,Video Call',
            'wants_updates' => 'nullable|boolean',
            'know_destination' => 'nullable|string|max:50',
        ]);

        $destinationNames = Destination::whereIn('id', $validated['destinations'])
            ->pluck('name')->toArray();

        $plan = SafariPlan::create([
            'safari_package_id' => $validated['safari_id'] ?? null,
            'destinations' => $destinationNames,
            'months' => $validated['months'],
            'travel_group' => $validated['travel_group'],
            'interests' => $validated['interests'] ?? [],
            'budget_range' => $validated['budget_range'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'country_code' => $validated['country_code'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'contact_methods' => $validated['contact_methods'],
            'wants_updates' => $request->boolean('wants_updates'),
            'know_destination' => $validated['know_destination'] ?? null,
        ]);

        // Send admin email
        $adminEmail = config('mail.admin_address');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new SafariPlanNotification($plan));
        }

        // Build WhatsApp message
        $whatsappNumber = config('services.safari.whatsapp_number', '');
        $message = "Hello, I'd like help planning a safari:\n\n"
            . "Destinations: " . implode(', ', $destinationNames) . "\n"
            . "Travel Time: " . implode(', ', $validated['months']) . "\n"
            . "Group: " . $validated['travel_group'] . "\n"
            . "Budget: " . $validated['budget_range'] . "\n\n"
            . "My name is " . $validated['first_name'] . ' ' . $validated['last_name'];

        $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', $whatsappNumber)
            . '?text=' . urlencode($message);

        return redirect()->route('plan-safari')
            ->with('success', 'Thank you! Your safari plan has been submitted. Our team will reach out shortly.')
            ->with('whatsapp_url', $whatsappUrl);
    }
}
