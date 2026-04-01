@extends('layouts.admin')

@section('title', __('admin.listings.edit'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.listings.edit') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.listings.show', $listing) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="edit-listing-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
                                    <option value="{{ $owner->id }}" {{ $listing->user_id == $owner->id ? 'selected' : '' }}>
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
                                <option value="urban_renewal" {{ $listing->type === 'urban_renewal' ? 'selected' : '' }}>{{ __('admin.listings.type_urban_renewal') }}</option>
                                <option value="land" {{ $listing->type === 'land' ? 'selected' : '' }}>{{ __('admin.listings.type_land') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="type"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">{{ __('common.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="pending"  {{ $listing->status === 'pending'  ? 'selected' : '' }}>{{ __('admin.listings.status_pending') }}</option>
                                <option value="draft"    {{ $listing->status === 'draft'    ? 'selected' : '' }}>{{ __('admin.listings.status_draft') }}</option>
                                <option value="active"   {{ $listing->status === 'active'   ? 'selected' : '' }}>{{ __('admin.listings.status_active') }}</option>
                                <option value="passive"  {{ $listing->status === 'passive'  ? 'selected' : '' }}>{{ __('admin.listings.status_passive') }}</option>
                                <option value="rejected" {{ $listing->status === 'rejected' ? 'selected' : '' }}>{{ __('admin.listings.status_rejected') }}</option>
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
                            <select name="province" id="province-select" class="form-select"
                                    data-selected="{{ $listing->province }}">
                                <option value="">{{ __('common.select') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->name }}" data-id="{{ $province->id }}"
                                        {{ $listing->province === $province->name ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="province"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.listings.form.district') }}</label>
                            <select name="district" id="district-select" class="form-select"
                                    data-selected="{{ $listing->district }}" disabled>
                                <option value="">{{ __('admin.listings.form.select_district') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="district"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.neighborhood') }}</label>
                            <select name="neighborhood" id="neighborhood-select" class="form-select"
                                    data-selected="{{ $listing->neighborhood }}" disabled>
                                <option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="neighborhood"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('admin.listings.form.address') }}</label>
                            <textarea name="address" class="form-control" rows="2">{{ $listing->address }}</textarea>
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
                            <input type="text" name="ada_no" class="form-control" value="{{ $listing->ada_no }}">
                            <div class="invalid-feedback" data-field="ada_no"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.parcel_no') }}</label>
                            <input type="text" name="parcel_no" class="form-control" value="{{ $listing->parcel_no }}">
                            <div class="invalid-feedback" data-field="parcel_no"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.area_m2') }}</label>
                            <input type="number" name="area_m2" class="form-control" step="0.01" min="0" value="{{ $listing->area_m2 }}">
                            <div class="invalid-feedback" data-field="area_m2"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('admin.listings.form.floor_count') }}</label>
                            <input type="number" name="floor_count" class="form-control" min="0" value="{{ $listing->floor_count }}">
                            <div class="invalid-feedback" data-field="floor_count"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.zoning_status') }}</label>
                            <select name="zoning_status" class="form-select">
                                <option value="">{{ __('common.select') }}</option>
                                <option value="residential" {{ $listing->zoning_status === 'residential' ? 'selected' : '' }}>{{ __('admin.listings.zoning_residential') }}</option>
                                <option value="commercial"  {{ $listing->zoning_status === 'commercial'  ? 'selected' : '' }}>{{ __('admin.listings.zoning_commercial') }}</option>
                                <option value="mixed"       {{ $listing->zoning_status === 'mixed'       ? 'selected' : '' }}>{{ __('admin.listings.zoning_mixed') }}</option>
                                <option value="unplanned"   {{ $listing->zoning_status === 'unplanned'   ? 'selected' : '' }}>{{ __('admin.listings.zoning_unplanned') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="zoning_status"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.taks') }}</label>
                            <input type="number" name="taks" class="form-control" step="0.01" min="0" max="1" value="{{ $listing->taks }}">
                            <div class="invalid-feedback" data-field="taks"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.listings.form.kaks') }}</label>
                            <input type="number" name="kaks" class="form-control" step="0.01" min="0" value="{{ $listing->kaks }}">
                            <div class="invalid-feedback" data-field="kaks"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('admin.listings.form.description') }}</label>
                            <textarea name="description" class="form-control" rows="4">{{ $listing->description }}</textarea>
                            <div class="invalid-feedback" data-field="description"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Parsel Haritası --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-polygon icon me-1 text-primary"></i>
                        Parsel Geometrisi
                    </h3>
                    <div class="card-options">
                        <button type="button" class="btn btn-sm btn-primary me-2" id="parcel-query-btn">
                            <i class="ti ti-search icon me-1"></i>Parsel Sorgula
                        </button>
                        <button type="button" class="btn btn-sm btn-ghost-danger" id="clear-geometry-btn" style="display:none">
                            <i class="ti ti-trash icon me-1"></i>Çizimi Sil
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="parcel-map" style="height: 420px; width: 100%; border-radius: 0 0 4px 4px;"></div>
                </div>
                <div class="card-footer text-muted small">
                    <i class="ti ti-info-circle icon me-1"></i>
                    Parseli harita üzerinde çizmek için sol menüdeki polygon veya dikdörtgen aracını kullanın. Çizim GeoJSON olarak kaydedilir.
                </div>
                <input type="hidden" name="parcel_geometry" id="parcel-geometry-input"
                       value="{{ $listing->parcel_geometry ? json_encode($listing->parcel_geometry) : '' }}">
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
                    @include('admin.listings.partials.documents-dropzone', [
                        'existingDocs' => $listing->getMedia('documents'),
                    ])
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
                    @include('admin.listings.partials.photos-dropzone', [
                        'existingPhotos' => $listing->getMedia('photos'),
                    ])
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>{{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.listings.show', $listing) }}" class="btn btn-secondary">
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
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input"
                               {{ $listing->is_featured ? 'checked' : '' }}>
                        <span class="form-check-label">{{ __('admin.listings.form.is_featured') }}</span>
                    </label>
                    <div class="form-hint">{{ __('admin.listings.form.is_featured_hint') }}</div>
                </div>
            </div>


        </div>
    </div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script>
// ── Parsel Haritası ──────────────────────────────────────────────────────────
(function () {
    var map = L.map('parcel-map').setView([39.1, 35.6], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 20
    }).addTo(map);

    var drawnItems = new L.FeatureGroup().addTo(map);

    var drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: {
            polygon:      { shapeOptions: { color: '#EA580C', fillOpacity: 0.25 } },
            rectangle:    { shapeOptions: { color: '#EA580C', fillOpacity: 0.25 } },
            polyline:     false,
            circle:       false,
            circlemarker: false,
            marker:       false,
        }
    }).addTo(map);

    function updateInput() {
        var geojson = drawnItems.toGeoJSON();
        if (geojson.features.length > 0) {
            $('#parcel-geometry-input').val(JSON.stringify(geojson));
            $('#clear-geometry-btn').show();
        } else {
            $('#parcel-geometry-input').val('');
            $('#clear-geometry-btn').hide();
        }
    }

    map.on(L.Draw.Event.CREATED, function (e) {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        updateInput();
    });

    map.on(L.Draw.Event.EDITED, updateInput);
    map.on(L.Draw.Event.DELETED, updateInput);

    // Mevcut geometriyi yükle
    var existing = @json($listing->parcel_geometry);
    if (existing) {
        var geojsonLayer = L.geoJSON(existing, {
            style: { color: '#EA580C', fillOpacity: 0.25 },
            onEachFeature: function (feature, layer) {
                drawnItems.addLayer(layer);
            }
        });
        if (drawnItems.getLayers().length > 0) {
            map.fitBounds(drawnItems.getBounds(), { padding: [40, 40] });
        }
        updateInput();
    }

    $('#clear-geometry-btn').on('click', function () {
        drawnItems.clearLayers();
        updateInput();
    });
})();
</script>

<script>
const districtSelected = '{{ $listing->district }}';
const neighborhoodSelected = '{{ $listing->neighborhood }}';

function loadDistricts(provinceId, selectedDistrict, cb) {
    if (!provinceId) return;
    fetch('{{ route('admin.locations.districts') }}?province_id=' + provinceId)
        .then(r => r.json())
        .then(data => {
            const sel = $('#district-select').prop('disabled', false);
            data.forEach(d => {
                sel.append('<option value="' + d.name + '" data-id="' + d.id + '"' +
                    (d.name === selectedDistrict ? ' selected' : '') + '>' + d.name + '</option>');
            });
            if (selectedDistrict && cb) cb();
        });
}

function loadNeighborhoods(districtId, selectedNeighborhood) {
    if (!districtId) return;
    fetch('{{ route('admin.locations.neighborhoods') }}?district_id=' + districtId)
        .then(r => r.json())
        .then(data => {
            const sel = $('#neighborhood-select').prop('disabled', false);
            data.forEach(n => {
                sel.append('<option value="' + n.name + '"' +
                    (n.name === selectedNeighborhood ? ' selected' : '') + '>' + n.name + '</option>');
            });
        });
}

// Init: load districts for current province, then neighborhoods for current district
$(function () {
    const selectedProvince = $('#province-select').find(':selected');
    const provinceId = selectedProvince.data('id');
    if (provinceId) {
        loadDistricts(provinceId, districtSelected, function () {
            const selectedDistrictOpt = $('#district-select').find(':selected');
            const districtId = selectedDistrictOpt.data('id');
            if (districtId) loadNeighborhoods(districtId, neighborhoodSelected);
        });
    }
});

$('#province-select').on('change', function () {
    const option = $(this).find(':selected');
    const provinceId = option.data('id');
    $('#district-select').prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_district') }}</option>');
    $('#neighborhood-select').prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>');
    if (provinceId) loadDistricts(provinceId, null, null);
});

$('#district-select').on('change', function () {
    const option = $(this).find(':selected');
    const districtId = option.data('id');
    $('#neighborhood-select').prop('disabled', true).html('<option value="">{{ __('admin.listings.form.select_neighborhood') }}</option>');
    if (districtId) loadNeighborhoods(districtId, null);
});

$('#edit-listing-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>{{ __('common.saving') }}');

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.listings.update', $listing) }}', new FormData(this))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            setTimeout(function () { window.location.reload(); }, 1500);
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
