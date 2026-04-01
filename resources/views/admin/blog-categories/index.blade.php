@extends('layouts.admin')

@section('title', __('admin.blog_categories.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.blog_categories.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <div class="btn-list">
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus icon"></i>
                    {{ __('admin.blog_categories.create') }}
                </a>
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary d-sm-none btn-icon">
                    <i class="ti ti-plus icon"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="blog-categories-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>{{ __('admin.blog_categories.table.id') }}</th>
                    <th>{{ __('admin.blog_categories.table.name') }}</th>
                    <th>{{ __('admin.blog_categories.table.translations') }}</th>
                    <th>{{ __('admin.blog_categories.table.posts_count') }}</th>
                    <th>{{ __('admin.blog_categories.table.sort_order') }}</th>
                    <th>{{ __('admin.blog_categories.table.status') }}</th>
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
const categoriesTable = $('#blog-categories-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.blog-categories.index') }}',
    columns: [
        { data: 'id',             name: 'id',           width: '50px', className: 'text-secondary' },
        { data: 'category_name',  name: 'name->en' },
        { data: 'translations',   name: 'translations',  orderable: false, searchable: false, width: '80px' },
        { data: 'posts_count',    name: 'posts_count',  orderable: false, searchable: false, className: 'text-secondary' },
        { data: 'sort_order',     name: 'sort_order',   className: 'text-secondary' },
        { data: 'status',         name: 'is_active',    searchable: false },
        { data: 'actions',        name: 'actions',      orderable: false, searchable: false, className: 'text-end' },
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
                categoriesTable.ajax.reload();
            })
            .catch(err => handleAjaxError(err));
    });
});

$(document).on('click', '.toggle-active-btn', function () {
    const url = $(this).data('url');
    axios.post(url)
        .then(res => {
            handleAjaxSuccess(res.data.message);
            categoriesTable.ajax.reload();
        })
        .catch(err => handleAjaxError(err));
});
</script>
@endpush
