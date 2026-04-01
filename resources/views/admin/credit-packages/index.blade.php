@extends('layouts.admin')

@section('title', __('admin.credit_packages.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.credit_packages.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.credit-packages.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus icon"></i>
                {{ __('admin.credit_packages.create') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="credit-packages-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.credit_packages.table.name') }}</th>
                    <th>{{ __('admin.credit_packages.table.credits') }}</th>
                    <th>{{ __('admin.credit_packages.table.price') }}</th>
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
var table = $('#credit-packages-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.credit-packages.index') }}',
    columns: [
        { data: 'id',                   name: 'id',         width: '50px', className: 'text-secondary' },
        { data: 'name',                 name: 'name' },
        { data: 'credits',              name: 'credits' },
        { data: 'formatted_price',      name: 'price', orderable: false, searchable: false },
        { data: 'status',               name: 'is_active', orderable: false, searchable: false },
        { data: 'formatted_created_at', name: 'created_at', className: 'text-secondary' },
        { data: 'actions',              name: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[3, 'asc']],
    language: window.trans.datatables_language
});

$(document).on('click', '.btn-delete-credit-package', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url).then(res => { handleAjaxSuccess(res.data.message); table.ajax.reload(); }).catch(err => handleAjaxError(err));
    });
});
</script>
@endpush
