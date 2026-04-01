<?php

namespace App\Services\Admin;

use App\Models\Listing;
use App\Services\CreditService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListingService
{
    public function __construct(private CreditService $creditService) {}

    public function createListing(array $data, array $documents = [], array $photos = []): Listing
    {
        DB::beginTransaction();

        try {
            $listing = Listing::create($data);

            foreach ($documents as $file) {
                $listing->addMedia($file)->toMediaCollection('documents');
            }

            foreach ($photos as $file) {
                $listing->addMedia($file)->toMediaCollection('photos');
            }

            DB::commit();

            return $listing->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateListing(Listing $listing, array $data, array $documents = [], array $photos = [], array $removeDocumentIds = [], array $removePhotoIds = []): Listing
    {
        DB::beginTransaction();

        try {
            $listing->update($data);

            foreach ($removeDocumentIds as $mediaId) {
                $listing->media()->where('id', $mediaId)->where('collection_name', 'documents')->first()?->delete();
            }

            foreach ($removePhotoIds as $mediaId) {
                $listing->media()->where('id', $mediaId)->where('collection_name', 'photos')->first()?->delete();
            }

            foreach ($documents as $file) {
                $listing->addMedia($file)->toMediaCollection('documents');
            }

            foreach ($photos as $file) {
                $listing->addMedia($file)->toMediaCollection('photos');
            }

            DB::commit();

            return $listing->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing update failed', ['listing_id' => $listing->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateStatus(Listing $listing, string $status): Listing
    {
        DB::beginTransaction();

        try {
            $listing->update(['status' => $status]);

            // Vitrin için ödenen kontörü iade et — ilan reddedilirse
            if ($status === Listing::STATUS_REJECTED && $listing->featured_credit_spent) {
                $listing->update(['featured_credit_spent' => false, 'is_featured' => false]);
                $this->creditService->addCredits(
                    $listing->user,
                    10,
                    'Vitrin ücreti iadesi — ilan reddedildi (#' . $listing->id . ')',
                    'refund'
                );
            }

            DB::commit();

            return $listing->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing status update failed', [
                'listing_id' => $listing->id,
                'status'     => $status,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function toggleFeatured(Listing $listing): Listing
    {
        DB::beginTransaction();

        try {
            $listing->update(['is_featured' => ! $listing->is_featured]);
            DB::commit();

            return $listing->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing featured toggle failed', [
                'listing_id' => $listing->id,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function deleteListing(Listing $listing): void
    {
        DB::beginTransaction();

        try {
            $listing->clearMediaCollection('documents');
            $listing->clearMediaCollection('photos');
            $listing->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing deletion failed', [
                'listing_id' => $listing->id,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
