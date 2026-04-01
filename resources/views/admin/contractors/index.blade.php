@extends('layouts.admin')

@section('title', __('admin.contractors.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.contractors.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.contractors.create') }}" class="btn btn-primary">
                <i class="ti ti-plus icon me-1"></i>
                {{ __('admin.contractors.create') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="contractors-table" class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.users.form.name') }}</th>
                        <th>{{ __('admin.contractors.form.company_name') }}</th>
                        <th>{{ __('admin.contractors.credit_balance') }}</th>
                        <th>{{ __('admin.contractors.form.certificate_status') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th class="text-end">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$('#contractors-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.contractors.index') }}',
    columns: [
        {
            data: 'name', name: 'name',
            render: function(data, type, row) {
                return '<div class="d-flex align-items-center gap-2">' +
                    '<span class="avatar avatar-sm bg-blue-lt"><i class="ti ti-building-community icon"></i></span>' +
                    '<div><div class="fw-medium">' + row.name + '</div><div class="text-secondary small">' + row.email + '</div></div>' +
                    '</div>';
            }
        },
        { data: 'company_name', name: 'company_name', orderable: false, searchable: false },
        {
            data: 'credit_balance', name: 'credit_balance', orderable: false, searchable: false,
            render: function(val) {
                return '<span class="fw-medium">' + val + '</span> <small class="text-secondary">kontör</small>';
            }
        },
        { data: 'certificate_status', name: 'certificate_status', orderable: false, searchable: false },
        { data: 'status', name: 'is_active', orderable: false, searchable: false },
        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[0, 'asc']],
    language: window.trans.datatables_language,
});

$(document).on('click', '.btn-delete', function () {
    var btn = $(this);
    var name = btn.data('name');
    var url = btn.data('url');

    if (!confirm('{{ __('admin.contractors.confirm_delete') }} (' + name + ')')) return;

    axios.delete(url)
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            $('#contractors-table').DataTable().ajax.reload(null, false);
        })
        .catch(handleAjaxError);
});
</script>
@endpush
