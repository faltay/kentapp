<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;

class StaticPageController extends Controller
{
    public function home()
    {
        $page = Page::homepage()->published()->first();

        if (!$page) {
            return view('welcome');
        }

        return view('pages.show', compact('page'));
    }

    public function show(string $slug)
    {
        $page = Page::bySlug($slug)
            ->published()
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
