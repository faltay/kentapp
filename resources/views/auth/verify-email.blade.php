@extends('layouts.auth')

@section('title', __('auth.verify_email'))

@section('content')
<div class="card card-md">
    <div class="card-body text-center">
        <div class="mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="40" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
        </div>
        <h2 class="h2 mb-2">{{ __('auth.verify_email') }}</h2>
        <p class="text-secondary mb-4">{{ __('auth.verify_email_message') }}</p>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                {{ __('auth.resend_verification') }}
            </button>
        </form>

        <div class="mt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-secondary">
                    {{ __('common.logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
