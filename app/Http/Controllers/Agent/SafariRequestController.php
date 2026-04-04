<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\SafariRequest;
use App\Models\TourType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SafariRequestController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;
        $requests = $agent->safariRequests()
            ->with('response')
            ->latest()
            ->paginate(15);

        return view('agent.requests.index', compact('requests'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get(['id', 'name']);
        $tourTypes    = TourType::orderBy('name')->get(['id', 'name']);
        $categories   = Category::orderBy('name')->get(['id', 'name']);
        $countries    = Country::orderBy('name')->get(['id', 'name']);

        return view('agent.requests.create', compact('destinations', 'tourTypes', 'categories', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name'  => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'country'      => ['nullable', 'string', 'max:100'],
            'travel_date'  => ['required', 'date', 'after:today'],
            'people'       => ['required', 'integer', 'min:1', 'max:200'],
            'destinations' => ['required', 'array', 'min:1'],
            'destinations.*' => ['integer', 'exists:destinations,id'],
            'activities'   => ['nullable', 'array'],
            'activities.*' => ['string', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:3000'],
        ]);

        $agent = Auth::user()->agent;

        SafariRequest::create([
            'agent_id'     => $agent->id,
            'client_name'  => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'country'      => $validated['country'] ?? null,
            'travel_date'  => $validated['travel_date'],
            'people'       => $validated['people'],
            'destinations' => $validated['destinations'],
            'activities'   => $validated['activities'] ?? [],
            'notes'        => $validated['notes'] ?? null,
            'status'       => 'new',
        ]);

        return redirect()
            ->route('agent.requests.index')
            ->with('success', 'Safari request submitted. Admin will review and send a proposal shortly.');
    }

    public function responses()
    {
        $agent = Auth::user()->agent;
        $responses = $agent->safariRequests()
            ->with('response')
            ->whereHas('response')
            ->latest()
            ->paginate(15);

        return view('agent.responses.index', compact('responses'));
    }

    public function acceptResponse(Request $request, SafariRequest $safariRequest)
    {
        // Auth check
        abort_unless($safariRequest->agent_id === Auth::user()->agent->id, 403);

        $response = $safariRequest->response;
        abort_unless($response && $response->status === 'pending', 403);

        $agent = Auth::user()->agent;

        $commission = (float) $response->price * ($agent->commission_rate / 100);

        Booking::create([
            'agent_id'          => $agent->id,
            'safari_package_id' => null,
            'client_name'       => $safariRequest->client_name,
            'client_email'      => $safariRequest->client_email,
            'client_phone'      => $safariRequest->client_phone,
            'travel_date'       => $safariRequest->travel_date,
            'num_people'        => $safariRequest->people,
            'total_price'       => $response->price,
            'commission_amount' => $commission,
            'notes'             => "Custom safari: {$response->safari_title}",
            'status'            => 'confirmed',
        ]);

        $response->update(['status' => 'accepted']);

        return redirect()
            ->route('agent.responses.index')
            ->with('success', "Proposal accepted! Booking created. Commission: $" . number_format($commission, 2));
    }

    public function declineResponse(SafariRequest $safariRequest)
    {
        abort_unless($safariRequest->agent_id === Auth::user()->agent->id, 403);

        $response = $safariRequest->response;
        abort_unless($response && $response->status === 'pending', 403);

        $response->update(['status' => 'declined']);

        return back()->with('success', 'Proposal declined.');
    }
}
