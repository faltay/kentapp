@extends('emails.layout')

@section('subject', __('emails.welcome.subject', [], 'en'))

@section('content')
<h1>{{ __('emails.welcome.heading', [], $locale) }}</h1>
<p>{{ __('emails.welcome.greeting', ['name' => $user->name], $locale) }}</p>
<p>{{ __('emails.welcome.body', [], $locale) }}</p>
<ul>
    <li>{{ __('emails.welcome.feature_menu', [], $locale) }}</li>
    <li>{{ __('emails.welcome.feature_qr', [], $locale) }}</li>
    <li>{{ __('emails.welcome.feature_ai', [], $locale) }}</li>
</ul>
<a href="{{ route('restaurant.dashboard') }}" class="btn">
    {{ __('emails.welcome.cta', [], $locale) }}
</a>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">{{ __('emails.welcome.footer_note', [], $locale) }}</p>
@endsection
