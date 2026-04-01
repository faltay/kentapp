<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreLanguageRequest;
use App\Http\Requests\Admin\UpdateLanguageRequest;
use App\Models\Language;
use App\Services\Admin\LanguageService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends BaseController
{
    public function __construct(private LanguageService $service)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $languages = Language::query()->orderBy('sort_order');

            return DataTables::of($languages)
                ->addColumn('code_badge', function ($lang) {
                    $class = $lang->is_active ? 'bg-green-lt' : 'bg-secondary-lt';

                    return '<span class="badge ' . $class . '">' . strtoupper($lang->code) . '</span>';
                })
                ->addColumn('language_name', fn ($lang) => e($lang->name) . ' (' . e($lang->native) . ')')
                ->addColumn('default_badge', function ($lang) {
                    if ($lang->is_default) {
                        return '<span class="badge bg-blue-lt">' . __('admin.languages.default') . '</span>';
                    }

                    return '';
                })
                ->addColumn('direction_label', fn ($lang) => strtoupper($lang->direction))
                ->addColumn('status', function ($lang) {
                    if ($lang->is_active) {
                        return '<span class="badge bg-green-lt">' . __('common.active') . '</span>';
                    }

                    return '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn ($lang) => view('admin.languages.partials.actions', ['language' => $lang])->render())
                ->rawColumns(['code_badge', 'default_badge', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.languages.index');
    }

    public function create()
    {
        $nextSortOrder = (int) Language::max('sort_order') + 1;

        return view('admin.languages.create', compact('nextSortOrder'));
    }

    public function store(StoreLanguageRequest $request): JsonResponse
    {
        try {
            $this->service->createLanguage($request->validated());

            return $this->created(
                __('admin.languages.created_successfully'),
                ['redirect_url' => route('admin.languages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.languages.creation_failed'), $e);
        }
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        try {
            $this->service->updateLanguage($language, $request->validated());

            return $this->success(
                __('admin.languages.updated_successfully'),
                ['redirect_url' => route('admin.languages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.languages.update_failed'), $e);
        }
    }

    public function destroy(Language $language): JsonResponse
    {
        $this->authorize('delete', $language);

        try {
            $this->service->deleteLanguage($language);

            return $this->success(__('admin.languages.deleted_successfully'));
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), $e, 422);
        } catch (\Exception $e) {
            return $this->error(__('admin.languages.deletion_failed'), $e);
        }
    }

    public function toggleActive(Language $language): JsonResponse
    {
        try {
            $lang = $this->service->toggleActive($language);
            $msg = $lang->is_active
                ? __('admin.languages.activated_successfully')
                : __('admin.languages.deactivated_successfully');

            return $this->success($msg);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), $e, 422);
        } catch (\Exception $e) {
            return $this->error(__('admin.languages.update_failed'), $e);
        }
    }
}
