@extends('layouts.admin')

@section('title', __('admin.listings.create'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.listings.create') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="create-listing-form" enctype="multipart/form-data">
    @csrf
    <div class="row">

        {{-- Sol Kolon --}}
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
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label required">{{ __('admin.listings.form.owner') }}</label>
                            <select name="user_id" id="owner-select" class="form-select">
                                <option value="">{{ __('admin.listings.form.owner_placeholder') }}</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">
                                        {{ $owner->name }} ({{ $owner->email }})
                                        — {{ $owner->type === 'agent' ? __('admin.users.form.type_agent') : __('admin.users.form.type_land_owner') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="user_id"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.listings.form.type') }}</label>
                            <select name="type" class="form-select">
                                <option value="urban_renewal">{{ __('admin.listings.type_urban_renewal') }}</option>
                                <option value="land">{{ __('admin.listings.type_land') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="type"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">{{ __('common.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="pending">{{ __('admin.listings.status_pending') }}</option>
                                <option value="draft">{{ __('admin.listings.status_draft') }}</option>
                                <option value="active">{{ __('admin.listings.status_active') }}</option>
                                <option value="passive">{{ __('admin.listings.status_passive') }}</option>
                                <option value="rejected">{{ __('admin.listings.status_rejected') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="status"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Konum --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-map-pin icon me-1 text-primary"></i>
                        {{ __('admin.listings.form.province') }} / {{ __('admin.listings.form.district') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.listings.form.province') }}</label>
                            <select name="province" id="province-select" class="form-select">
                                <option value="">{{ __('common.select') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->name }}" data-id="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="province"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.listings.form.district') }}</label>
                            <select name="district" id="district-select" class="form-select" disabled>
                                <option value="">{{ __('admin.listings.form.select_district') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="district"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.neighborhood') }}</label>
                            <select name="neighborhood" id="neighborhood-select" class="form-select" disabled>
                                <option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="neighborhood"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('admin.listings.form.address') }}</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Tapu / Parsel --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-file-description icon me-1 text-primary"></i>
                        {{ __('admin.listings.form.ada_no') }} / {{ __('admin.listings.form.parcel_no') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.ada_no') }}</label>
                            <input type="text" name="ada_no" class="form-control">
                            <div class="invalid-feedback" data-field="ada_no"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.parcel_no') }}</label>
                            <input type="text" name="parcel_no" class="form-control">
                            <div class="invalid-feedback" data-field="parcel_no"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.area_m2') }}</label>
                            <input type="number" name="area_m2" class="form-control" step="0.01" min="0">
                            <div class="invalid-feedback" data-field="area_m2"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.floor_count') }}</label>
                            <input type="number" name="floor_count" class="form-control" min="0">
                            <div class="invalid-feedback" data-field="floor_count"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.zoning_status') }}</label>
                            <select name="zoning_status" class="form-select">
                                <option value="">{{ __('common.select') }}</option>
                                <option value="residential">{{ __('admin.listings.zoning_residential') }}</option>
                                <option value="commercial">{{ __('admin.listings.zoning_commercial') }}</option>
                                <option value="mixed">{{ __('admin.listings.zoning_mixed') }}</option>
                                <option value="unplanned">{{ __('admin.listings.zoning_unplanned') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="zoning_status"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.taks') }}</label>
                            <input type="number" name="taks" class="form-control" step="0.01" min="0" max="1" placeholder="0.00 - 1.00">
                            <div class="invalid-feedback" data-field="taks"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.kaks') }}</label>
                            <input type="number" name="kaks" class="form-control" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" data-field="kaks"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('admin.listings.form.description') }}</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                            <div class="invalid-feedback" data-field="description"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Belgeler --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-file-upload icon me-1 text-primary"></i>
                        {{ __('admin.listings.form.documents') }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.listings.partials.documents-dropzone')
                </div>
            </div>

            {{-- Fotoğraflar --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo-up icon me-1 text-primary"></i>
                        {{ __('admin.listings.form.photos') }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.listings.partials.photos-dropzone')
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>{{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>{{ __('common.cancel') }}
                    </a>
                </div>
            </div>

        </div>

        {{-- Sağ Kolon --}}
        <div class="col-lg-4">

            {{-- Vitrin --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-star icon me-1 text-primary"></i>
                        {{ __('admin.listings.featured') }}
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input">
                        <span class="form-check-label">{{ __('admin.listings.form.is_featured') }}</span>
                    </label>
                    <div class="form-hint">{{ __('admin.listings.form.is_featured_hint') }}</div>
                </div>
            </div>

            {{-- Bitiş Tarihi --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-calendar icon me-1 text-primary"></i>
                        {{ __('admin.listings.form.expires_at') }}
                    </h3>
                </div>
                <div class="card-body">
                    <input type="datetime-local" name="expires_at" class="form-control">
                    <div class="invalid-feedback" data-field="expires_at"></div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Province → District cascade
$('#province-select').on('change', function () {
    const option = $(this).find(':selected');
    const provinceId = option.data('id');
    const districtSelect = $('#district-select');
    const neighborhoodSelect = $('#neighborhood-select');

    districtSelect.prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_district') }}</option>');
    neighborhoodSelect.prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>');

    if (!provinceId) return;

    fetch('{{ route('admin.locations.districts') }}?province_id=' + provinceId)
        .then(r => r.json())
        .then(data => {
            data.forEach(d => {
                districtSelect.append('<option value="' + d.name + '" data-id="' + d.id + '">' + d.name + '</option>');
            });
            districtSelect.prop('disabled', false);
        });
});

// District → Neighborhood cascade
$('#district-select').on('change', function () {
    const option = $(this).find(':selected');
    const districtId = option.data('id');
    const neighborhoodSelect = $('#neighborhood-select');

    neighborhoodSelect.prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>');

    if (!districtId) return;

    fetch('{{ route('admin.locations.neighborhoods') }}?district_id=' + districtId)
        .then(r => r.json())
        .then(data => {
            data.forEach(n => {
                neighborhoodSelect.append('<option value="' + n.name + '">' + n.name + '</option>');
            });
            neighborhoodSelect.prop('disabled', false);
        });
});

$('#create-listing-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>{{ __('common.saving') }}');

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.listings.store') }}', new FormData(this))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            setTimeout(function () { window.location = res.data.data.redirect_url; }, 1500);
        })
        .catch(function (err) {
            if (err.response?.status === 422 && err.response.data?.errors) {
                Object.entries(err.response.data.errors).forEach(function ([field, messages]) {
                    $('[name="' + field + '"]').addClass('is-invalid');
                    $('[data-field="' + field + '"]').text(messages[0]);
                });
            }
            handleAjaxError(err);
            btn.prop('disabled', false).html('<i class="ti ti-check icon me-1"></i>{{ __('common.save') }}');
        });
});
</script>
@endpush
