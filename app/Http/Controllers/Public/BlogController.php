<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::active()->ordered()->withCount([
            'posts' => fn ($q) => $q->published()->translatedIn(),
        ])->get();

        $query = Post::published()->translatedIn()->ordered();

        $activeCategory = null;
        if ($slug = request('category')) {
            $activeCategory = BlogCategory::active()->bySlug($slug)->first();
            if ($activeCategory) {
                $query->inCategory($activeCategory->id);
            }
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $post = Post::with('blogCategory')
            ->bySlug($slug)
            ->published()
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
