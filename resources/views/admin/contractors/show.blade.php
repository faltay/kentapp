@extends('layouts.admin')

@section('title', $contractor->name)

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ $contractor->name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.contractors.edit', $contractor) }}" class="btn btn-primary me-2">
                <i class="ti ti-pencil icon me-1"></i>{{ __('common.edit') }}
            </a>
            <a href="{{ route('admin.contractors.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

@php $profile = $contractor->contractorProfile; @endphp

<div class="row">

    {{-- Sol Kolon --}}
    <div class="col-lg-4">

        {{-- Kullanıcı Bilgileri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-user icon me-1 text-primary"></i>
                    {{ __('admin.users.form.user_info') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-lg me-3 rounded"
                          style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($contractor->name) }}&background=066fd1&color=fff&bold=true&size=128)"></span>
                    <div>
                        <div class="fw-bold">{{ $contractor->name }}</div>
                        <div class="text-secondary small">{{ $contractor->email }}</div>
                        @if($contractor->phone)
                            <div class="text-secondary small">{{ $contractor->phone }}</div>
                        @endif
                    </div>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">{{ __('common.status') }}</dt>
                    <dd class="col-7">
                        @if($contractor->is_suspended)
                            <span class="badge bg-red-lt">{{ __('admin.users.suspended') }}</span>
                        @elseif($contractor->is_active)
                            <span class="badge bg-success-lt text-success">{{ __('common.active') }}</span>
                        @else
                            <span class="badge bg-secondary-lt">{{ __('common.inactive') }}</span>
                        @endif
                    </dd>
                    <dt class="col-5 text-secondary">{{ __('common.created_at') }}</dt>
                    <dd class="col-7 text-secondary small">{{ $contractor->created_at->format('d.m.Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        {{-- Firma Profili --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-building-community icon me-1 text-primary"></i>
                    {{ __('admin.contractors.form.company_info') }}
                </h3>
            </div>
            @if($profile && $profile->certificate_status !== 'approved')
            <div class="card-body border-bottom pb-3 d-flex gap-2">
                <button class="btn btn-success btn-sm" id="btn-approve"
                        data-url="{{ route('admin.contractors.approve-certificate', $contractor) }}">
                    <i class="ti ti-check icon me-1"></i>Onayla
                </button>
                <button class="btn btn-danger btn-sm" id="btn-reject"
                        data-url="{{ route('admin.contractors.reject-certificate', $contractor) }}">
                    <i class="ti ti-x icon me-1"></i>Reddet
                </button>
                <button class="btn btn-outline-danger btn-sm ms-auto" id="btn-delete"
                        data-url="{{ route('admin.contractors.destroy', $contractor) }}">
                    <i class="ti ti-trash icon me-1"></i>Sil
                </button>
            </div>
            @elseif($profile)
            <div class="card-body border-bottom pb-3 d-flex gap-2">
                <button class="btn btn-outline-warning btn-sm" id="btn-reject"
                        data-url="{{ route('admin.contractors.reject-certificate', $contractor) }}">
                    <i class="ti ti-x icon me-1"></i>Onayı Kaldır
                </button>
                <button class="btn btn-outline-danger btn-sm ms-auto" id="btn-delete"
                        data-url="{{ route('admin.contractors.destroy', $contractor) }}">
                    <i class="ti ti-trash icon me-1"></i>Sil
                </button>
            </div>
            @endif
            <div class="card-body">
                @if($profile)
                <dl class="row mb-0">
                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.company_name') }}</dt>
                    <dd class="col-7">{{ $profile->company_name ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.authorized_name') }}</dt>
                    <dd class="col-7">{{ $profile->authorized_name ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.company_phone') }}</dt>
                    <dd class="col-7">{{ $profile->company_phone ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.company_email') }}</dt>
                    <dd class="col-7 small">{{ $profile->company_email ?? '—' }}</dd>

                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.certificate_status') }}</dt>
                    <dd class="col-7">@include('admin.contractors.partials.certificate-badge', ['profile' => $profile])</dd>

                    @if($profile->certificate_number)
                    <dt class="col-5 text-secondary">{{ __('admin.contractors.form.certificate_number') }}</dt>
                    <dd class="col-7 small">{{ $profile->certificate_number }}</dd>
                    @endif

                    @if($profile->certificate_rejection_reason)
                    <dt class="col-5 text-secondary">Red Sebebi</dt>
                    <dd class="col-7 small text-danger">{{ $profile->certificate_rejection_reason }}</dd>
                    @endif
                </dl>
                @else
                <p class="text-secondary small mb-0">{{ __('admin.contractors.no_profile') }}</p>
                @endif
            </div>
        </div>

        {{-- Çalışma Bölgeleri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-map-pin icon me-1 text-primary"></i>
                    {{ __('admin.contractors.form.working_neighborhoods') }}
                </h3>
            </div>
            <div class="card-body">
                @php $areas = $profile?->working_neighborhoods ?? []; @endphp
                @if(count($areas))
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($areas as $area)
                            @php
                                $label = is_array($area)
                                    ? (($area['district'] ?? '') . ' / ' . ($area['neighborhood'] ?? ''))
                                    : $area;
                            @endphp
                            <span class="badge bg-blue-lt">{{ $label }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary small mb-0">{{ __('admin.contractors.form.no_areas') }}</p>
                @endif
            </div>
        </div>

        {{-- Kontör Bakiyesi --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-coins icon me-1 text-primary"></i>
                    {{ __('admin.contractors.credit_balance') }}
                </h3>
            </div>
            <div class="card-body text-center py-3">
                <div class="display-6 fw-bold text-primary">{{ $profile?->credit_balance ?? 0 }}</div>
                <div class="text-secondary small">kontör</div>
            </div>
        </div>

    </div>

    {{-- Sağ Kolon --}}
    <div class="col-lg-8">

        {{-- Son Kontör İşlemleri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-coin icon me-1 text-primary"></i>
                    {{ __('admin.credit_transactions.title') }}
                </h3>
                <div class="card-actions">
                    <span class="text-secondary small">Son 10 işlem</span>
                </div>
            </div>
            @if($contractor->creditTransactions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.credit_transactions.table.type') }}</th>
                            <th>{{ __('admin.credit_transactions.table.amount') }}</th>
                            <th>{{ __('admin.credit_transactions.table.balance_after') }}</th>
                            <th>{{ __('admin.credit_transactions.table.description') }}</th>
                            <th>{{ __('admin.credit_transactions.table.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contractor->creditTransactions as $tx)
                        <tr>
                            <td>@include('admin.credit-transactions.partials.type-badge', ['transaction' => $tx])</td>
                            <td class="fw-medium {{ $tx->amount > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                            </td>
                            <td>{{ $tx->balance_after }}</td>
                            <td class="text-secondary small">{{ $tx->description ?? '—' }}</td>
                            <td class="text-secondary small">{{ $tx->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-3">
                <i class="ti ti-coin-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                <p class="mb-0 small">{{ __('admin.dashboard.no_data') }}</p>
            </div>
            @endif
        </div>

        {{-- Son Görüntülenen İlanlar --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-eye icon me-1 text-primary"></i>
                    {{ __('admin.contractors.recent_views') }}
                </h3>
                <div class="card-actions">
                    <span class="text-secondary small">Son 5 ilan</span>
                </div>
            </div>
            @if($recentViews->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>İlan</th>
                            <th>Harcanan Kontör</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentViews as $view)
                        <tr>
                            <td>
                                @if($view->listing)
                                    {{ $view->listing->province }} / {{ $view->listing->district }}
                                    <div class="text-secondary small">{{ $view->listing->type === 'urban_renewal' ? 'Kentsel Dönüşüm' : 'Arsa' }}</div>
                                @else
                                    <span class="text-secondary">—</span>
                                @endif
                            </td>
                            <td class="text-danger fw-medium">-{{ $view->credits_spent }}</td>
                            <td class="text-secondary small">{{ \Carbon\Carbon::parse($view->viewed_at)->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-secondary py-3">
                <i class="ti ti-eye-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                <p class="mb-0 small">{{ __('admin.dashboard.no_data') }}</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

{{-- Rejection Reason Modal --}}
<div class="modal modal-blur fade" id="modal-reject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sertifikayı Reddet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label required">Red Sebebi</label>
                <textarea id="rejection-reason" class="form-control" rows="3" maxlength="500" placeholder="Reddetme sebebini girin..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-danger" id="btn-reject-confirm">Reddet</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rejectUrl = null;

$('#btn-approve').on('click', function () {
    const btn = $(this);
    axios.post(btn.data('url'))
        .then(res => {
            handleAjaxSuccess(res.data.message);
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => handleAjaxError(err));
});

$('#btn-reject').on('click', function () {
    rejectUrl = $(this).data('url');
    $('#rejection-reason').val('');
    $('#modal-reject').modal('show');
});

$('#btn-reject-confirm').on('click', function () {
    const reason = $('#rejection-reason').val().trim();
    if (!reason) {
        $('#rejection-reason').addClass('is-invalid');
        return;
    }
    $('#rejection-reason').removeClass('is-invalid');

    axios.post(rejectUrl, { reason })
        .then(res => {
            $('#modal-reject').modal('hide');
            handleAjaxSuccess(res.data.message);
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => handleAjaxError(err));
});

$('#btn-delete').on('click', function () {
    if (!confirm('Bu müteahhiti silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) return;
    axios.delete($(this).data('url'))
        .then(res => {
            handleAjaxSuccess(res.data.message);
            setTimeout(() => window.location.href = '{{ route('admin.contractors.index') }}', 1000);
        })
        .catch(err => handleAjaxError(err));
});
</script>
@endpush
