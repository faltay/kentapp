@extends('layouts.auth')

@section('title', __('auth.sign_in'))

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('auth.sign_in') }}</h2>

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">{{ __('auth.email') }}</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="your@email.com"
                    autofocus
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-label" for="password">
                    {{ __('auth.password') }}
                    <span class="form-label-description">
                        <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
                    </span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="{{ __('auth.password_placeholder') }}"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <span class="form-check-label">{{ __('auth.remember_me') }}</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('auth.sign_in') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    {{ __('auth.no_account') }}
    <a href="{{ route('register') }}">{{ __('auth.sign_up') }}</a>
</div>

{{-- Demo Credentials --}}
<div class="mt-4">
    <div class="row g-2">
        <div class="col-6">
            <div class="card card-sm" style="cursor:pointer" onclick="fillLogin('admin@kentapp.test','password')">
                <div class="card-body p-2 text-center">
                    <div class="mb-1">
                        <span class="avatar avatar-sm bg-blue-lt">
                            <i class="ti ti-shield-check icon"></i>
                        </span>
                    </div>
                    <div class="fw-medium small">Super Admin</div>
                    <div class="text-secondary" style="font-size:.7rem">admin@kentapp.test</div>
                    <div class="text-secondary" style="font-size:.7rem">password</div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-sm" style="cursor:pointer" onclick="fillLogin('owner@kentapp.test','password')">
                <div class="card-body p-2 text-center">
                    <div class="mb-1">
                        <span class="avatar avatar-sm bg-green-lt">
                            <i class="ti ti-building-community icon"></i>
                        </span>
                    </div>
                    <div class="fw-medium small">Müteahhit</div>
                    <div class="text-secondary" style="font-size:.7rem">owner@kentapp.test</div>
                    <div class="text-secondary" style="font-size:.7rem">password</div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-sm" style="cursor:pointer" onclick="fillLogin('owner2@kentapp.test','password')">
                <div class="card-body p-2 text-center">
                    <div class="mb-1">
                        <span class="avatar avatar-sm bg-yellow-lt">
                            <i class="ti ti-home-2 icon"></i>
                        </span>
                    </div>
                    <div class="fw-medium small">Arsa Sahibi</div>
                    <div class="text-secondary" style="font-size:.7rem">owner2@kentapp.test</div>
                    <div class="text-secondary" style="font-size:.7rem">password</div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-sm" style="cursor:pointer" onclick="fillLogin('agent@kentapp.test','password')">
                <div class="card-body p-2 text-center">
                    <div class="mb-1">
                        <span class="avatar avatar-sm bg-purple-lt">
                            <i class="ti ti-user-star icon"></i>
                        </span>
                    </div>
                    <div class="fw-medium small">Emlak Danışmanı</div>
                    <div class="text-secondary" style="font-size:.7rem">agent@kentapp.test</div>
                    <div class="text-secondary" style="font-size:.7rem">password</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
@endsection
