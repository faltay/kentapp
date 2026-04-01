<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AgentProfile;
use App\Models\ContractorProfile;
use App\Models\CreditTransaction;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardController extends BaseController
{
    public function index()
    {
        try {
            $pendingCertificates = ContractorProfile::where('certificate_status', ContractorProfile::CERTIFICATE_PENDING)->count()
                + AgentProfile::where('certificate_status', AgentProfile::CERTIFICATE_PENDING)->count();

            $stats = [
                'total_users'           => User::count(),
                'total_contractors'     => User::contractors()->count(),
                'total_land_owners'     => User::landOwners()->count(),
                'total_agents'          => User::where('type', User::TYPE_AGENT)->count(),
                'total_listings'        => Listing::count(),
                'pending_listings'      => Listing::pending()->count(),
                'pending_certificates'  => $pendingCertificates,
                'pending_reviews'       => Review::pending()->count(),
                'total_payments'        => Payment::where('status', Payment::STATUS_SUCCEEDED)->count(),
            ];

            $recentListings = Listing::with('user')
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            $recentTransactions = CreditTransaction::with('user')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact(
                'stats',
                'recentListings',
                'recentTransactions',
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard loading failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }
}
