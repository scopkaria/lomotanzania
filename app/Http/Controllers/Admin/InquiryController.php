<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Inquiry::class; }

    public function index(Request $request)
    {
        $query = Inquiry::with('safariPackage');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $inquiries = $query->latest()->paginate($request->integer('per_page', 20))->withQueryString();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load('safariPackage');

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,booked',
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted.');
    }
}
