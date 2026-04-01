@extends('layouts.admin')

@section('title', __('admin.languages.edit'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.languages.edit') }}: {{ $language->name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="edit-language-form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-language icon me-1 text-primary"></i>
                        {{ __('admin.languages.form.info_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.languages.form.code') }}</label>
                            <input type="text" name="code" class="form-control" value="{{ $language->code }}" maxlength="2" required>
                            <div class="form-hint">{{ __('admin.languages.form.code_hint') }}</div>
                            <div class="invalid-feedback" data-field="code"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.languages.form.name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ $language->name }}" required>
                            <div class="form-hint">{{ __('admin.languages.form.name_hint') }}</div>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.languages.form.native') }}</label>
                            <input type="text" name="native" class="form-control" value="{{ $language->native }}" required>
                            <div class="form-hint">{{ __('admin.languages.form.native_hint') }}</div>
                            <div class="invalid-feedback" data-field="native"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.languages.form.flag') }}</label>
                            <input type="text" name="flag" class="form-control" value="{{ $language->flag }}" maxlength="10">
                            <div class="invalid-feedback" data-field="flag"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">{{ __('admin.languages.form.direction') }}</label>
                            <select name="direction" class="form-select" required>
                                <option value="ltr" @selected($language->direction === 'ltr')>{{ __('admin.languages.form.direction_ltr') }}</option>
                                <option value="rtl" @selected($language->direction === 'rtl')>{{ __('admin.languages.form.direction_rtl') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="direction"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.languages.form.sort_order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $language->sort_order }}" min="0">
                            <div class="invalid-feedback" data-field="sort_order"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>
                        {{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>
                        {{ __('common.cancel') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.languages.form.settings_section') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                   @checked($language->is_active)>
                            <span class="form-check-label">{{ __('admin.languages.form.is_active') }}</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_default" value="1" class="form-check-input"
                                   @checked($language->is_default)>
                            <span class="form-check-label">{{ __('admin.languages.form.is_default') }}</span>
                        </label>
                        <div class="form-hint">{{ __('admin.languages.form.is_default_hint') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$('#edit-language-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>' + (window.trans?.saving || '{{ __('common.saving') }}'));

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.languages.update', $language) }}', new FormData(this))
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
