@php
$map = [
    'purchase' => ['bg-green-lt', __('admin.credit_transactions.type_purchase')],
    'spend'    => ['bg-red-lt',   __('admin.credit_transactions.type_spend')],
    'refund'   => ['bg-blue-lt',  __('admin.credit_transactions.type_refund')],
];
[$class, $label] = $map[$transaction->type] ?? ['bg-secondary-lt', $transaction->type];
@endphp
<span class="badge {{ $class }}">{{ $label }}</span>
