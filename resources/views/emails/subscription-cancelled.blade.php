@extends('emails.layout')

@section('subject', __('emails.subscription_cancelled.subject', [], 'en'))

@section('content')
<h1>{{ __('emails.subscription_cancelled.heading', [], $locale) }}</h1>
<p>{{ __('emails.subscription_cancelled.greeting', ['name' => $restaurant->owner?->name ?? $restaurant->localized_name], $locale) }}</p>
<p>{{ __('emails.subscription_cancelled.body', [], $locale) }}</p>

<div class="meta">
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_cancelled.plan', [], $locale) }}</span>
        <span class="meta-value">{{ $plan->localized_name }}</span>
    </div>
    @if($subscription->ends_at)
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_cancelled.access_until', [], $locale) }}</span>
        <span class="meta-value">{{ $subscription->ends_at->format('d M Y') }}</span>
    </div>
    @endif
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_cancelled.status', [], $locale) }}</span>
        <span class="meta-value"><span class="badge badge-warning">{{ __('emails.subscription_cancelled.cancelled', [], $locale) }}</span></span>
    </div>
</div>

<p>{{ __('emails.subscription_cancelled.resubscribe_note', [], $locale) }}</p>
<a href="{{ route('restaurant.billing.plans') }}" class="btn">
    {{ __('emails.subscription_cancelled.cta', [], $locale) }}
</a>
@endsection
