@extends('layouts.admin')

@section('title', __('admin.land_owners.create'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.land_owners.create') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.land-owners.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="create-land-owner-form">
    @csrf
    <div class="row">

        {{-- Sol Kolon --}}
        <div class="col-lg-8">

            {{-- Hesap Bilgileri --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-user icon me-1 text-primary"></i>
                        {{ __('admin.users.form.user_info') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.users.form.name') }}</label>
                            <input type="text" name="name" class="form-control" autofocus>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.users.form.email') }}</label>
                            <input type="email" name="email" class="form-control">
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.users.form.phone') }}</label>
                            <input type="text" name="phone" class="form-control" placeholder="+90 5xx xxx xx xx">
                            <div class="invalid-feedback" data-field="phone"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.land_owners.form.tc_number') }}</label>
                            <input type="text" name="tc_number" class="form-control" maxlength="11" placeholder="xxxxxxxxxxx">
                            <div class="invalid-feedback" data-field="tc_number"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.users.form.password') }}</label>
                            <input type="password" name="password" class="form-control">
                            <div class="invalid-feedback" data-field="password"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('auth.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            <div class="invalid-feedback" data-field="password_confirmation"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>{{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.land-owners.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>{{ __('common.cancel') }}
                    </a>
                </div>
            </div>

        </div>

        {{-- Sağ Kolon --}}
        <div class="col-lg-4">

            {{-- Durum --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.status') }}</h3>
                </div>
                <div class="card-body">
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                        <span class="form-check-label">{{ __('admin.users.form.is_active') }}</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$('#create-land-owner-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>{{ __('common.saving') }}');

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.land-owners.store') }}', new FormData(this))
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
