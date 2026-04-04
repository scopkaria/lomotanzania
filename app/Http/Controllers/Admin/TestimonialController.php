<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPackage;
use App\Models\Testimonial;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Testimonial::class; }
    protected function allowedBulkActions(): array { return ['delete', 'approve']; }

    public function index(Request $request)
    {
        $query = Testimonial::with('safariPackage');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('approved', $request->status === 'approved');
        }
        $testimonials = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $safaris = SafariPackage::orderBy('title')->get();

        return view('admin.testimonials.create', compact('safaris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'safari_package_id' => 'nullable|integer|exists:safari_packages,id',
            'name'              => 'required|string|max:255',
            'message'           => 'required|string',
            'rating'            => 'required|integer|min:1|max:5',
            'approved'          => 'boolean',
        ]);

        $validated['approved'] = $request->boolean('approved');

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        $safaris = SafariPackage::orderBy('title')->get();

        return view('admin.testimonials.edit', compact('testimonial', 'safaris'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'safari_package_id' => 'nullable|integer|exists:safari_packages,id',
            'name'              => 'required|string|max:255',
            'message'           => 'required|string',
            'rating'            => 'required|integer|min:1|max:5',
            'approved'          => 'boolean',
        ]);

        $validated['approved'] = $request->boolean('approved');

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }
}
