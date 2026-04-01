@php
    $status = $profile?->certificate_status ?? 'none';
    $map = [
        'none'     => ['bg-secondary-lt', __('admin.agents.certificate.none')],
        'pending'  => ['bg-warning-lt text-warning', __('admin.agents.certificate.pending')],
        'approved' => ['bg-success-lt text-success', __('admin.agents.certificate.approved')],
        'rejected' => ['bg-red-lt', __('admin.agents.certificate.rejected')],
    ];
    [$cls, $label] = $map[$status] ?? $map['none'];
@endphp
<span class="badge {{ $cls }}">{{ $label }}</span>
