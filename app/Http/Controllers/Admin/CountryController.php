<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Country::class; }

    public function index(Request $request)
    {
        $query = Country::withCount('destinations', 'safariPackages');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $countries = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:countries,slug',
            'description'    => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        Country::create($validated);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:countries,slug,' . $country->id,
            'description'    => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $country->update($validated);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        if ($country->featured_image) {
            Storage::disk('public')->delete($country->featured_image);
        }

        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country deleted.');
    }
}
