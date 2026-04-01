@extends('layouts.admin')

@section('title', __('common.dashboard'))

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('common.dashboard') }}</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <a href="{{ route('admin.listings.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus icon me-1"></i>{{ __('admin.listings.create') }}
            </a>
        </div>
    </div>
</div>

{{-- ── Stat Cards ──────────────────────────────────────────────────────────── --}}
<div class="row row-deck row-cards mb-4">

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader flex-fill">{{ __('admin.users.title') }}</div>
                    <span class="avatar avatar-sm bg-blue-lt">
                        <i class="ti ti-users icon text-blue"></i>
                    </span>
                </div>
                <div class="h1 mb-1">{{ number_format($stats['total_users']) }}</div>
                <div class="text-secondary small">
                    {{ $stats['total_contractors'] }} {{ __('admin.contractors.title') }}
                    &middot; {{ $stats['total_agents'] }} {{ __('admin.agents.title') }}
                    &middot; {{ $stats['total_land_owners'] }} {{ __('admin.land_owners.title') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader flex-fill">{{ __('admin.listings.title') }}</div>
                    <span class="avatar avatar-sm bg-green-lt">
                        <i class="ti ti-building-estate icon text-green"></i>
                    </span>
                </div>
                <div class="h1 mb-1">{{ number_format($stats['total_listings']) }}</div>
                @if($stats['pending_listings'] > 0)
                <a href="{{ route('admin.listings.index') }}" class="text-warning small fw-medium">
                    <i class="ti ti-clock icon me-1"></i>{{ $stats['pending_listings'] }} {{ __('admin.listings.status_pending') }}
                </a>
                @else
                <div class="text-secondary small">{{ __('admin.dashboard.no_pending') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader flex-fill">{{ __('admin.contractor_certificates.title') }}</div>
                    <span class="avatar avatar-sm bg-yellow-lt">
                        <i class="ti ti-certificate icon text-yellow"></i>
                    </span>
                </div>
                <div class="h1 mb-1">{{ number_format($stats['pending_certificates']) }}</div>
                <div class="text-secondary small">
                    @if($stats['pending_certificates'] > 0)
                    <span class="text-warning">{{ __('admin.dashboard.awaiting_approval') }}</span>
                    @else
                    {{ __('admin.dashboard.no_pending') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader flex-fill">{{ __('admin.reviews.title') }}</div>
                    <span class="avatar avatar-sm bg-red-lt">
                        <i class="ti ti-star icon text-red"></i>
                    </span>
                </div>
                <div class="h1 mb-1">{{ number_format($stats['pending_reviews']) }}</div>
                @if($stats['pending_reviews'] > 0)
                <a href="{{ route('admin.reviews.index') }}" class="text-danger small fw-medium">
                    <i class="ti ti-clock icon me-1"></i>{{ __('admin.dashboard.awaiting_moderation') }}
                </a>
                @else
                <div class="text-secondary small">{{ __('admin.dashboard.no_pending') }}</div>
                @endif
            </div>
        </div>
    </div>

</div>

<div class="row row-deck row-cards">

    {{-- Son İlanlar --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.dashboard.recent_listings') }}</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.listings.index') }}" class="btn btn-sm">{{ __('common.view_all') }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.listings.table.owner') }}</th>
                            <th>{{ __('admin.listings.table.location') }}</th>
                            <th>{{ __('admin.listings.table.type') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentListings as $listing)
                        <tr>
                            <td>{{ $listing->user?->name ?? '—' }}</td>
                            <td class="text-secondary">{{ $listing->province }} / {{ $listing->district }}</td>
                            <td class="text-secondary">
                                {{ $listing->type === \App\Models\Listing::TYPE_URBAN_RENEWAL
                                    ? __('admin.listings.type_urban_renewal')
                                    : __('admin.listings.type_land') }}
                            </td>
                            <td>@include('admin.listings.partials.status-badge')</td>
                            <td>
                                <a href="{{ route('admin.listings.show', $listing) }}" class="text-secondary">
                                    {{ __('common.view') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">{{ __('admin.dashboard.no_listings_yet') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Son Kontör İşlemleri --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.dashboard.recent_transactions') }}</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.credit-transactions.index') }}" class="btn btn-sm">{{ __('common.view_all') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="divide-y">
                    @forelse($recentTransactions as $tx)
                    <div class="row align-items-center py-2">
                        <div class="col-auto">
                            <span class="avatar avatar-sm {{ $tx->type === 'purchase' ? 'bg-green-lt' : 'bg-red-lt' }}">
                                <i class="ti ti-{{ $tx->type === 'purchase' ? 'plus' : 'minus' }} icon"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="text-truncate fw-medium small">{{ $tx->user?->name ?? '—' }}</div>
                            <div class="text-secondary" style="font-size:.7rem">{{ $tx->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="col-auto fw-bold {{ $tx->amount > 0 ? 'text-green' : 'text-red' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-coin-off icon mb-2" style="font-size:2rem;opacity:.3"></i>
                        <p class="mb-0 small">{{ __('admin.dashboard.no_transactions_yet') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
