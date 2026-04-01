@php
$map = [
    'pending'  => ['bg-yellow-lt', __('admin.reviews.status_pending')],
    'approved' => ['bg-green-lt',  __('admin.reviews.status_approved')],
    'rejected' => ['bg-red-lt',    __('admin.reviews.status_rejected')],
];
[$class, $label] = $map[$review->status] ?? ['bg-secondary-lt', $review->status];
@endphp
<span class="badge {{ $class }}">{{ $label }}</span>
