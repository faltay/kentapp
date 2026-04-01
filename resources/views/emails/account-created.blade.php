@extends('emails.layout')

@section('subject', __('emails.account_created.subject', ['app_name' => config('app.name')], 'en'))

@section('content')
<h1>{{ __('emails.account_created.heading', [], $locale) }}</h1>
<p>{{ __('emails.account_created.greeting', ['name' => $user->name], $locale) }}</p>
<p>{{ __('emails.account_created.body', [], $locale) }}</p>

<div class="meta">
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.account_created.email', [], $locale) }}</span>
        <span class="meta-value">{{ $user->email }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.account_created.password', [], $locale) }}</span>
        <span class="meta-value">{{ $plainPassword }}</span>
    </div>
</div>

<p style="font-size:13px;color:#e2863b;">{{ __('emails.account_created.password_warning', [], $locale) }}</p>

<a href="{{ url('/login') }}" class="btn">
    {{ __('emails.account_created.cta', [], $locale) }}
</a>

<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">{{ __('emails.account_created.footer_note', ['app_name' => config('app.name')], $locale) }}</p>
@endsection
