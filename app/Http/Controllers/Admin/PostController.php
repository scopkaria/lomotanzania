<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use HasBulkActions;

    protected array $locales = ['en', 'fr', 'de', 'es'];

    protected function bulkModel(): string { return Post::class; }
    protected function allowedBulkActions(): array { return ['delete', 'publish', 'draft']; }

    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])->latest('updated_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title->en', 'like', '%' . $request->search . '%');
        }

        $posts      = $query->paginate($request->integer('per_page', 15))->withQueryString();
        $categories = BlogCategory::orderBy('name->en')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post'       => null,
            'categories' => BlogCategory::orderBy('name->en')->get(),
            'locales'    => $this->locales,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePost($request);

        $post = Post::create([
            'title'            => $data['title'],
            'slug'             => $data['slug'] ?: Str::slug($data['title']['en']),
            'content'          => $data['content'] ?? [],
            'excerpt'          => $data['excerpt'] ?? [],
            'featured_image'   => $data['featured_image'] ?? null,
            'blog_category_id' => $data['blog_category_id'] ?? null,
            'author_id'        => Auth::id(),
            'meta'             => $this->buildMeta($request),
            'status'           => $data['status'],
            'published_at'     => $data['status'] === 'published' ? ($data['published_at'] ?? now()) : $data['published_at'],
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', [
            'post'       => $post,
            'categories' => BlogCategory::orderBy('name->en')->get(),
            'locales'    => $this->locales,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validatePost($request, $post->id);

        $post->update([
            'title'            => $data['title'],
            'slug'             => $data['slug'] ?: Str::slug($data['title']['en']),
            'content'          => $data['content'] ?? $post->content,
            'excerpt'          => $data['excerpt'] ?? $post->excerpt,
            'featured_image'   => $data['featured_image'] ?? $post->featured_image,
            'blog_category_id' => $data['blog_category_id'] ?? null,
            'meta'             => $this->buildMeta($request),
            'status'           => $data['status'],
            'published_at'     => $data['status'] === 'published'
                ? ($data['published_at'] ?? $post->published_at ?? now())
                : $data['published_at'],
        ]);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    protected function validatePost(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'            => 'required|array',
            'title.en'         => 'required|string|max:255',
            'title.fr'         => 'nullable|string|max:255',
            'title.de'         => 'nullable|string|max:255',
            'title.es'         => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:posts,slug' . ($ignoreId ? ",{$ignoreId}" : ''),
            'content'          => 'nullable|array',
            'content.*'        => 'nullable|string',
            'excerpt'          => 'nullable|array',
            'excerpt.*'        => 'nullable|string|max:500',
            'featured_image'   => 'nullable|string|max:500',
            'blog_category_id' => 'nullable|integer|exists:blog_categories,id',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
        ]);
    }

    protected function buildMeta(Request $request): array
    {
        return [
            'meta_title'       => $request->input('meta_title', []),
            'meta_description' => $request->input('meta_description', []),
        ];
    }
}
