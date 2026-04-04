<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Traits\HasSeoData;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasSeoData;

    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'author'])->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $posts      = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])->orderBy('sort_order')->get();

        return view('blog.index', compact('posts', 'categories') + $this->seoData(null, __('messages.blog'), 'Safari stories, travel guides, and Tanzania tips'));
    }

    public function show(string $locale, string $slug)
    {
        $post = Post::published()->with(['category', 'author'])->where('slug', $slug)->firstOrFail();

        $related = Post::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'related') + $this->seoData($post, $post->translatedTitle(), $post->translatedExcerpt()));
    }
}
