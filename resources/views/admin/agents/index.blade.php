@extends('layouts.admin')

@section('title', __('admin.agents.title'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.agents.title') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.agents.create') }}" class="btn btn-primary">
                <i class="ti ti-plus icon me-1"></i>
                {{ __('admin.agents.create') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="agents-table" class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.users.form.name') }}</th>
                        <th>{{ __('admin.users.form.phone') }}</th>
                        <th>{{ __('admin.agents.form.company_name') }}</th>
                        <th>{{ __('admin.listings.title') }}</th>
                        <th>{{ __('admin.agents.credit_balance') }}</th>
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
$('#agents-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.agents.index') }}',
    columns: [
        {
            data: 'name', name: 'name',
            render: function(data, type, row) {
                return '<div class="d-flex align-items-center gap-2">' +
                    '<span class="avatar avatar-sm bg-teal-lt"><i class="ti ti-user-star icon"></i></span>' +
                    '<div><div class="fw-medium">' + row.name + '</div><div class="text-secondary small">' + row.email + '</div></div>' +
                    '</div>';
            }
        },
        { data: 'phone', name: 'phone', defaultContent: '—' },
        { data: 'company_name', name: 'agentProfile.company_name', orderable: false, searchable: false, defaultContent: '—' },
        { data: 'listings_count', name: 'listings_count', orderable: false, searchable: false,
            render: function(val) {
                return '<span class="badge bg-azure-lt">' + val + '</span>';
            }
        },
        { data: 'credit_balance', name: 'credit_balance', orderable: false, searchable: false,
            render: function(val) {
                return '<span class="badge bg-yellow-lt">' + val + '</span>';
            }
        },
        { data: 'status', name: 'is_active', orderable: false, searchable: false },
        { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[0, 'asc']],
    language: window.trans.datatables_language,
});

$(document).on('click', '.btn-delete', function () {
    var btn = $(this);
    if (!confirm('{{ __('admin.agents.confirm_delete') }} (' + btn.data('name') + ')')) return;

    axios.delete(btn.data('url'))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            $('#agents-table').DataTable().ajax.reload(null, false);
        })
        .catch(handleAjaxError);
});
</script>
@endpush
