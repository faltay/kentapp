@extends('layouts.admin')

@section('title', $landOwner->name)

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ $landOwner->name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.land-owners.edit', $landOwner) }}" class="btn btn-primary me-2">
                <i class="ti ti-pencil icon me-1"></i>{{ __('common.edit') }}
            </a>
            <a href="{{ route('admin.land-owners.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

@php $profile = $landOwner->landOwnerProfile; @endphp

<div class="row">

    {{-- Sol Kolon --}}
    <div class="col-lg-4">

        {{-- Kullanıcı Bilgileri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-user icon me-1 text-primary"></i>
                    {{ __('admin.users.form.user_info') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-lg me-3 rounded"
                          style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($landOwner->name) }}&background=f59f00&color=fff&bold=true&size=128)"></span>
                    <div>
                        <div class="fw-bold">{{ $landOwner->name }}</div>
                        <div class="text-secondary small">{{ $landOwner->email }}</div>
                        @if($landOwner->phone)
                            <div class="text-secondary small">{{ $landOwner->phone }}</div>
                        @endif
                    </div>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">{{ __('admin.land_owners.form.tc_number') }}</dt>
                    <dd class="col-7">{{ $profile?->tc_number ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('common.status') }}</dt>
                    <dd class="col-7">
                        @if($landOwner->is_suspended)
                            <span class="badge bg-red-lt">{{ __('admin.users.suspended') }}</span>
                        @elseif($landOwner->is_active)
                            <span class="badge bg-success-lt text-success">{{ __('common.active') }}</span>
                        @else
                            <span class="badge bg-secondary-lt">{{ __('common.inactive') }}</span>
                        @endif
                    </dd>

                    <dt class="col-5 text-secondary">{{ __('common.created_at') }}</dt>
                    <dd class="col-7 text-secondary small">{{ $landOwner->created_at->format('d.m.Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        {{-- Kontör Bakiyesi --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-coins icon me-1 text-primary"></i>
                    Kontör Bakiyesi
                </h3>
            </div>
            <div class="card-body text-center py-3">
                <div class="display-6 fw-bold text-primary">{{ $profile?->credit_balance ?? 0 }}</div>
                <div class="text-secondary small">kontör</div>
            </div>
        </div>

    </div>

    {{-- Sağ Kolon --}}
    <div class="col-lg-8">

        {{-- Kontör İşlemleri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-coin icon me-1 text-primary"></i>
                    Kontör İşlemleri
                </h3>
                <div class="card-actions">
                    <span class="text-secondary small">Son 15 işlem</span>
                </div>
            </div>
            @if($landOwner->creditTransactions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.credit_transactions.table.type') }}</th>
                            <th>{{ __('admin.credit_transactions.table.amount') }}</th>
                            <th>{{ __('admin.credit_transactions.table.balance_after') }}</th>
                            <th>{{ __('admin.credit_transactions.table.description') }}</th>
                            <th>{{ __('admin.credit_transactions.table.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($landOwner->creditTransactions as $tx)
                        <tr>
                            <td>@include('admin.credit-transactions.partials.type-badge', ['transaction' => $tx])</td>
                            <td class="fw-medium {{ $tx->amount > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                            </td>
                            <td>{{ $tx->balance_after }}</td>
                            <td class="text-secondary small">{{ $tx->description ?? '—' }}</td>
                            <td class="text-secondary small">{{ $tx->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-3">
                <i class="ti ti-coin-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                <p class="mb-0 small">{{ __('admin.dashboard.no_data') }}</p>
            </div>
            @endif
        </div>

        {{-- İlanlar --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-building-estate icon me-1 text-primary"></i>
                    {{ __('admin.listings.title') }}
                </h3>
                <div class="card-actions">
                    <span class="badge bg-azure-lt">{{ $listings->count() }} {{ __('admin.land_owners.listing_count') }}</span>
                </div>
            </div>
            @if($listings->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.listings.table.location') }}</th>
                            <th>{{ __('admin.listings.table.type') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('common.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listings as $listing)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $listing->province }} / {{ $listing->district }}</div>
                                <div class="text-secondary small">{{ $listing->neighborhood }}</div>
                            </td>
                            <td class="text-secondary">
                                {{ $listing->type === 'urban_renewal' ? 'Kentsel Dönüşüm' : 'Arsa' }}
                            </td>
                            <td>@include('admin.listings.partials.status-badge')</td>
                            <td class="text-secondary small">{{ $listing->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-4">
                <i class="ti ti-building-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                <p class="mb-0 small">{{ __('admin.dashboard.no_data') }}</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
