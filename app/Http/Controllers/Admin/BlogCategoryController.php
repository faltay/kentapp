<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ReorderBlogCategoryRequest;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use App\Models\Language;
use App\Services\Admin\BlogCategoryService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends BaseController
{
    public function __construct(private BlogCategoryService $service)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $categories = BlogCategory::withCount('posts');
            $languages = Language::getActiveLanguages();

            return DataTables::of($categories)
                ->addColumn('category_name', fn ($cat) => e($cat->localized_name))
                ->addColumn('translations', function ($cat) use ($languages) {
                    $html = '';
                    foreach ($languages as $lang) {
                        $editUrl = route('admin.blog-categories.edit', $cat) . '?lang=' . $lang->code;
                        if ($cat->hasTranslation($lang->code)) {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-green-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        } else {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-secondary-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        }
                    }

                    return $html;
                })
                ->editColumn('posts_count', function ($cat) {
                    if ($cat->posts_count === 0) {
                        return '<span class="text-secondary">0</span>';
                    }

                    $url = route('admin.posts.index') . '?category=' . $cat->id;

                    return '<a href="' . $url . '" class="badge bg-blue-lt text-decoration-none">' . $cat->posts_count . '</a>';
                })
                ->addColumn('status', function ($cat) {
                    if ($cat->is_active) {
                        return '<span class="badge bg-green-lt">' . __('common.active') . '</span>';
                    }

                    return '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn ($cat) => view('admin.blog-categories.partials.actions', ['category' => $cat])->render())
                ->rawColumns(['translations', 'posts_count', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.blog-categories.index');
    }

    public function create()
    {
        $nextSortOrder = (int) BlogCategory::max('sort_order') + 1;
        $editLang = request('lang', default_language_code());

        return view('admin.blog-categories.create', compact('nextSortOrder', 'editLang'));
    }

    public function store(StoreBlogCategoryRequest $request): JsonResponse
    {
        try {
            $this->service->createCategory($request->validated());

            return $this->created(
                __('admin.blog_categories.created_successfully'),
                ['redirect_url' => route('admin.blog-categories.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.blog_categories.creation_failed'), $e);
        }
    }

    public function edit(BlogCategory $blogCategory)
    {
        $editLang = request('lang', default_language_code());

        return view('admin.blog-categories.edit', [
            'category' => $blogCategory,
            'editLang' => $editLang,
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): JsonResponse
    {
        try {
            $this->service->updateCategory($blogCategory, $request->validated());

            return $this->success(
                __('admin.blog_categories.updated_successfully'),
                ['redirect_url' => route('admin.blog-categories.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.blog_categories.update_failed'), $e);
        }
    }

    public function destroy(BlogCategory $blogCategory): JsonResponse
    {
        try {
            $this->service->deleteCategory($blogCategory);

            return $this->success(__('admin.blog_categories.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.blog_categories.deletion_failed'), $e);
        }
    }

    public function reorder(ReorderBlogCategoryRequest $request): JsonResponse
    {
        try {
            $this->service->reorder($request->validated()['items']);

            return $this->success(__('admin.blog_categories.reordered_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.blog_categories.reorder_failed'), $e);
        }
    }

    public function toggleActive(BlogCategory $blogCategory): JsonResponse
    {
        try {
            $category = $this->service->toggleActive($blogCategory);
            $msg = $category->is_active
                ? __('admin.blog_categories.activated_successfully')
                : __('admin.blog_categories.deactivated_successfully');

            return $this->success($msg);
        } catch (\Exception $e) {
            return $this->error(__('admin.blog_categories.update_failed'), $e);
        }
    }
}
