@php
$map = [
    'active'   => ['bg-green-lt',     __('admin.listings.status_active')],
    'pending'  => ['bg-yellow-lt',    __('admin.listings.status_pending')],
    'rejected' => ['bg-red-lt',       __('admin.listings.status_rejected')],
    'passive'  => ['bg-secondary-lt', __('admin.listings.status_passive')],
    'draft'    => ['bg-secondary-lt', __('admin.listings.status_draft')],
];
[$class, $label] = $map[$listing->status] ?? ['bg-secondary-lt', $listing->status];
@endphp
<span class="badge {{ $class }}">{{ $label }}</span>
