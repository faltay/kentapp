@extends('layouts.admin')

@section('title', 'AI Prompt Ayarları')

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">Prompt Ayarları</h2>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <a href="{{ route('admin.ai.settings') }}" class="btn btn-secondary">
                <i class="ti ti-settings icon me-1"></i>Bağlantı Ayarları
            </a>
            <a href="{{ route('admin.ai.conversations.index') }}" class="btn btn-secondary">
                <i class="ti ti-messages icon me-1"></i>Konuşmalar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">

        <form id="prompt-form">
            @csrf

            {{-- Karşılama Mesajı --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-message-circle icon me-1 text-primary"></i>
                        Karşılama Mesajı
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-label">Kullanıcı sohbeti açtığında gösterilecek ilk mesaj</label>
                    <textarea name="welcome_message" class="form-control" rows="3"
                              placeholder="Merhaba! Ben KentApp AI Asistanı. Arsa, imar durumu ve kentsel dönüşüm hakkında sorularınızı yanıtlayabilirim.">{{ $config->welcome_message }}</textarea>
                    <div class="form-hint">Boş bırakılırsa karşılama mesajı gösterilmez.</div>
                    <div class="invalid-feedback" data-field="welcome_message"></div>
                </div>
            </div>

            {{-- System Prompt --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-terminal-2 icon me-1 text-primary"></i>
                        System Prompt
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-label">AI'ya verilen temel görev ve davranış talimatları</label>
                    <textarea name="system_prompt" class="form-control font-monospace" rows="16"
                              placeholder="Sen KentApp platformunun AI asistanısın. Kullanıcılara arsa, parsel, imar durumu, kentsel dönüşüm ve platform kullanımı hakkında yardım ediyorsun...">{{ $config->system_prompt }}</textarea>
                    <div class="form-hint">
                        AI'nın kişiliğini, uzmanlık alanını ve sınırlarını buraya yazın.
                        Maksimum 8.000 karakter.
                        <span id="char-count" class="ms-2 text-secondary">0 / 8000</span>
                    </div>
                    <div class="invalid-feedback" data-field="system_prompt"></div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body d-flex justify-content-end gap-2">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>Kaydet
                    </button>
                </div>
            </div>

        </form>

    </div>

    {{-- Sağ: İpuçları --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-bulb icon me-1 text-primary"></i>Prompt İpuçları</h3>
            </div>
            <div class="card-body">
                <p class="small text-secondary mb-3">İyi bir system prompt şunları içerir:</p>
                <ul class="small text-secondary mb-3 ps-3">
                    <li class="mb-1">AI'nın rolü ve adı</li>
                    <li class="mb-1">Uzmanlık alanları</li>
                    <li class="mb-1">Yanıt vermeyeceği konular</li>
                    <li class="mb-1">Dil ve ton (Türkçe, resmi/samimi)</li>
                    <li class="mb-1">Platform bağlamı (KentApp nedir)</li>
                </ul>
                <p class="small text-secondary mb-0">
                    Kullanıcı mesajları bu prompt'un ardından AI'ya iletilir.
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-clipboard icon me-1 text-primary"></i>Örnek Prompt</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="load-example-btn">
                    Örnek prompt'u yükle
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const examplePrompt = `Sen KentApp'ın AI asistanısın. KentApp; arsa sahiplerini müteahhitler ve emlak danışmanlarıyla buluşturan bir platformdur.

Görevin:
- Kullanıcılara arsa, parsel, ada, pafta gibi tapu kavramlarını açıklamak
- İmar durumu, TAKS, KAKS, gabari gibi teknik terimleri açıklamak
- Kentsel dönüşüm süreci hakkında bilgi vermek
- KentApp platformunun nasıl kullanılacağını anlatmak (kontör, ilan açma, profil vb.)

Kurallar:
- Sadece Türkçe yanıt ver
- Hukuki tavsiye verme, kullanıcıyı bir hukuk uzmanına yönlendir
- Fiyat tahmini yapma
- Bilmediğin konularda dürüst ol`;

const promptArea = $('[name="system_prompt"]');

function updateCount() {
    const len = promptArea.val().length;
    $('#char-count').text(len + ' / 8000').toggleClass('text-danger', len > 8000);
}

promptArea.on('input', updateCount);
updateCount();

$('#load-example-btn').on('click', function () {
    promptArea.val(examplePrompt);
    updateCount();
});

$('#prompt-form').on('submit', function (e) {
    e.preventDefault();
    const btn = $('#submit-btn');
    btn.prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>Kaydediliyor…');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    axios.post('{{ route('admin.ai.prompt.update') }}', new FormData(this))
        .then(res => handleAjaxSuccess(res.data.message))
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
