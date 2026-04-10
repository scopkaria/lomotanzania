<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourType;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourTypeController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return TourType::class; }

    public function index(Request $request)
    {
        $query = TourType::withCount('safariPackages');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $tourTypes = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        return view('admin.tour-types.index', compact('tourTypes'));
    }

    public function create()
    {
        return view('admin.tour-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tour_types,name',
            'slug' => 'nullable|string|max:255|unique:tour_types,slug',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $tourType = TourType::create(collect($validated)->except(['translations', 'focus_keyword'])->toArray());

        $this->saveTranslations($tourType, $request->input('translations', []));
        $tourType->saveSeoMeta($validated);

        return redirect()->route('admin.tour-types.index')
            ->with('success', 'Tour type created successfully.');
    }

    public function edit(TourType $tourType)
    {
        return view('admin.tour-types.edit', compact('tourType'));
    }

    public function update(Request $request, TourType $tourType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tour_types,name,' . $tourType->id,
            'slug' => 'nullable|string|max:255|unique:tour_types,slug,' . $tourType->id,
            'description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $tourType->update(collect($validated)->except(['translations', 'focus_keyword'])->toArray());

        $this->saveTranslations($tourType, $request->input('translations', []));
        $tourType->saveSeoMeta($validated);

        return redirect()->route('admin.tour-types.index')
            ->with('success', 'Tour type updated successfully.');
    }

    public function destroy(TourType $tourType)
    {
        $tourType->delete();
        return redirect()->route('admin.tour-types.index')
            ->with('success', 'Tour type deleted.');
    }

    private function saveTranslations(TourType $model, array $translations): void
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
