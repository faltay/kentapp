@extends('emails.layout')

@section('subject', __('emails.subscription_started.subject', [], 'en'))

@section('content')
<h1>{{ __('emails.subscription_started.heading', [], $locale) }}</h1>
<p>{{ __('emails.subscription_started.greeting', ['name' => $restaurant->owner?->name ?? $restaurant->localized_name], $locale) }}</p>
<p>{{ __('emails.subscription_started.body', [], $locale) }}</p>

<div class="meta">
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_started.plan', [], $locale) }}</span>
        <span class="meta-value">{{ $plan->localized_name }}</span>
    </div>
    @if($subscription->ends_at)
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_started.renewal', [], $locale) }}</span>
        <span class="meta-value">{{ $subscription->ends_at->format('d M Y') }}</span>
    </div>
    @endif
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_started.amount', [], $locale) }}</span>
        <span class="meta-value">
            @if($subscription->amount_paid > 0)
                {{ number_format($subscription->amount_paid, 2) }} {{ $subscription->currency }}
            @else
                {{ __('emails.subscription_started.free', [], $locale) }}
            @endif
        </span>
    </div>
    <div class="meta-row">
        <span class="meta-label">{{ __('emails.subscription_started.status', [], $locale) }}</span>
        <span class="meta-value"><span class="badge badge-success">{{ __('emails.subscription_started.active', [], $locale) }}</span></span>
    </div>
</div>

<a href="{{ route('restaurant.billing.index') }}" class="btn">
    {{ __('emails.subscription_started.cta', [], $locale) }}
</a>
@endsection
