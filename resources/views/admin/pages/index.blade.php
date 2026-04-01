@extends('layouts.admin')

@section('title', __('admin.pages.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.pages.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <div class="btn-list">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus icon"></i>
                    {{ __('admin.pages.create') }}
                </a>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary d-sm-none btn-icon">
                    <i class="ti ti-plus icon"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="pages-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>{{ __('admin.pages.table.id') }}</th>
                    <th>{{ __('admin.pages.table.title') }}</th>
                    <th>{{ __('admin.pages.table.translations') }}</th>
                    <th>{{ __('admin.pages.table.status') }}</th>
                    <th>{{ __('admin.pages.table.sort_order') }}</th>
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
const pagesTable = $('#pages-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.pages.index') }}',
    columns: [
        { data: 'id',           name: 'id',           width: '50px', className: 'text-secondary' },
        { data: 'page_title',   name: 'title->en' },
        { data: 'translations', name: 'translations',  orderable: false, searchable: false, width: '80px' },
        { data: 'status',       name: 'is_published',  searchable: false },
        { data: 'sort_order',   name: 'sort_order',    width: '80px', className: 'text-secondary' },
        { data: 'actions',      name: 'actions',       orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[4, 'asc']],
    language: window.trans.datatables_language
});

$(document).on('click', '.delete-btn', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url)
            .then(res => {
                handleAjaxSuccess(res.data.message);
                pagesTable.ajax.reload();
            })
            .catch(err => handleAjaxError(err));
    });
});
</script>
@endpush
