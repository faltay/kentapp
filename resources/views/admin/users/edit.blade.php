@extends('layouts.admin')

@section('title', __('admin.users.edit'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.users.edit') }}: {{ $user->name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="edit-user-form">
    @csrf
    @method('PUT')
    <div class="row">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- User Info --}}
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
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.users.form.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{ __('admin.users.form.phone') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="+90 5xx xxx xx xx">
                            <div class="invalid-feedback" data-field="phone"></div>
                        </div>
                        <div class="col-12">
                            <div class="form-hint mb-2">{{ __('admin.users.form.password_hint') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.users.form.password') }}</label>
                            <input type="password" name="password" class="form-control">
                            <div class="invalid-feedback" data-field="password"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('auth.confirm_password') }}</label>
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
                        <i class="ti ti-check icon me-1"></i>
                        {{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>
                        {{ __('common.cancel') }}
                    </a>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Type & Role --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-shield icon me-1 text-primary"></i>
                        {{ __('admin.users.form.type') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.users.form.type') }}</label>
                        <select name="type" class="form-select" required>
                            <option value="land_owner" @selected($user->type === 'land_owner')>{{ __('admin.users.form.type_land_owner') }}</option>
                            <option value="contractor" @selected($user->type === 'contractor')>{{ __('admin.users.form.type_contractor') }}</option>
                            <option value="agent" @selected($user->type === 'agent')>{{ __('admin.users.form.type_agent') }}</option>
                        </select>
                        <div class="invalid-feedback" data-field="type"></div>
                    </div>
                    <div>
                        <label class="form-label required">{{ __('admin.users.form.role') }}</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" @selected($user->roles->first()?->name === $key)>{{ __('admin.users.roles.' . $key) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" data-field="role"></div>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.status') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                   @checked($user->is_active)>
                            <span class="form-check-label">{{ __('admin.users.form.is_active') }}</span>
                        </label>
                    </div>
                    <div class="mb-0">
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_suspended" value="1" class="form-check-input"
                                   @checked($user->is_suspended)>
                            <span class="form-check-label text-danger">{{ __('admin.users.form.is_suspended') }}</span>
                        </label>
                        <div class="form-hint text-warning">{{ __('admin.users.form.is_suspended_hint') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$('#edit-user-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>' + (window.trans?.saving || '{{ __('common.saving') }}'));

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.users.update', $user) }}', new FormData(this))
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
