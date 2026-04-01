@extends('layouts.admin')

@section('title', __('admin.payments.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.payments.title') }}</h2>
        </div>
    </div>
</div>

{{-- Filter Panel --}}
<div class="card mb-3" id="filter-card" style="display:none;">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label">{{ __('admin.payments.filters.provider') }}</label>
                <select id="filter-provider" class="form-select form-select-sm">
                    <option value="">{{ __('admin.payments.filters.all_providers') }}</option>
                    <option value="iyzico">İyzico</option>
                    <option value="bank_transfer">Havale / EFT</option>
                    <option value="google_pay">Google Pay</option>
                    <option value="apple_pay">Apple Pay</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label">{{ __('admin.payments.filters.status') }}</label>
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="">{{ __('admin.payments.filters.all_statuses') }}</option>
                    <option value="pending">{{ __('admin.payments.status.pending') }}</option>
                    <option value="succeeded">{{ __('admin.payments.status.succeeded') }}</option>
                    <option value="failed">{{ __('admin.payments.status.failed') }}</option>
                    <option value="refunded">{{ __('admin.payments.status.refunded') }}</option>
                    <option value="partially_refunded">{{ __('admin.payments.status.partially_refunded') }}</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label class="form-label">{{ __('admin.payments.filters.currency') }}</label>
                <select id="filter-currency" class="form-select form-select-sm">
                    <option value="">{{ __('admin.payments.filters.all_currencies') }}</option>
                    <option value="TRY">TRY (₺)</option>
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label class="form-label">{{ __('admin.payments.filters.date_from') }}</label>
                <input type="date" id="filter-date-from" class="form-control form-control-sm">
            </div>
            <div class="col-sm-6 col-lg-2">
                <label class="form-label">{{ __('admin.payments.filters.date_to') }}</label>
                <input type="date" id="filter-date-to" class="form-control form-control-sm">
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="button" id="apply-filters" class="btn btn-primary btn-sm">
                <i class="ti ti-filter icon me-1"></i>{{ __('common.filter') }}
            </button>
            <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-refresh icon me-1"></i>{{ __('common.reset') }}
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="payments-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.payments.table.user') }}</th>
                    <th>{{ __('admin.payments.table.package') }}</th>
                    <th>{{ __('admin.payments.table.credits') }}</th>
                    <th>{{ __('admin.payments.table.provider') }}</th>
                    <th>{{ __('admin.payments.table.amount') }}</th>
                    <th>{{ __('admin.payments.table.status') }}</th>
                    <th>{{ __('common.created_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
const table = $('#payments-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.payments.index') }}',
        data: function (d) {
            d.filter_provider  = $('#filter-provider').val();
            d.filter_status    = $('#filter-status').val();
            d.filter_currency  = $('#filter-currency').val();
            d.filter_date_from = $('#filter-date-from').val();
            d.filter_date_to   = $('#filter-date-to').val();
        }
    },
    dom: '<"d-flex align-items-center justify-content-between p-3"l<"d-flex align-items-center gap-2"f<"filter-toggle-wrapper">>>rt<"d-flex align-items-center justify-content-between p-3"ip>',
    columns: [
        { data: 'id',                  name: 'payments.id',       width: '50px', className: 'text-secondary', searchable: false },
        { data: 'user_name',           name: 'user_name',         orderable: false },
        { data: 'package_name',        name: 'package_name',      orderable: false, searchable: false, className: 'text-secondary' },
        { data: 'credits_formatted',   name: 'credits_formatted', orderable: false, searchable: false },
        { data: 'provider_badge',      name: 'payments.provider', orderable: false, searchable: false },
        { data: 'amount_formatted',    name: 'payments.amount',   orderable: false, searchable: false },
        { data: 'status_badge',        name: 'payments.status',   orderable: false, searchable: false },
        { data: 'created_at_formatted',name: 'payments.created_at', className: 'text-secondary', searchable: false },
    ],
    order: [[0, 'desc']],
    language: window.trans.datatables_language,
    initComplete: function () {
        $('.filter-toggle-wrapper').html(
            '<button type="button" id="toggle-filters" class="btn btn-outline-secondary btn-sm">' +
                '<i class="ti ti-filter icon me-1"></i>' +
                '{{ __('admin.payments.filters.title') }}' +
            '</button>'
        );
        $('#toggle-filters').on('click', function () {
            $('#filter-card').slideToggle(200);
            $(this).toggleClass('btn-outline-secondary btn-primary');
        });
    }
});

$('#apply-filters').on('click', function () { table.ajax.reload(); });
$('#reset-filters').on('click', function () {
    $('#filter-provider, #filter-status, #filter-currency').val('');
    $('#filter-date-from, #filter-date-to').val('');
    table.ajax.reload();
});
$('#filter-date-from, #filter-date-to').on('keydown', function (e) {
    if (e.key === 'Enter') table.ajax.reload();
});
</script>
@endpush
