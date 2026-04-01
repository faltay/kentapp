<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewService
{
    public function create(User $reviewer, array $data): Review
    {
        $alreadyReviewed = Review::where('reviewer_id', $reviewer->id)
            ->where('reviewed_id', $data['reviewed_id'])
            ->exists();

        if ($alreadyReviewed) {
            throw new \RuntimeException('Bu kullanıcıyı daha önce değerlendirdiniz.');
        }

        return DB::transaction(function () use ($reviewer, $data) {
            return Review::create([
                'reviewer_id' => $reviewer->id,
                'reviewed_id' => $data['reviewed_id'],
                'listing_id'  => $data['listing_id'] ?? null,
                'rating'      => $data['rating'],
                'comment'     => $data['comment'] ?? null,
                'status'      => Review::STATUS_PENDING,
            ]);
        });
    }
}
