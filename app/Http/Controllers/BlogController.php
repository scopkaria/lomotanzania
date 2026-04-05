<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\Page;
use App\Models\Post;
use App\Traits\HasSeoData;
use App\Traits\LoadsSectionData;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasSeoData, LoadsSectionData;

    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'author'])->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $posts      = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])->orderBy('sort_order')->get();
        $page = Page::published()->where('slug', 'blog')->first();
        $sections = $page ? $page->activeSections()->with('heroSlides')->get() : collect();
        $sectionDataMap = [];

        foreach ($sections as $section) {
            $sectionDataMap[$section->id] = $this->loadSectionData($section);
        }

        return view('blog.index', compact('posts', 'categories', 'page', 'sections', 'sectionDataMap') + $this->seoData(null, __('messages.blog'), 'Safari stories, travel guides, and Tanzania tips'));
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
