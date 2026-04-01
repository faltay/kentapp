@extends('layouts.admin')

@section('title', __('admin.languages.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.languages.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                <i class="ti ti-plus icon"></i>
                {{ __('admin.languages.create') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table id="languages-table" class="table table-vcenter card-table" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('admin.languages.table.code') }}</th>
                    <th>{{ __('admin.languages.table.name') }}</th>
                    <th>{{ __('admin.languages.table.direction') }}</th>
                    <th>{{ __('admin.languages.table.default') }}</th>
                    <th>{{ __('admin.languages.table.status') }}</th>
                    <th class="w-1">{{ __('admin.languages.table.actions') }}</th>
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
$(function () {
    var table = $('#languages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.languages.index') }}',
        columns: [
            { data: 'code_badge', name: 'code', orderable: true, searchable: true },
            { data: 'language_name', name: 'name', orderable: true, searchable: true },
            { data: 'direction_label', name: 'direction', orderable: false, searchable: false },
            { data: 'default_badge', name: 'is_default', orderable: false, searchable: false },
            { data: 'status', name: 'is_active', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        language: window.trans?.datatables_language || {}
    });

    // Delete
    $(document).on('click', '.btn-delete', function () {
        var url = $(this).data('url');
        confirmDelete(function () {
            axios.delete(url)
                .then(function (res) {
                    handleAjaxSuccess(res.data.message);
                    table.ajax.reload();
                })
                .catch(handleAjaxError);
        });
    });

    // Toggle active
    $(document).on('click', '.btn-toggle-active', function () {
        var url = $(this).data('url');
        axios.post(url)
            .then(function (res) {
                handleAjaxSuccess(res.data.message);
                table.ajax.reload();
            })
            .catch(handleAjaxError);
    });
});
</script>
@endpush
