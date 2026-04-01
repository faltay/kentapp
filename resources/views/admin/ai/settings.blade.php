@extends('layouts.admin')

@section('title', 'AI Bağlantı Ayarları')

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">AI Bağlantı Ayarları</h2>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <a href="{{ route('admin.ai.prompt') }}" class="btn btn-secondary">
                <i class="ti ti-prompt icon me-1"></i>Prompt Ayarları
            </a>
            <a href="{{ route('admin.ai.conversations.index') }}" class="btn btn-secondary">
                <i class="ti ti-messages icon me-1"></i>Konuşmalar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">

        <form id="ai-settings-form">
            @csrf

            {{-- Aktif/Pasif --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold">AI Asistan</div>
                            <div class="text-secondary small">Kapalıyken kullanıcılar sohbet başlatamaz, admin manuel yanıt verebilir.</div>
                        </div>
                        <label class="form-check form-switch mb-0">
                            <input type="checkbox" name="ai_enabled" value="1" class="form-check-input"
                                   {{ $config->ai_enabled ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Sağlayıcı & Model --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-robot icon me-1 text-primary"></i>
                        API Sağlayıcısı
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label required">Sağlayıcı</label>
                            <select name="provider" id="provider-select" class="form-select">
                                @foreach(\App\Models\AiConfig::PROVIDERS as $val => $label)
                                    <option value="{{ $val }}" {{ $config->provider === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="provider"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Model</label>
                            <select name="model" id="model-select" class="form-select">
                                @foreach(\App\Models\AiConfig::MODELS as $provider => $models)
                                    <optgroup label="{{ \App\Models\AiConfig::PROVIDERS[$provider] }}"
                                              data-provider="{{ $provider }}">
                                        @foreach($models as $val => $label)
                                            <option value="{{ $val }}" {{ $config->model === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="model"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label required">API Key</label>
                            <div class="input-group">
                                <input type="password" name="api_key" id="api-key-input"
                                       class="form-control font-monospace"
                                       placeholder="{{ $config->api_key ? $config->masked_api_key : 'sk-...' }}"
                                       autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary" id="toggle-key-btn">
                                    <i class="ti ti-eye icon"></i>
                                </button>
                            </div>
                            @if($config->api_key)
                                <div class="form-hint text-success">
                                    <i class="ti ti-check icon me-1"></i>API key kayıtlı. Değiştirmek için yeni key girin, boş bırakırsanız mevcut korunur.
                                </div>
                            @else
                                <div class="form-hint text-warning">
                                    <i class="ti ti-alert-triangle icon me-1"></i>API key henüz girilmemiş.
                                </div>
                            @endif
                            <div class="invalid-feedback" data-field="api_key"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Parametreler --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-adjustments icon me-1 text-primary"></i>
                        Parametreler
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label required">Temperature
                                <span class="text-secondary small">(0 = kesin, 2 = yaratıcı)</span>
                            </label>
                            <input type="number" name="temperature" class="form-control"
                                   step="0.05" min="0" max="2" value="{{ $config->temperature }}">
                            <div class="invalid-feedback" data-field="temperature"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Max Tokens</label>
                            <input type="number" name="max_tokens" class="form-control"
                                   min="100" max="8000" value="{{ $config->max_tokens }}">
                            <div class="invalid-feedback" data-field="max_tokens"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Günlük Mesaj Limiti
                                <span class="text-secondary small">(kullanıcı başına)</span>
                            </label>
                            <input type="number" name="daily_message_limit" class="form-control"
                                   min="1" max="500" value="{{ $config->daily_message_limit }}">
                            <div class="invalid-feedback" data-field="daily_message_limit"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex justify-content-end gap-2">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>Kaydet
                    </button>
                </div>
            </div>

        </form>

    </div>

    {{-- Sağ: Bilgi kartı --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-info-circle icon me-1 text-primary"></i>Model Rehberi</h3>
            </div>
            <div class="card-body">
                <p class="small text-secondary mb-2">
                    <strong class="text-body">GPT-4o</strong> — En yetenekli OpenAI modeli. Yüksek kaliteli yanıtlar.
                </p>
                <p class="small text-secondary mb-2">
                    <strong class="text-body">GPT-4o Mini</strong> — Daha hızlı ve ekonomik. Basit sorular için yeterli.
                </p>
                <p class="small text-secondary mb-2">
                    <strong class="text-body">Claude Sonnet 4.6</strong> — Anthropic'in dengeli modeli. Güçlü ve hızlı.
                </p>
                <p class="small text-secondary mb-0">
                    <strong class="text-body">Claude Opus 4.6</strong> — En gelişmiş Claude modeli. En yüksek maliyet.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Provider değişince model optgroup'larını filtrele
function filterModels(provider) {
    $('#model-select optgroup').each(function () {
        const show = $(this).data('provider') === provider;
        $(this).toggle(show);
        if (show) {
            const first = $(this).find('option').first();
            if (!$(this).find('option:selected').length) first.prop('selected', true);
        } else {
            $(this).find('option').prop('selected', false);
        }
    });
}

$('#provider-select').on('change', function () { filterModels(this.value); });
filterModels($('#provider-select').val());

// API key göster/gizle
$('#toggle-key-btn').on('click', function () {
    const input = $('#api-key-input');
    const isPass = input.attr('type') === 'password';
    input.attr('type', isPass ? 'text' : 'password');
    $(this).find('i').toggleClass('ti-eye ti-eye-off');
});

// Form submit
$('#ai-settings-form').on('submit', function (e) {
    e.preventDefault();
    const btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>Kaydediliyor…');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.ai.settings.update') }}', new FormData(this))
        .then(res => {
            handleAjaxSuccess(res.data.message);
        })
        .catch(err => {
            if (err.response?.status === 422) {
                Object.entries(err.response.data.errors ?? {}).forEach(([field, msgs]) => {
                    $('[name="' + field + '"]').addClass('is-invalid');
                    $('[data-field="' + field + '"]').text(msgs[0]);
                });
            }
            handleAjaxError(err);
        })
        .finally(() => btn.prop('disabled', false).html('<i class="ti ti-check icon me-1"></i>Kaydet'));
});
</script>
@endpush
