@extends('layouts.admin')

@section('title', __('admin.credit_packages.edit'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.credit_packages.edit') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.credit-packages.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="edit-package-form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-coins icon me-1 text-primary"></i>
                        {{ __('admin.credit_packages.form.package_info') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.credit_packages.form.name') }}</label>
                        <input type="text" name="name" class="form-control" required value="{{ $creditPackage->name }}">
                        <div class="invalid-feedback" data-field="name"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">{{ __('admin.credit_packages.form.credits') }}</label>
                            <input type="number" name="credits" class="form-control" required min="1" value="{{ $creditPackage->credits }}">
                            <div class="invalid-feedback" data-field="credits"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">{{ __('admin.credit_packages.form.price') }}</label>
                            <input type="number" name="price" class="form-control" required min="0" step="0.01" value="{{ $creditPackage->price }}">
                            <div class="invalid-feedback" data-field="price"></div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label required">{{ __('admin.credit_packages.form.currency') }}</label>
                            <input type="text" name="currency" class="form-control" required maxlength="3" value="{{ $creditPackage->currency }}">
                            <div class="invalid-feedback" data-field="currency"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.credit_packages.form.sort_order') }}</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ $creditPackage->sort_order }}">
                        <div class="invalid-feedback" data-field="sort_order"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>{{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.credit-packages.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>{{ __('common.cancel') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.status') }}</h3>
                </div>
                <div class="card-body">
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input"
                               @checked($creditPackage->is_active)>
                        <span class="form-check-label">{{ __('admin.credit_packages.form.is_active') }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$('#edit-package-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>{{ __('common.saving') }}');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.put('{{ route('admin.credit-packages.update', $creditPackage) }}', Object.fromEntries(new FormData(this)))
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
