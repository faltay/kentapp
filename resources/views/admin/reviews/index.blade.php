@extends('layouts.admin')

@section('title', __('admin.reviews.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.reviews.title') }}</h2>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">{{ __('common.status') }}</label>
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="">{{ __('common.select') }}</option>
                    <option value="pending">{{ __('admin.reviews.status_pending') }}</option>
                    <option value="approved">{{ __('admin.reviews.status_approved') }}</option>
                    <option value="rejected">{{ __('admin.reviews.status_rejected') }}</option>
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
        <table id="reviews-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.reviews.table.reviewer') }}</th>
                    <th>{{ __('admin.reviews.table.reviewed') }}</th>
                    <th>{{ __('admin.reviews.table.rating') }}</th>
                    <th>{{ __('admin.reviews.table.comment') }}</th>
                    <th>{{ __('common.status') }}</th>
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
var table = $('#reviews-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.reviews.index') }}',
        data: function (d) { d.status = $('#filter-status').val(); }
    },
    columns: [
        { data: 'id',                   name: 'id',              width: '50px', className: 'text-secondary' },
        { data: 'reviewer_name',        name: 'reviewer.name' },
        { data: 'reviewed_name',        name: 'reviewed.name' },
        { data: 'rating_stars',         name: 'rating',          orderable: false, searchable: false, className: 'text-warning' },
        { data: 'comment',              name: 'comment',         orderable: false, className: 'text-secondary' },
        { data: 'status_badge',         name: 'status',          orderable: false, searchable: false },
        { data: 'formatted_created_at', name: 'created_at',      className: 'text-secondary' },
        { data: 'actions',              name: 'actions',         orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[0, 'desc']],
    language: window.trans.datatables_language
});

$('#filter-status').on('change', function () { table.ajax.reload(); });
$('#btn-reset-filters').on('click', function () { $('#filter-status').val(''); table.ajax.reload(); });

$(document).on('click', '.btn-approve-review', function () {
    const url = $(this).data('url');
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});
$(document).on('click', '.btn-reject-review', function () {
    const url = $(this).data('url');
    axios.post(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
});
$(document).on('click', '.btn-delete-review', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
    });
});
</script>
@endpush
