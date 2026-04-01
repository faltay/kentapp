@extends('layouts.admin')

@section('title', __('admin.users.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.users.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="ti ti-plus icon"></i>
                {{ __('admin.users.create') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="users-table" class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('admin.users.form.name') }}</th>
                    <th>{{ __('admin.users.form.email') }}</th>
                    <th>{{ __('admin.users.form.role') }}</th>
                    <th>{{ __('common.status') }}</th>
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
$('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.users.index') }}',
    columns: [
        { data: 'id', name: 'id', width: '50px', className: 'text-secondary' },
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email', className: 'text-secondary' },
        { data: 'role', name: 'roles.name', orderable: false, searchable: false, className: 'text-secondary' },
        { data: 'status', name: 'is_active', orderable: false, searchable: false },
        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    language: window.trans.datatables_language
});

$(document).on('click', '.btn-delete-user', function () {
    const url = $(this).data('url');
    confirmDelete(() => {
        axios.delete(url)
            .then(res => {
                handleAjaxSuccess(res.data.message);
                $('#users-table').DataTable().ajax.reload();
            })
            .catch(err => handleAjaxError(err));
    });
});
</script>
@endpush
