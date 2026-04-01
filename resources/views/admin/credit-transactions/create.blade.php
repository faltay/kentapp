@extends('layouts.admin')

@section('title', __('admin.credit_transactions.assign'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.credit_transactions.assign') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.credit-transactions.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <form id="assign-credit-form">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-coins icon me-1 text-primary"></i>
                        {{ __('admin.credit_transactions.assign') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label required">{{ __('admin.credit_transactions.form.user') }}</label>
                            <select id="user-select" name="user_id" placeholder="{{ __('admin.credit_transactions.form.user_placeholder') }}"></select>
                            <div class="invalid-feedback" data-field="user_id"></div>
                        </div>

                        <div class="col-12" id="balance-info" style="display:none">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="ti ti-info-circle icon me-1"></i>
                                {{ __('admin.credit_transactions.form.current_balance') }}:
                                <strong id="balance-value">0</strong> kontör
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.credit_transactions.form.type') }}</label>
                            <select name="type" class="form-select">
                                <option value="purchase">{{ __('admin.credit_transactions.type_purchase') }}</option>
                                <option value="refund">{{ __('admin.credit_transactions.type_refund') }}</option>
                            </select>
                            <div class="invalid-feedback" data-field="type"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">{{ __('admin.credit_transactions.form.amount') }}</label>
                            <input type="number" name="amount" class="form-control" min="1" max="99999" placeholder="0">
                            <div class="invalid-feedback" data-field="amount"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('admin.credit_transactions.table.description') }}</label>
                            <input type="text" name="description" class="form-control" placeholder="{{ __('admin.credit_transactions.form.description_placeholder') }}">
                            <div class="invalid-feedback" data-field="description"></div>
                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-coins icon me-1"></i>{{ __('admin.credit_transactions.assign_btn') }}
                    </button>
                    <a href="{{ route('admin.credit-transactions.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>{{ __('common.cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
var userSelect = new TomSelect('#user-select', {
    valueField: 'id',
    labelField: 'name',
    searchField: ['name', 'email'],
    maxOptions: 20,
    load: function(query, callback) {
        if (!query.length) return callback();
        fetch('{{ route('admin.credit-transactions.users-search') }}?q=' + encodeURIComponent(query))
            .then(r => r.json())
            .then(callback)
            .catch(() => callback());
    },
    render: {
        option: function(data) {
            var typeLabel = data.type === 'contractor' ? 'Müteahhit' : 'Emlak Danışmanı';
            return '<div class="d-flex justify-content-between align-items-center">' +
                '<div><div class="fw-medium">' + data.name + '</div>' +
                '<div class="text-secondary small">' + data.email + ' &mdash; ' + typeLabel + '</div></div>' +
                '<span class="badge bg-yellow-lt ms-2">' + data.balance + ' kontör</span>' +
                '</div>';
        },
        item: function(data) {
            return '<div>' + data.name + ' <span class="text-secondary small">(' + data.email + ')</span></div>';
        },
        no_results: function() {
            return '<div class="no-results">{{ __('admin.credit_transactions.form.user_no_results') }}</div>';
        }
    },
    onChange: function(value) {
        var item = this.options[value];
        if (item) {
            $('#balance-value').text(item.balance);
            $('#balance-info').show();
        } else {
            $('#balance-info').hide();
        }
    }
});

$('#assign-credit-form').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>{{ __('common.saving') }}');

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.credit-transactions.store') }}', new FormData(this))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            setTimeout(function () { window.location = res.data.data.redirect_url; }, 1500);
        })
        .catch(function (err) {
            if (err.response?.status === 422 && err.response.data?.errors) {
                Object.entries(err.response.data.errors).forEach(function ([field, messages]) {
                    $('[name="' + field + '"]').addClass('is-invalid');
                    $('[data-field="' + field + '"]').text(messages[0]);
                });
            }
            handleAjaxError(err);
            btn.prop('disabled', false).html('<i class="ti ti-coins icon me-1"></i>{{ __('admin.credit_transactions.assign_btn') }}');
        });
});
</script>
@endpush
