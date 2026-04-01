@extends('layouts.admin')

@section('title', __('admin.settings.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.settings.title') }}</h2>
        </div>
    </div>
</div>

<form id="settings-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- General Info --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-circle icon me-1 text-primary"></i>
                        {{ __('admin.settings.form.general_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.settings.form.site_name') }}</label>
                            <input type="text" name="site_name" class="form-control"
                                   value="{{ $settings['site_name'] ?? '' }}" required>
                            <div class="invalid-feedback" data-field="site_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.settings.form.meta_title') }}</label>
                            <input type="text" name="meta_title" class="form-control"
                                   value="{{ $settings['meta_title'] ?? '' }}" maxlength="255">
                            <div class="invalid-feedback" data-field="meta_title"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.settings.form.site_description') }}</label>
                            <textarea name="site_description" class="form-control" rows="2" maxlength="500">{{ $settings['site_description'] ?? '' }}</textarea>
                            <div class="invalid-feedback" data-field="site_description"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.settings.form.meta_description') }}</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="255">{{ $settings['meta_description'] ?? '' }}</textarea>
                            <div class="invalid-feedback" data-field="meta_description"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-address-book icon me-1 text-primary"></i>
                        {{ __('admin.settings.form.contact_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.settings.form.contact_email') }}</label>
                            <input type="email" name="contact_email" class="form-control"
                                   value="{{ $settings['contact_email'] ?? '' }}">
                            <div class="invalid-feedback" data-field="contact_email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.settings.form.contact_phone') }}</label>
                            <input type="text" name="contact_phone" class="form-control"
                                   value="{{ $settings['contact_phone'] ?? '' }}" maxlength="20">
                            <div class="invalid-feedback" data-field="contact_phone"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.settings.form.address') }}</label>
                            <textarea name="address" class="form-control" rows="2" maxlength="500">{{ $settings['address'] ?? '' }}</textarea>
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Social Media --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-brand-facebook icon me-1 text-primary"></i>
                        {{ __('admin.settings.form.social_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-brand-facebook icon me-1"></i>
                                {{ __('admin.settings.form.facebook') }}
                            </label>
                            <input type="url" name="facebook" class="form-control"
                                   value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                            <div class="invalid-feedback" data-field="facebook"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-brand-instagram icon me-1"></i>
                                {{ __('admin.settings.form.instagram') }}
                            </label>
                            <input type="url" name="instagram" class="form-control"
                                   value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                            <div class="invalid-feedback" data-field="instagram"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-brand-x icon me-1"></i>
                                {{ __('admin.settings.form.twitter') }}
                            </label>
                            <input type="url" name="twitter" class="form-control"
                                   value="{{ $settings['twitter'] ?? '' }}" placeholder="https://x.com/...">
                            <div class="invalid-feedback" data-field="twitter"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-brand-youtube icon me-1"></i>
                                {{ __('admin.settings.form.youtube') }}
                            </label>
                            <input type="url" name="youtube" class="form-control"
                                   value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                            <div class="invalid-feedback" data-field="youtube"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-brand-tiktok icon me-1"></i>
                                {{ __('admin.settings.form.tiktok') }}
                            </label>
                            <input type="url" name="tiktok" class="form-control"
                                   value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/...">
                            <div class="invalid-feedback" data-field="tiktok"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>
                        {{ __('common.save') }}
                    </button>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Logo --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo icon me-1 text-primary"></i>
                        {{ __('admin.settings.form.logo') }}
                    </h3>
                </div>
                <div class="card-body">
                    @if($mediaSetting && $mediaSetting->hasMedia('logo'))
                        <div class="mb-3 text-center">
                            <img src="{{ $mediaSetting->getFirstMediaUrl('logo', 'medium') }}"
                                 alt="Logo" class="img-fluid rounded" style="max-height: 120px;">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-hint">{{ __('admin.settings.form.logo_hint') }}</div>
                    <div class="invalid-feedback" data-field="logo"></div>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-app-window icon me-1 text-primary"></i>
                        {{ __('admin.settings.form.favicon') }}
                    </h3>
                </div>
                <div class="card-body">
                    @if($mediaSetting && $mediaSetting->hasMedia('favicon'))
                        <div class="mb-3 text-center">
                            <img src="{{ $mediaSetting->getFirstMediaUrl('favicon') }}"
                                 alt="Favicon" class="img-fluid" style="max-height: 64px;">
                        </div>
                    @endif
                    <input type="file" name="favicon" class="form-control" accept="image/png,image/x-icon">
                    <div class="form-hint">{{ __('admin.settings.form.favicon_hint') }}</div>
                    <div class="invalid-feedback" data-field="favicon"></div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$('#settings-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>' + (window.trans?.saving || '{{ __('common.saving') }}'));

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.settings.update') }}', new FormData(this))
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
