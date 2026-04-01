@extends('emails.layout')

@section('subject', __('emails.payment_failed.subject', [], 'en'))

@section('content')
<h1>{{ __('emails.payment_failed.heading', [], $locale) }}</h1>
<p>{{ __('emails.payment_failed.greeting', ['name' => $restaurant->owner?->name ?? $restaurant->localized_name], $locale) }}</p>
<p>{{ __('emails.payment_failed.body', [], $locale) }}</p>

<div class="meta">
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.payment_failed.restaurant', [], $locale) }}</span>
        <span class="meta-value">{{ $restaurant->localized_name }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.payment_failed.amount', [], $locale) }}</span>
        <span class="meta-value">{{ number_format($amount, 2) }} {{ $currency }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.payment_failed.status', [], $locale) }}</span>
        <span class="meta-value"><span class="badge badge-danger">{{ __('emails.payment_failed.failed', [], $locale) }}</span></span>
    </div>
</div>

<p>{{ __('emails.payment_failed.action_note', [], $locale) }}</p>
<a href="{{ route('restaurant.billing.plans') }}" class="btn">
    {{ __('emails.payment_failed.cta', [], $locale) }}
</a>
@endsection
