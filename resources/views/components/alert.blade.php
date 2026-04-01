@props([
    'type'        => 'info',
    'dismissible' => true,
    'title'       => null,
])

<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible' : '' }} fade show" role="alert">
    @if($title)
        <h4 class="alert-title">{{ $title }}</h4>
    @endif
    {{ $slot }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    @endif
</div>
