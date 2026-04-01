<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\BlogCategory;
use App\Models\Language;
use App\Models\Post;
use App\Services\Admin\PostService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PostController extends BaseController
{
    public function __construct(private PostService $postService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $posts = Post::with(['author', 'blogCategory'])->select('posts.*');

            if ($categoryId = request('category')) {
                $posts->where('blog_category_id', $categoryId);
            }
            $languages = Language::getActiveLanguages();

            return DataTables::of($posts)
                ->addColumn('post_title', fn ($post) => $post->localized_title)
                ->addColumn('translations', function ($post) use ($languages) {
                    $html = '';
                    foreach ($languages as $lang) {
                        $editUrl = route('admin.posts.edit', $post) . '?lang=' . $lang->code;
                        if ($post->hasTranslation($lang->code)) {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-green-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        } else {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-secondary-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        }
                    }

                    return $html;
                })
                ->addColumn('author_name', fn ($post) => $post->author->name ?? '—')
                ->addColumn('category_name', fn ($post) => $post->blogCategory ? e($post->blogCategory->localized_name) : '—')
                ->addColumn('status', function ($post) {
                    if ($post->isPublished()) {
                        return '<span class="badge bg-green-lt">' . __('admin.posts.status_published') . '</span>';
                    }

                    return '<span class="badge bg-secondary-lt">' . __('admin.posts.status_draft') . '</span>';
                })
                ->addColumn('published_at_formatted', fn ($post) => $post->published_at?->format('d.m.Y H:i') ?? '—')
                ->addColumn('actions', fn ($post) => view('admin.posts.partials.actions', compact('post'))->render())
                ->rawColumns(['translations', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.posts.index');
    }

    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        $editLang = request('lang', default_language_code());

        return view('admin.posts.create', compact('categories', 'editLang'));
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $this->postService->createPost($request->validated(), auth()->id());

            return $this->created(
                __('admin.posts.created_successfully'),
                ['redirect_url' => route('admin.posts.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.posts.creation_failed'), $e);
        }
    }

    public function edit(Post $post)
    {
        $categories = BlogCategory::active()->ordered()->get();
        $editLang = request('lang', default_language_code());

        return view('admin.posts.edit', compact('post', 'categories', 'editLang'));
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $this->postService->updatePost($post, $request->validated());

            return $this->success(
                __('admin.posts.updated_successfully'),
                ['redirect_url' => route('admin.posts.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.posts.update_failed'), $e);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $this->postService->deletePost($post);

            return $this->success(__('admin.posts.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.posts.deletion_failed'), $e);
        }
    }

    public function togglePublish(Post $post): JsonResponse
    {
        try {
            $post = $this->postService->togglePublish($post);
            $msg = $post->is_published
                ? __('admin.posts.published_successfully')
                : __('admin.posts.unpublished_successfully');

            return $this->success($msg);
        } catch (\Exception $e) {
            return $this->error(__('admin.posts.update_failed'), $e);
        }
    }
}
