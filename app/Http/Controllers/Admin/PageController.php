<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Language;
use App\Models\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PageController extends BaseController
{
    public function __construct(private PageService $pageService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $pages = Page::select('pages.*');
            $languages = Language::getActiveLanguages();

            return DataTables::of($pages)
                ->addColumn('page_title', fn ($page) => $page->localized_title)
                ->addColumn('translations', function ($page) use ($languages) {
                    $html = '';
                    foreach ($languages as $lang) {
                        $editUrl = route('admin.pages.edit', $page) . '?lang=' . $lang->code;
                        if ($page->hasTranslation($lang->code)) {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-green-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        } else {
                            $html .= '<a href="' . $editUrl . '" class="badge bg-secondary-lt text-decoration-none me-1" title="' . e($lang->name) . '">' . strtoupper($lang->code) . '</a>';
                        }
                    }

                    return $html;
                })
                ->addColumn('status', function ($page) {
                    return $page->is_published
                        ? '<span class="badge bg-green-lt">' . __('admin.pages.status_published') . '</span>'
                        : '<span class="badge bg-secondary-lt">' . __('admin.pages.status_draft') . '</span>';
                })
                ->addColumn('actions', fn ($page) => view('admin.pages.partials.actions', compact('page'))->render())
                ->rawColumns(['translations', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.pages.index');
    }

    public function create()
    {
        $editLang = request('lang', default_language_code());

        return view('admin.pages.create', compact('editLang'));
    }

    public function store(StorePageRequest $request): JsonResponse
    {
        try {
            $this->pageService->createPage($request->validated());

            return $this->created(
                __('admin.pages.created_successfully'),
                ['redirect_url' => route('admin.pages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.pages.creation_failed'), $e);
        }
    }

    public function edit(Page $page)
    {
        $editLang = request('lang', default_language_code());

        return view('admin.pages.edit', compact('page', 'editLang'));
    }

    public function update(UpdatePageRequest $request, Page $page): JsonResponse
    {
        try {
            $this->pageService->updatePage($page, $request->validated());

            return $this->success(
                __('admin.pages.updated_successfully'),
                ['redirect_url' => route('admin.pages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.pages.update_failed'), $e);
        }
    }

    public function destroy(Page $page): JsonResponse
    {
        try {
            $this->pageService->deletePage($page);

            return $this->success(__('admin.pages.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.pages.deletion_failed'), $e);
        }
    }
}
