<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Payment;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends BaseController
{
    public function index()
    {
        if (request()->ajax()) {
            $payments = Payment::with(['user', 'creditPackage'])
                ->join('users', 'payments.user_id', '=', 'users.id')
                ->select('payments.*');

            if ($provider = request('filter_provider')) {
                $payments->where('payments.provider', $provider);
            }
            if ($status = request('filter_status')) {
                $payments->where('payments.status', $status);
            }
            if ($currency = request('filter_currency')) {
                $payments->where('payments.currency', $currency);
            }
            if ($dateFrom = request('filter_date_from')) {
                $payments->whereDate('payments.created_at', '>=', $dateFrom);
            }
            if ($dateTo = request('filter_date_to')) {
                $payments->whereDate('payments.created_at', '<=', $dateTo);
            }

            return DataTables::of($payments)
                ->addColumn('user_name', fn ($p) => $p->user?->name ?? '—')
                ->addColumn('package_name', fn ($p) => $p->creditPackage?->name ?? '—')
                ->addColumn('amount_formatted', fn ($p) => number_format((float) $p->amount, 2) . ' ' . $p->currency)
                ->addColumn('credits_formatted', fn ($p) => $p->credits > 0 ? $p->credits . ' kontör' : '—')
                ->addColumn('status_badge', function ($p) {
                    $map = [
                        'pending'            => ['secondary', 'clock',            __('admin.payments.status.pending')],
                        'succeeded'          => ['success',   'circle-check',     __('admin.payments.status.succeeded')],
                        'failed'             => ['danger',    'circle-x',         __('admin.payments.status.failed')],
                        'refunded'           => ['warning',   'rotate-clockwise', __('admin.payments.status.refunded')],
                        'partially_refunded' => ['orange',    'rotate-clockwise', __('admin.payments.status.partially_refunded')],
                    ];
                    [$color, $icon, $label] = $map[$p->status] ?? ['secondary', 'circle', $p->status];

                    return "<span class=\"badge bg-{$color}-lt\"><i class=\"ti ti-{$icon} me-1\"></i>{$label}</span>";
                })
                ->addColumn('provider_badge', function ($p) {
                    $map = [
                        'iyzico'        => ['purple', 'credit-card',  'İyzico'],
                        'bank_transfer' => ['blue',   'building-bank','Havale'],
                        'google_pay'    => ['green',  'brand-google', 'Google Pay'],
                        'apple_pay'     => ['dark',   'brand-apple',  'Apple Pay'],
                    ];
                    [$color, $icon, $label] = $map[$p->provider] ?? ['secondary', 'credit-card', ucfirst($p->provider)];

                    return "<span class=\"badge bg-{$color}-lt\"><i class=\"ti ti-{$icon} me-1\"></i>{$label}</span>";
                })
                ->addColumn('created_at_formatted', fn ($p) => $p->created_at->format('d.m.Y H:i'))
                ->filterColumn('user_name', fn ($query, $keyword) => $query->where('users.name', 'like', "%{$keyword}%"))
                ->rawColumns(['status_badge', 'provider_badge'])
                ->make(true);
        }

        return view('admin.payments.index');
    }
}
