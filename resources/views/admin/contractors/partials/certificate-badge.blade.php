@php
    $map = [
        'none'     => ['bg-secondary-lt', 'Yok'],
        'pending'  => ['bg-yellow-lt text-yellow', 'Beklemede'],
        'approved' => ['bg-green-lt text-green', 'Onaylı'],
        'rejected' => ['bg-red-lt text-red', 'Reddedildi'],
    ];
    $status = $profile?->certificate_status ?? 'none';
    [$cls, $label] = $map[$status] ?? ['bg-secondary-lt', $status];
@endphp
<span class="badge {{ $cls }}">{{ $label }}</span>
