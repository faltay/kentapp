@extends('layouts.admin')

@section('title', __('admin.posts.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.posts.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <div class="btn-list">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus icon"></i>
                    {{ __('admin.posts.create') }}
                </a>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary d-sm-none btn-icon">
                    <i class="ti ti-plus icon"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="posts-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>{{ __('admin.posts.table.id') }}</th>
                    <th>{{ __('admin.posts.table.title') }}</th>
                    <th>{{ __('admin.posts.table.translations') }}</th>
                    <th>{{ __('admin.posts.table.category') }}</th>
                    <th>{{ __('admin.posts.table.author') }}</th>
                    <th>{{ __('admin.posts.table.status') }}</th>
                    <th>{{ __('admin.posts.table.published_at') }}</th>
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
const postsTable = $('#posts-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.posts.index') }}' + window.location.search,
    columns: [
        { data: 'id',                  name: 'id',           width: '50px', className: 'text-secondary' },
        { data: 'post_title',          name: 'title->en' },
        { data: 'translations',        name: 'translations', orderable: false, searchable: false, width: '80px' },
        { data: 'category_name',       name: 'blog_category_id', orderable: false, searchable: false, className: 'text-secondary' },
        { data: 'author_name',         name: 'users.name',   orderable: false, searchable: false, className: 'text-secondary' },
        { data: 'status',              name: 'is_published', searchable: false },
        { data: 'published_at_formatted', name: 'published_at', className: 'text-secondary' },
        { data: 'actions',             name: 'actions',      orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[6, 'desc']],
    language: window.trans.datatables_language
});

$(document).on('click', '.delete-btn', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url)
            .then(res => {
                handleAjaxSuccess(res.data.message);
                postsTable.ajax.reload();
            })
            .catch(err => handleAjaxError(err));
    });
});

$(document).on('click', '.toggle-publish-btn', function () {
    const url = $(this).data('url');
    axios.post(url)
        .then(res => {
            handleAjaxSuccess(res.data.message);
            postsTable.ajax.reload();
        })
        .catch(err => handleAjaxError(err));
});
</script>
@endpush
