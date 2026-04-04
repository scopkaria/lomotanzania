<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Destination;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Destination::class; }

    public function index(Request $request)
    {
        $query = Destination::with('country')->withCount('safariPackages');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }
        $destinations = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        $countries = Country::orderBy('name')->get();
        return view('admin.destinations.index', compact('destinations', 'countries'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.destinations.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id'     => 'required|integer|exists:countries,id',
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:destinations,slug',
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

        Destination::create($validated);

        $destination = Destination::where('slug', $validated['slug'])->first();
        $this->saveTranslations($destination, $request);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.destinations.edit', compact('destination', 'countries'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'country_id'     => 'required|integer|exists:countries,id',
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:destinations,slug,' . $destination->id,
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

        $destination->update($validated);
        $this->saveTranslations($destination, $request);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->featured_image) {
            Storage::disk('public')->delete($destination->featured_image);
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination deleted.');
    }

    protected function saveTranslations(Destination $destination, Request $request): void
    {
        $locales = ['en', 'fr', 'de', 'es'];

        foreach (['name', 'description'] as $field) {
            $translations = [];
            foreach ($locales as $locale) {
                $value = $request->input("translations.{$locale}.{$field}");
                if (filled($value)) {
                    $translations[$locale] = $value;
                }
            }
            if (! empty($translations)) {
                $destination->setTranslations($field, $translations);
            }
        }

        $destination->save();
    }
}
