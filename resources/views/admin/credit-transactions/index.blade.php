@extends('layouts.admin')

@section('title', __('admin.credit_transactions.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.credit_transactions.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.credit-transactions.create') }}" class="btn btn-primary">
                <i class="ti ti-coins icon me-1"></i>
                {{ __('admin.credit_transactions.assign') }}
            </a>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">{{ __('admin.credit_transactions.type') }}</label>
                <select id="filter-type" class="form-select form-select-sm">
                    <option value="">{{ __('common.select') }}</option>
                    <option value="purchase">{{ __('admin.credit_transactions.type_purchase') }}</option>
                    <option value="spend">{{ __('admin.credit_transactions.type_spend') }}</option>
                    <option value="refund">{{ __('admin.credit_transactions.type_refund') }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button id="btn-reset-filters" class="btn btn-sm btn-secondary">
                    <i class="ti ti-refresh icon me-1"></i>{{ __('common.reset') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="transactions-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.credit_transactions.table.contractor') }}</th>
                    <th>{{ __('admin.users.form.email') }}</th>
                    <th>{{ __('admin.credit_transactions.table.listing') }}</th>
                    <th>{{ __('admin.credit_transactions.table.type') }}</th>
                    <th>{{ __('admin.credit_transactions.table.amount') }}</th>
                    <th>{{ __('admin.credit_transactions.table.balance_after') }}</th>
                    <th>{{ __('admin.credit_transactions.table.description') }}</th>
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
var table = $('#transactions-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.credit-transactions.index') }}',
        data: function (d) { d.type = $('#filter-type').val(); }
    },
    columns: [
        { data: 'id',                   name: 'id',         width: '50px', className: 'text-secondary' },
        { data: 'user_name',            name: 'users.name' },
        { data: 'user_email',           name: 'users.email', className: 'text-secondary small' },
        { data: 'listing_info',         name: 'listings.province', orderable: false },
        { data: 'type_badge',           name: 'type', orderable: false, searchable: false },
        { data: 'formatted_amount',     name: 'amount', className: 'fw-bold' },
        { data: 'balance_after',        name: 'balance_after' },
        { data: 'description',          name: 'description', orderable: false },
        { data: 'formatted_created_at', name: 'created_at', className: 'text-secondary' },
    ],
    order: [[0, 'desc']],
    language: window.trans.datatables_language
});

$('#filter-type').on('change', function () { table.ajax.reload(); });
$('#btn-reset-filters').on('click', function () { $('#filter-type').val(''); table.ajax.reload(); });
</script>
@endpush
