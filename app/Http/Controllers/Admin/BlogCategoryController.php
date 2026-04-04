<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return BlogCategory::class; }

    public function index(Request $request)
    {
        $query = BlogCategory::withCount('posts')->orderBy('sort_order')->orderBy('name->en');
        if ($request->filled('search')) {
            $query->where('name->en', 'like', '%' . $request->search . '%');
        }
        $categories = $query->paginate($request->integer('per_page', 20))->withQueryString();
        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog-categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|array',
            'name.en'    => 'required|string|max:255',
            'name.fr'    => 'nullable|string|max:255',
            'name.de'    => 'nullable|string|max:255',
            'name.es'    => 'nullable|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:blog_categories,slug',
            'sort_order' => 'nullable|integer',
        ]);

        BlogCategory::create([
            'name'       => $data['name'],
            'slug'       => $data['slug'] ?: Str::slug($data['name']['en']),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category created.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories.form', ['category' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $data = $request->validate([
            'name'       => 'required|array',
            'name.en'    => 'required|string|max:255',
            'name.fr'    => 'nullable|string|max:255',
            'name.de'    => 'nullable|string|max:255',
            'name.es'    => 'nullable|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'sort_order' => 'nullable|integer',
        ]);

        $blogCategory->update([
            'name'       => $data['name'],
            'slug'       => $data['slug'] ?: Str::slug($data['name']['en']),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category updated.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category deleted.');
    }
}
