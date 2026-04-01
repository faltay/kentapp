@extends('layouts.auth')

@section('title', __('auth.sign_up'))

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('auth.create_account') }}</h2>

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label required" for="name">{{ __('auth.name') }}</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="{{ __('auth.name_placeholder') }}"
                    autofocus
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required" for="email">{{ __('auth.email') }}</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="your@email.com"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required" for="password">{{ __('auth.password') }}</label>
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

            <div class="mb-3">
                <label class="form-label required" for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="{{ __('auth.password_placeholder') }}"
                    required
                >
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('auth.create_account') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    {{ __('auth.have_account') }}
    <a href="{{ route('login') }}">{{ __('auth.sign_in') }}</a>
</div>
@endsection
