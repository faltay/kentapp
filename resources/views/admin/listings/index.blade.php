@extends('layouts.admin')

@section('title', __('admin.listings.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.listings.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.listings.create') }}" class="btn btn-primary">
                <i class="ti ti-plus icon me-1"></i>{{ __('admin.listings.create') }}
            </a>
        </div>
    </div>
</div>

{{-- Filtreler --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">{{ __('common.status') }}</label>
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="">{{ __('common.select') }}</option>
                    <option value="pending">{{ __('admin.listings.status_pending') }}</option>
                    <option value="active">{{ __('admin.listings.status_active') }}</option>
                    <option value="rejected">{{ __('admin.listings.status_rejected') }}</option>
                    <option value="passive">{{ __('admin.listings.status_passive') }}</option>
                    <option value="draft">{{ __('admin.listings.status_draft') }}</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label">{{ __('admin.listings.type') }}</label>
                <select id="filter-type" class="form-select form-select-sm">
                    <option value="">{{ __('common.select') }}</option>
                    <option value="urban_renewal">{{ __('admin.listings.type_urban_renewal') }}</option>
                    <option value="land">{{ __('admin.listings.type_land') }}</option>
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
        <table id="listings-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.listings.table.owner') }}</th>
                    <th>{{ __('admin.listings.table.location') }}</th>
                    <th>{{ __('admin.listings.table.type') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th>{{ __('admin.listings.featured') }}</th>
                    <th>{{ __('common.created_at') }}</th>
                    <th class="w-1"></th>
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
var table = $('#listings-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.listings.index') }}',
        data: function (d) {
            d.status = $('#filter-status').val();
            d.type   = $('#filter-type').val();
        }
    },
    columns: [
        { data: 'id',                   name: 'id',         width: '50px', className: 'text-secondary' },
        { data: 'owner_name',           name: 'users.name', orderable: false },
        { data: 'location',             name: 'province',   orderable: false },
        { data: 'type_label',           name: 'type',       orderable: false },
        { data: 'status_badge',         name: 'status',     orderable: false, searchable: false },
        { data: 'featured_badge',       name: 'is_featured', orderable: false, searchable: false },
        { data: 'formatted_created_at', name: 'created_at', className: 'text-secondary' },
        { data: 'actions',              name: 'actions',    orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[0, 'desc']],
    language: window.trans.datatables_language
});

$('#filter-status, #filter-type').on('change', function () { table.ajax.reload(); });
$('#btn-reset-filters').on('click', function () {
    $('#filter-status, #filter-type').val('');
    table.ajax.reload();
});

$(document).on('click', '.btn-approve-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_approve') }}')) return;
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});

$(document).on('click', '.btn-reject-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_reject') }}')) return;
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});

$(document).on('click', '.btn-passive-listing', function () {
    const url = $(this).data('url');
    if (!confirm('{{ __('admin.listings.confirm_passive') }}')) return;
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});

$(document).on('click', '.btn-toggle-featured', function () {
    const url = $(this).data('url');
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});

$(document).on('click', '.btn-delete-listing', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
    });
});
</script>
@endpush
