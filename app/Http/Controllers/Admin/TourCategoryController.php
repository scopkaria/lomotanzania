<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// ADDED: Admin CRUD for tour categories (Safari, Trekking, Beach)
class TourCategoryController extends Controller
{
    public function index()
    {
        $categories = TourCategory::withCount('safariPackages')
            ->orderBy('display_order')
            ->get();

        return view('admin.tour-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.tour-categories.form', ['category' => new TourCategory()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tour_categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        TourCategory::create($validated);

        return redirect()->route('admin.tour-categories.index')
            ->with('success', 'Tour category created.');
    }

    public function edit(TourCategory $tourCategory)
    {
        return view('admin.tour-categories.form', ['category' => $tourCategory]);
    }

    public function update(Request $request, TourCategory $tourCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tour_categories,slug,' . $tourCategory->id,
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tourCategory->update($validated);

        return redirect()->route('admin.tour-categories.index')
            ->with('success', 'Tour category updated.');
    }

    public function destroy(TourCategory $tourCategory)
    {
        $tourCategory->safariPackages()->detach();
        $tourCategory->delete();

        return redirect()->route('admin.tour-categories.index')
            ->with('success', 'Tour category deleted.');
    }
}
