<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreReviewRequest;
use App\Models\Review;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends ApiController
{
    public function __construct(private ReviewService $reviewService) {}

    public function index(Request $request, User $user): JsonResponse
    {
        $reviews = Review::where('reviewed_id', $user->id)
            ->where('status', Review::STATUS_APPROVED)
            ->with('reviewer:id,name')
            ->latest()
            ->paginate(15);

        $avgRating = Review::where('reviewed_id', $user->id)
            ->where('status', Review::STATUS_APPROVED)
            ->avg('rating');

        return $this->success([
            'reviews'    => $reviews->map(fn($r) => [
                'id'         => $r->id,
                'rating'     => $r->rating,
                'comment'    => $r->comment,
                'reviewer'   => ['id' => $r->reviewer?->id, 'name' => $r->reviewer?->name],
                'created_at' => $r->created_at->toISOString(),
            ]),
            'avg_rating' => $avgRating ? round($avgRating, 1) : null,
            'meta'       => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $review = $this->reviewService->create($request->user(), $request->validated());

            return $this->created([
                'review' => ['id' => $review->id, 'status' => $review->status],
            ], 'Değerlendirme gönderildi, onay bekleniyor.');
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('API review store failed', ['error' => $e->getMessage()]);
            return $this->error('Değerlendirme gönderilemedi.');
        }
    }
}
