@extends('layouts.admin')

@section('title', __('admin.listings.show'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.listings.show') }}</h2>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <a href="{{ route('admin.listings.edit', $listing) }}" class="btn btn-primary">
                <i class="ti ti-pencil icon me-1"></i>{{ __('common.edit') }}
            </a>
            <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">

        {{-- İlan Bilgileri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-home-2 icon me-1 text-primary"></i>
                    {{ __('admin.listings.form.listing_info') }}
                </h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">{{ __('admin.listings.form.type') }}</dt>
                    <dd class="col-sm-8">
                        {{ $listing->type === \App\Models\Listing::TYPE_URBAN_RENEWAL
                            ? __('admin.listings.type_urban_renewal')
                            : __('admin.listings.type_land') }}
                    </dd>

                    <dt class="col-sm-4">{{ __('common.status') }}</dt>
                    <dd class="col-sm-8">@include('admin.listings.partials.status-badge')</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.province') }}</dt>
                    <dd class="col-sm-8">{{ $listing->province }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.district') }}</dt>
                    <dd class="col-sm-8">{{ $listing->district }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.neighborhood') }}</dt>
                    <dd class="col-sm-8">{{ $listing->neighborhood ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.address') }}</dt>
                    <dd class="col-sm-8">{{ $listing->address ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.ada_no') }}</dt>
                    <dd class="col-sm-8">{{ $listing->ada_no ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.parcel_no') }}</dt>
                    <dd class="col-sm-8">{{ $listing->parcel_no ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.pafta') }}</dt>
                    <dd class="col-sm-8">{{ $listing->pafta ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.area_m2') }}</dt>
                    <dd class="col-sm-8">{{ $listing->area_m2 ? $listing->area_m2 . ' m²' : '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.floor_count') }}</dt>
                    <dd class="col-sm-8">{{ $listing->floor_count ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.zoning_status') }}</dt>
                    <dd class="col-sm-8">{{ $listing->zoning_status ? __('admin.listings.zoning_' . $listing->zoning_status) : '—' }}</dd>

                    <dt class="col-sm-4">TAKS</dt>
                    <dd class="col-sm-8">{{ $listing->taks ?? '—' }}</dd>

                    <dt class="col-sm-4">KAKS</dt>
                    <dd class="col-sm-8">{{ $listing->kaks ?? '—' }}</dd>

                    <dt class="col-sm-4">Gabari</dt>
                    <dd class="col-sm-8">{{ $listing->gabari ? $listing->gabari . ' m' : '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.agreement_model') }}</dt>
                    <dd class="col-sm-8">
                        @if($listing->agreement_model)
                            {{ __('admin.listings.agreement_' . $listing->agreement_model) }}
                        @else
                            —
                        @endif
                    </dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.description') }}</dt>
                    <dd class="col-sm-8">{{ $listing->description ?? '—' }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.view_count') }}</dt>
                    <dd class="col-sm-8">{{ $listing->view_count }}</dd>

                    <dt class="col-sm-4">{{ __('admin.listings.form.expires_at') }}</dt>
                    <dd class="col-sm-8">{{ $listing->expires_at?->format('d.m.Y H:i') ?? '—' }}</dd>
                </dl>
            </div>
        </div>

    </div>

    <div class="col-lg-4">

        {{-- Sahip Bilgileri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-user icon me-1 text-primary"></i>
                    {{ __('admin.listings.owner_info') }}
                </h3>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $listing->user?->name }}</strong></p>
                <p class="mb-1 text-secondary">{{ $listing->user?->email }}</p>
                <p class="mb-0 text-secondary">{{ $listing->user?->phone ?? '—' }}</p>
            </div>
        </div>

        {{-- İşlemler --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('common.actions') }}</h3>
            </div>
            <div class="card-body d-grid gap-2">
                @if($listing->status === \App\Models\Listing::STATUS_PENDING)
                <button class="btn btn-success btn-approve-listing" data-url="{{ route('admin.listings.approve', $listing) }}">
                    <i class="ti ti-check icon me-1"></i>{{ __('admin.listings.approve') }}
                </button>
                <button class="btn btn-danger btn-reject-listing" data-url="{{ route('admin.listings.reject', $listing) }}">
                    <i class="ti ti-x icon me-1"></i>{{ __('admin.listings.reject') }}
                </button>
                @endif
                @if($listing->status === \App\Models\Listing::STATUS_ACTIVE)
                <button class="btn btn-warning btn-passive-listing" data-url="{{ route('admin.listings.passive', $listing) }}">
                    <i class="ti ti-eye-off icon me-1"></i>{{ __('admin.listings.passive') }}
                </button>
                @endif
                <button class="btn {{ $listing->is_featured ? 'btn-yellow' : 'btn-outline-yellow' }} btn-toggle-featured"
                        data-url="{{ route('admin.listings.toggle-featured', $listing) }}">
                    <i class="ti ti-star icon me-1"></i>
                    {{ $listing->is_featured ? __('admin.listings.remove_featured') : __('admin.listings.set_featured') }}
                </button>
            </div>
        </div>

        {{-- İstatistikler --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('admin.listings.stats') }}</h3>
            </div>
            <div class="card-body">
                <p class="mb-1">{{ __('admin.listings.total_views') }}: <strong>{{ $listing->views->count() }}</strong></p>
                <p class="mb-0">{{ __('admin.listings.total_reviews') }}: <strong>{{ $listing->reviews->count() }}</strong></p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-approve-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_approve') }}')) return;
    axios.post(url).then(res => {
        handleAjaxSuccess(res.data.message);
        setTimeout(() => window.location.reload(), 1500);
    }).catch(err => handleAjaxError(err));
});
$(document).on('click', '.btn-reject-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_reject') }}')) return;
    axios.post(url).then(res => {
        handleAjaxSuccess(res.data.message);
        setTimeout(() => window.location.reload(), 1500);
    }).catch(err => handleAjaxError(err));
});
$(document).on('click', '.btn-passive-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_passive') }}')) return;
    axios.post(url).then(res => {
        handleAjaxSuccess(res.data.message);
        setTimeout(() => window.location.reload(), 1500);
    }).catch(err => handleAjaxError(err));
});
$(document).on('click', '.btn-toggle-featured', function () {
    const url = $(this).data('url');
    axios.post(url).then(res => {
        handleAjaxSuccess(res.data.message);
        setTimeout(() => window.location.reload(), 1500);
    }).catch(err => handleAjaxError(err));
});
</script>
@endpush
