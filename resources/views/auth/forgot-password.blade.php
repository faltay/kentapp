@extends('layouts.auth')

@section('title', __('auth.forgot_password'))

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-2">{{ __('auth.forgot_password') }}</h2>
        <p class="text-secondary text-center mb-4">{{ __('auth.forgot_password_message') }}</p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label required" for="email">{{ __('auth.email') }}</label>
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

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('auth.send_reset_link') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    <a href="{{ route('login') }}">{{ __('auth.back_to_login') }}</a>
</div>
@endsection
