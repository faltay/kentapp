<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends BaseController
{
    public function index()
    {
        if (request()->ajax()) {
            $reviews = Review::with(['reviewer', 'reviewed', 'listing'])
                ->select('reviews.*');

            return DataTables::of($reviews)
                ->addColumn('reviewer_name', fn ($r) => $r->reviewer?->name ?? '—')
                ->addColumn('reviewed_name', fn ($r) => $r->reviewed?->name ?? '—')
                ->addColumn('rating_stars', fn ($r) => str_repeat('★', $r->rating) . str_repeat('☆', 5 - $r->rating))
                ->addColumn('status_badge', fn ($r) => view('admin.reviews.partials.status-badge', ['review' => $r])->render())
                ->addColumn('formatted_created_at', fn ($r) => $r->created_at->format('d.m.Y H:i'))
                ->addColumn('actions', fn ($r) => view('admin.reviews.partials.actions', ['review' => $r])->render())
                ->rawColumns(['rating_stars', 'status_badge', 'actions'])
                ->make(true);
        }

        return view('admin.reviews.index');
    }

    public function approve(Review $review): JsonResponse
    {
        try {
            DB::beginTransaction();
            $review->update(['status' => Review::STATUS_APPROVED]);
            DB::commit();

            return $this->success(__('admin.reviews.approved_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review approval failed', ['review_id' => $review->id, 'error' => $e->getMessage()]);

            return $this->error(__('admin.reviews.approval_failed'), $e);
        }
    }

    public function reject(Review $review): JsonResponse
    {
        try {
            DB::beginTransaction();
            $review->update(['status' => Review::STATUS_REJECTED]);
            DB::commit();

            return $this->success(__('admin.reviews.rejected_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review rejection failed', ['review_id' => $review->id, 'error' => $e->getMessage()]);

            return $this->error(__('admin.reviews.rejection_failed'), $e);
        }
    }

    public function destroy(Review $review): JsonResponse
    {
        try {
            DB::beginTransaction();
            $review->delete();
            DB::commit();

            return $this->success(__('admin.reviews.deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review deletion failed', ['review_id' => $review->id, 'error' => $e->getMessage()]);

            return $this->error(__('admin.reviews.deletion_failed'), $e);
        }
    }
}
