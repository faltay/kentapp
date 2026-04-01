<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Listing;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    /**
     * GET /notifications
     * Authenticated kullanıcının bildirimleri (en son 50, okunmamışlar önce)
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->with('sender:id,name')
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'data'       => $n->data,
                'sender'     => ['id' => $n->sender?->id, 'name' => $n->sender?->name],
                'read_at'    => $n->read_at?->toISOString(),
                'created_at' => $n->created_at->toISOString(),
            ]);

        $unreadCount = UserNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return $this->success([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * POST /notifications/send-listing
     * Arsa sahibi seçili müteahhitlere ilan daveti gönderir.
     *
     * body: { contractor_ids: [1,2,3], listing_id: 4 }
     */
    public function sendListing(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->type, [User::TYPE_LAND_OWNER, User::TYPE_AGENT])) {
            return $this->forbidden('Sadece arsa sahipleri bildirim gönderebilir.');
        }

        $data = $request->validate([
            'contractor_ids'   => ['required', 'array', 'min:1', 'max:50'],
            'contractor_ids.*' => ['integer', 'exists:users,id'],
            'listing_id'       => ['required', 'integer'],
        ]);

        // İlan kendi ilanı ve aktif mi?
        $listing = Listing::where('id', $data['listing_id'])
            ->where('user_id', $user->id)
            ->where('status', Listing::STATUS_ACTIVE)
            ->first();

        if (! $listing) {
            return $this->error('İlan bulunamadı veya aktif değil.', 422);
        }

        // Sadece aktif ve onaylı contractor/agent kullanıcılara gönder
        $contractors = User::whereIn('id', $data['contractor_ids'])
            ->whereIn('type', [User::TYPE_CONTRACTOR, User::TYPE_AGENT])
            ->where('is_active', true)
            ->where('is_suspended', false)
            ->pluck('id');

        if ($contractors->isEmpty()) {
            return $this->error('Geçerli müteahhit bulunamadı.', 422);
        }

        $notifData = [
            'listing_id'   => $listing->id,
            'neighborhood' => $listing->neighborhood,
            'district'     => $listing->district,
            'province'     => $listing->province,
            'type'         => $listing->type,
        ];

        $records = $contractors->map(fn($cid) => [
            'user_id'    => $cid,
            'sender_id'  => $user->id,
            'type'       => 'listing_invite',
            'data'       => json_encode($notifData),
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        UserNotification::insert($records);

        return $this->success(['sent_count' => count($records)], 'Bildirimler gönderildi.');
    }

    /**
     * POST /notifications/{id}/read
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $notification = UserNotification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $notification) {
            return $this->notFound('Bildirim bulunamadı.');
        }

        $notification->update(['read_at' => now()]);

        return $this->success([], 'Okundu.');
    }

    /**
     * POST /notifications/read-all
     */
    public function markAllRead(Request $request): JsonResponse
    {
        UserNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->success([], 'Tümü okundu.');
    }
}
