<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Category::class; }

    public function index(Request $request)
    {
        $query = Category::withCount('safariPackages');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $categories = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
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

        $category = Category::create(collect($validated)->except(['translations', 'focus_keyword'])->toArray());

        $this->saveTranslations($category, $request->input('translations', []));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
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

        $category->update(collect($validated)->except(['translations', 'focus_keyword'])->toArray());

        $this->saveTranslations($category, $request->input('translations', []));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }

    private function saveTranslations(Category $model, array $translations): void
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
