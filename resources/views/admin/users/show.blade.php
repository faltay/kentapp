@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ $user->name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                <i class="ti ti-pencil icon me-1"></i>{{ __('common.edit') }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<div class="row">

    {{-- Sol Kolon --}}
    <div class="col-lg-4">

        {{-- Kullanıcı Bilgileri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-user icon me-1 text-primary"></i>
                    {{ __('admin.users.form.name') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-lg me-3 rounded"
                          style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=066fd1&color=fff&bold=true&size=128)"></span>
                    <div>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <div class="text-secondary small">{{ $user->email }}</div>
                    </div>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">{{ __('admin.users.form.phone') }}</dt>
                    <dd class="col-7">{{ $user->phone ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('admin.users.form.role') }}</dt>
                    <dd class="col-7">
                        @foreach($user->roles as $role)
                            <span class="badge bg-blue-lt">{{ __('admin.users.roles.' . $role->name) }}</span>
                        @endforeach
                        @if($user->roles->isEmpty()) <span class="text-secondary">—</span> @endif
                    </dd>

                    <dt class="col-5 text-secondary">{{ __('common.status') }}</dt>
                    <dd class="col-7">
                        @if($user->is_active)
                            <span class="badge bg-success-lt text-success">{{ __('common.active') }}</span>
                        @else
                            <span class="badge bg-danger-lt text-danger">{{ __('common.inactive') }}</span>
                        @endif
                    </dd>

                    <dt class="col-5 text-secondary">{{ __('common.created_at') }}</dt>
                    <dd class="col-7 text-secondary small">{{ $user->created_at->format('d.m.Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        {{-- Abonelik Durumu --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-refresh icon me-1 text-primary"></i>
                    {{ __('admin.subscriptions.title') }}
                </h3>
            </div>
            <div class="card-body">
                @if($subscription)
                    <div class="d-flex align-items-center mb-3">
                        <span class="avatar avatar-sm bg-success-lt me-2">
                            <i class="ti ti-circle-check icon text-success"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $subscription->plan?->localized_name ?? '—' }}</div>
                            <div class="text-secondary small">
                                {{ $subscription->billing_cycle === 'yearly'
                                    ? (app()->getLocale() === 'tr' ? 'Yıllık' : 'Yearly')
                                    : (app()->getLocale() === 'tr' ? 'Aylık' : 'Monthly') }}
                            </div>
                        </div>
                        <span class="ms-auto badge bg-success-lt text-success">
                            {{ $subscription->status }}
                        </span>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-5 text-secondary">{{ app()->getLocale() === 'tr' ? 'Başlangıç' : 'Started' }}</dt>
                        <dd class="col-7 small">{{ $subscription->starts_at?->format('d.m.Y') ?? '—' }}</dd>

                        <dt class="col-5 text-secondary">{{ app()->getLocale() === 'tr' ? 'Bitiş' : 'Ends At' }}</dt>
                        <dd class="col-7 small">
                            @if($subscription->ends_at)
                                <span class="{{ $subscription->ends_at->isPast() ? 'text-danger' : '' }}">
                                    {{ $subscription->ends_at->format('d.m.Y') }}
                                </span>
                                @if(!$subscription->ends_at->isPast())
                                    <span class="text-secondary">({{ $subscription->daysLeft() }} {{ app()->getLocale() === 'tr' ? 'gün' : 'days' }})</span>
                                @endif
                            @else
                                <span class="text-success">∞</span>
                            @endif
                        </dd>

                        <dt class="col-5 text-secondary">{{ app()->getLocale() === 'tr' ? 'Tutar' : 'Amount' }}</dt>
                        <dd class="col-7 small">
                            {{ number_format((float)$subscription->amount_paid, 2) }} {{ $subscription->currency }}
                        </dd>
                    </dl>
                @else
                    <div class="text-center text-secondary py-2">
                        <i class="ti ti-circle-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                        <p class="mb-0 small">{{ app()->getLocale() === 'tr' ? 'Aktif abonelik yok' : 'No active subscription' }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Sağ Kolon --}}
    <div class="col-lg-8">

        {{-- İlanlar (Arsa Sahibi ise) --}}
        @if($user->isLandOwner())
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-building-estate icon me-1 text-primary"></i>
                    {{ __('admin.listings.title') }}
                </h3>
                <div class="card-actions">
                    <a href="{{ route('admin.listings.index') }}" class="btn btn-sm">{{ __('common.view_all') }}</a>
                </div>
            </div>
            @php $userListings = $user->listings()->latest()->limit(5)->get(); @endphp
            @if($userListings->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Konum</th>
                            <th>Tür</th>
                            <th>{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userListings as $listing)
                        <tr>
                            <td>{{ $listing->province }} / {{ $listing->district }}</td>
                            <td class="text-secondary">{{ $listing->type === 'urban_renewal' ? 'Kentsel Dönüşüm' : 'Arsa' }}</td>
                            <td>@include('admin.listings.partials.status-badge')</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-3">
                <p class="mb-0 small">Henüz ilan yok.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Kontör Profili (Müteahhit ise) --}}
        @if($user->isContractor() && $user->contractorProfile)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-building-community icon me-1 text-primary"></i>
                    Müteahhit Profili
                </h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">Firma</dt>
                    <dd class="col-7">{{ $user->contractorProfile->company_name ?? '—' }}</dd>
                    <dt class="col-5 text-secondary">Kontör Bakiyesi</dt>
                    <dd class="col-7 fw-bold">{{ $user->contractorProfile->credit_balance }}</dd>
                    <dt class="col-5 text-secondary">Belge Durumu</dt>
                    <dd class="col-7">
                        @php
                            $certMap = ['none'=>'bg-secondary-lt','pending'=>'bg-yellow-lt','approved'=>'bg-green-lt','rejected'=>'bg-red-lt'];
                            $certLabel = ['none'=>'Yok','pending'=>'Beklemede','approved'=>'Onaylı','rejected'=>'Reddedildi'];
                            $cs = $user->contractorProfile->certificate_status;
                        @endphp
                        <span class="badge {{ $certMap[$cs] ?? 'bg-secondary-lt' }}">{{ $certLabel[$cs] ?? $cs }}</span>
                    </dd>
                </dl>
            </div>
        </div>
        @endif

        {{-- Ödeme Geçmişi --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-receipt icon me-1 text-primary"></i>
                    {{ __('admin.payments.title') }}
                </h3>
                <div class="card-actions">
                    <span class="text-secondary small">{{ app()->getLocale() === 'tr' ? 'Son 10 işlem' : 'Last 10 transactions' }}</span>
                </div>
            </div>
            @if($payments->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.payments.table.plan') }}</th>
                            <th>{{ __('admin.payments.table.provider') }}</th>
                            <th>{{ __('admin.payments.table.amount') }}</th>
                            <th>{{ __('admin.payments.table.status') }}</th>
                            <th>{{ __('admin.payments.table.paid_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->subscription?->plan?->localized_name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-azure-lt">{{ ucfirst($payment->provider) }}</span>
                            </td>
                            <td class="fw-medium">
                                {{ number_format((float)$payment->amount, 2) }} {{ $payment->currency }}
                                @if($payment->isRefunded())
                                    <div class="text-danger small">-{{ number_format((float)$payment->refunded_amount, 2) }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $colors = ['succeeded' => 'success', 'failed' => 'danger', 'refunded' => 'warning', 'partially_refunded' => 'orange'];
                                    $color = $colors[$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}-lt text-{{ $color }}">{{ $payment->status }}</span>
                            </td>
                            <td class="text-secondary small">{{ $payment->paid_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-3">
                <i class="ti ti-cash-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                <p class="mb-0 small">{{ __('admin.dashboard.no_data') }}</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
