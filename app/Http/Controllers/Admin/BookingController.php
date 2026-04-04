<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Booking::class; }
    protected function allowedBulkActions(): array { return ['delete', 'confirm', 'cancel']; }

    public function index(Request $request)
    {
        $query = Booking::with(['agent.user', 'safari'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('client_email', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->paginate($request->integer('per_page', 20))->withQueryString();

        $totalRevenue    = Booking::whereIn('status', ['pending', 'confirmed'])->sum('total_price');
        $totalCommission = Booking::whereIn('status', ['pending', 'confirmed'])->sum('commission_amount');

        return view('admin.bookings.index', compact('bookings', 'totalRevenue', 'totalCommission'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['agent.user', 'safari']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated.');
    }

    public function export()
    {
        $bookings = Booking::with(['agent.user', 'safari'])->latest()->get();

        $csv = implode(',', [
            'ID', 'Agent', 'Company', 'Safari', 'Client Name', 'Client Email',
            'Travel Date', 'People', 'Total Price', 'Commission', 'Status', 'Created At'
        ]) . "\n";

        foreach ($bookings as $b) {
            $csv .= implode(',', [
                $b->id,
                '"' . ($b->agent->user->name ?? '') . '"',
                '"' . ($b->agent->company_name ?? '') . '"',
                '"' . ($b->safari->title ?? '') . '"',
                '"' . $b->client_name . '"',
                '"' . $b->client_email . '"',
                $b->travel_date->format('Y-m-d'),
                $b->num_people,
                $b->total_price,
                $b->commission_amount,
                $b->status,
                $b->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
