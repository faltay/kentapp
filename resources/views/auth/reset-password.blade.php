@extends('layouts.auth')

@section('title', __('auth.reset_password'))

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('auth.reset_password') }}</h2>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label required" for="email">{{ __('auth.email') }}</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $email ?? '') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required" for="password">{{ __('auth.new_password') }}</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
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
                    required
                >
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('auth.reset_password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
