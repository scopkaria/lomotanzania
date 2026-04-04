<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Country;
use App\Models\Destination;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccommodationController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Accommodation::class; }

    public function index(Request $request)
    {
        $query = Accommodation::with(['country', 'destination'])->withCount(['images', 'itineraries']);
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $accommodations = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        $countries = Country::orderBy('name')->get();
        return view('admin.accommodations.index', compact('accommodations', 'countries'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $destinations = Destination::with('country')->orderBy('name')->get();

        return view('admin.accommodations.create', compact('countries', 'destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:accommodations,slug',
            'description' => 'nullable|string',
            'category' => 'required|string|in:luxury,mid-range,budget,high-end',
            'country_id' => 'required|integer|exists:countries,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'gallery_paths' => 'nullable|array',
            'gallery_paths.*' => 'string|max:500',
            'meta_title' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $accommodation = Accommodation::create(
            collect($validated)->except(['gallery_paths', 'translations', 'focus_keyword'])->toArray()
        );

        foreach ($validated['gallery_paths'] ?? [] as $path) {
            $accommodation->images()->create(['image_path' => $path]);
        }

        $this->saveTranslations($accommodation, $request->input('translations', []));

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Accommodation created successfully.');
    }

    public function edit(Accommodation $accommodation)
    {
        $accommodation->load('images');
        $countries = Country::orderBy('name')->get();
        $destinations = Destination::with('country')->orderBy('name')->get();

        return view('admin.accommodations.edit', compact('accommodation', 'countries', 'destinations'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:accommodations,slug,' . $accommodation->id,
            'description' => 'nullable|string',
            'category' => 'required|string|in:luxury,mid-range,budget,high-end',
            'country_id' => 'required|integer|exists:countries,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'gallery_paths' => 'nullable|array',
            'gallery_paths.*' => 'string|max:500',
            'meta_title' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $accommodation->update(
            collect($validated)->except(['gallery_paths', 'translations', 'focus_keyword'])->toArray()
        );

        // Replace all gallery images with the submitted paths
        $accommodation->images()->delete();
        foreach ($validated['gallery_paths'] ?? [] as $path) {
            $accommodation->images()->create(['image_path' => $path]);
        }

        $this->saveTranslations($accommodation, $request->input('translations', []));

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Accommodation updated successfully.');
    }

    public function destroy(Accommodation $accommodation)
    {
        foreach ($accommodation->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $accommodation->delete();

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Accommodation deleted.');
    }

    private function saveTranslations(Accommodation $model, array $translations): void
    {
        foreach (['name', 'description'] as $field) {
            $values = [];
            foreach ($translations as $locale => $fields) {
                if (!empty($fields[$field])) {
                    $values[$locale] = $fields[$field];
                }
            }
            if (!empty($values)) {
                $model->setTranslations($field, $values);
            }
        }
        $model->save();
    }
}