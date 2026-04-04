<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RequestResponse;
use App\Models\SafariRequest;
use Illuminate\Http\Request;

class SafariRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = SafariRequest::with(['agent.user', 'response'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(20);

        $counts = [
            'new'        => SafariRequest::where('status', 'new')->count(),
            'processing' => SafariRequest::where('status', 'processing')->count(),
            'completed'  => SafariRequest::where('status', 'completed')->count(),
        ];

        return view('admin.safari-requests.index', compact('requests', 'counts'));
    }

    public function show(SafariRequest $safariRequest)
    {
        $safariRequest->load(['agent.user', 'response']);
        return view('admin.safari-requests.show', compact('safariRequest'));
    }

    public function respond(Request $request, SafariRequest $safariRequest)
    {
        $validated = $request->validate([
            'safari_title' => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'price'        => ['required', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:2000'],
        ]);

        // Create or update the response
        $safariRequest->response()->updateOrCreate(
            ['request_id' => $safariRequest->id],
            array_merge($validated, ['status' => 'pending'])
        );

        // Mark request as completed
        $safariRequest->update(['status' => 'completed']);

        return redirect()
            ->route('admin.safari-requests.show', $safariRequest)
            ->with('success', 'Proposal sent to agent successfully.');
    }
}
