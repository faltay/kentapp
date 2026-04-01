@extends('layouts.admin')

@section('title', 'Konuşma #' . $conversation->id)

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">AI Konuşmaları</div>
            <h2 class="page-title">{{ $conversation->user?->name ?? 'Kullanıcı #' . $conversation->user_id }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.ai.conversations.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<div class="row">

    {{-- Sol: Mesajlar + Reply --}}
    <div class="col-lg-8">

        {{-- Mesajlar --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-messages icon me-1 text-primary"></i>Mesajlar</h3>
                <div class="card-actions">
                    <span class="text-secondary small">{{ $conversation->messages->count() }} mesaj</span>
                </div>
            </div>
            <div class="card-body" id="messages-container"
                 style="max-height:520px;overflow-y:auto;display:flex;flex-direction:column;gap:.75rem;">

                @forelse($conversation->messages as $msg)
                    @php
                        $isUser  = $msg->role === 'user';
                        $isAdmin = $msg->role === 'admin';
                        $isAi    = $msg->role === 'assistant';
                    @endphp
                    <div class="d-flex {{ $isUser ? '' : 'flex-row-reverse' }} align-items-end gap-2"
                         data-message-id="{{ $msg->id }}">

                        {{-- Avatar --}}
                        @if($isUser)
                            <span class="avatar avatar-sm flex-shrink-0"
                                  style="background-image:url(https://ui-avatars.com/api/?name={{ urlencode($conversation->user?->name ?? 'U') }}&background=066fd1&color=fff&size=64)"></span>
                        @elseif($isAi)
                            <span class="avatar avatar-sm flex-shrink-0 bg-orange text-white flex-shrink-0">
                                <i class="ti ti-robot icon" style="font-size:.9rem"></i>
                            </span>
                        @else
                            <span class="avatar avatar-sm flex-shrink-0 bg-blue text-white">
                                <i class="ti ti-shield icon" style="font-size:.9rem"></i>
                            </span>
                        @endif

                        {{-- Baloncuk --}}
                        <div style="max-width:72%">
                            <div class="d-flex align-items-center gap-1 mb-1 {{ $isUser ? '' : 'flex-row-reverse' }}">
                                <span class="small fw-medium">
                                    @if($isUser) {{ $conversation->user?->name ?? 'Kullanıcı' }}
                                    @elseif($isAi) AI Asistan
                                    @else Admin
                                    @endif
                                </span>
                                <span class="text-secondary" style="font-size:.7rem">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                            <div class="p-2 px-3 rounded-3 {{ $isUser ? 'bg-blue-lt' : ($isAi ? 'bg-orange-lt' : 'bg-green-lt') }}"
                                 style="line-height:1.5;font-size:.875rem;border-radius: {{ $isUser ? '4px 12px 12px 12px' : '12px 4px 12px 12px' }} !important;">
                                {!! nl2br(e($msg->content)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-secondary py-4">
                        <i class="ti ti-message-off icon mb-1" style="font-size:1.5rem;opacity:.4"></i>
                        <p class="mb-0 small">Henüz mesaj yok.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Admin Reply --}}
        @if($conversation->isOpen())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-send icon me-1 text-primary"></i>Manuel Yanıt Gönder</h3>
            </div>
            <div class="card-body">
                <textarea id="reply-input" class="form-control mb-2" rows="3"
                          placeholder="Kullanıcıya mesaj yazın…" maxlength="2000"></textarea>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary small"><span id="reply-count">0</span> / 2000</span>
                    <button type="button" id="reply-btn" class="btn btn-primary">
                        <i class="ti ti-send icon me-1"></i>Gönder
                    </button>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-secondary">
                <i class="ti ti-lock icon me-1"></i>Konuşma kapalı. Yanıt göndermek için konuşmayı yeniden açın.
            </div>
        @endif

    </div>

    {{-- Sağ: Bilgi + Kontroller --}}
    <div class="col-lg-4">

        {{-- Kullanıcı Bilgisi --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-user icon me-1 text-primary"></i>Kullanıcı</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="avatar avatar-md"
                          style="background-image:url(https://ui-avatars.com/api/?name={{ urlencode($conversation->user?->name ?? 'U') }}&background=066fd1&color=fff&size=128)"></span>
                    <div>
                        <div class="fw-bold">{{ $conversation->user?->name ?? '—' }}</div>
                        <div class="text-secondary small">{{ $conversation->user?->email ?? '—' }}</div>
                        <div class="text-secondary small">{{ $conversation->user?->type ?? '—' }}</div>
                    </div>
                </div>
                @if($conversation->user)
                    <a href="{{ route('admin.users.show', $conversation->user) }}"
                       class="btn btn-sm btn-ghost-secondary w-100">
                        <i class="ti ti-external-link icon me-1"></i>Profili Aç
                    </a>
                @endif
            </div>
        </div>

        {{-- Konuşma Kontrolleri --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-adjustments icon me-1 text-primary"></i>Kontroller</h3>
            </div>
            <div class="card-body d-grid gap-2">

                {{-- AI toggle --}}
                <button type="button" id="toggle-ai-btn"
                        class="btn {{ $conversation->ai_enabled ? 'btn-warning' : 'btn-success' }}"
                        data-url="{{ route('admin.ai.conversations.toggle-ai', $conversation) }}">
                    <i class="ti ti-robot icon me-1"></i>
                    <span id="ai-btn-label">
                        {{ $conversation->ai_enabled ? 'AI\'yı Kapat (Manuel Moda Al)' : 'AI\'yı Aç' }}
                    </span>
                </button>

                {{-- Durum --}}
                @if($conversation->isOpen())
                    <button type="button" class="btn btn-outline-secondary" id="close-btn"
                            data-url="{{ route('admin.ai.conversations.close', $conversation) }}">
                        <i class="ti ti-lock icon me-1"></i>Konuşmayı Kapat
                    </button>
                @else
                    <button type="button" class="btn btn-outline-success" id="reopen-btn"
                            data-url="{{ route('admin.ai.conversations.reopen', $conversation) }}">
                        <i class="ti ti-lock-open icon me-1"></i>Yeniden Aç
                    </button>
                @endif

            </div>
        </div>

        {{-- Konuşma Bilgisi --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-info-circle icon me-1 text-primary"></i>Bilgi</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-6 text-secondary">Durum</dt>
                    <dd class="col-6">
                        @if($conversation->isOpen())
                            <span class="badge bg-green-lt">Açık</span>
                        @else
                            <span class="badge bg-secondary-lt">Kapalı</span>
                        @endif
                    </dd>

                    <dt class="col-6 text-secondary">AI Modu</dt>
                    <dd class="col-6" id="ai-status-text">
                        @if($conversation->ai_enabled)
                            <span class="badge bg-blue-lt">Aktif</span>
                        @else
                            <span class="badge bg-yellow-lt">Manuel</span>
                        @endif
                    </dd>

                    <dt class="col-6 text-secondary">Başlangıç</dt>
                    <dd class="col-6">{{ $conversation->created_at->format('d.m.Y H:i') }}</dd>

                    <dt class="col-6 text-secondary">Son Mesaj</dt>
                    <dd class="col-6">{{ $conversation->last_message_at?->format('d.m.Y H:i') ?? '—' }}</dd>
                </dl>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Sayfayı aşağı kaydır
const container = document.getElementById('messages-container');
if (container) container.scrollTop = container.scrollHeight;

// Karakter sayacı
$('#reply-input').on('input', function () {
    $('#reply-count').text(this.value.length);
});

// Mesaj gönder
$('#reply-btn').on('click', function () {
    const content = $('#reply-input').val().trim();
    if (!content) return;

    const btn = $(this).prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>Gönderiliyor…');

    axios.post('{{ route('admin.ai.conversations.reply', $conversation) }}', { content })
        .then(function (res) {
            const msg = res.data.data.message;
            const html = `
                <div class="d-flex flex-row-reverse align-items-end gap-2">
                    <span class="avatar avatar-sm flex-shrink-0 bg-blue text-white">
                        <i class="ti ti-shield icon" style="font-size:.9rem"></i>
                    </span>
                    <div style="max-width:72%">
                        <div class="d-flex align-items-center gap-1 mb-1 flex-row-reverse">
                            <span class="small fw-medium">Admin</span>
                            <span class="text-secondary" style="font-size:.7rem">${msg.created_at}</span>
                        </div>
                        <div class="p-2 px-3 rounded-3 bg-green-lt"
                             style="line-height:1.5;font-size:.875rem;border-radius:12px 4px 12px 12px !important;">
                            ${msg.content.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>`;
            $('#messages-container').append(html);
            container.scrollTop = container.scrollHeight;
            $('#reply-input').val('');
            $('#reply-count').text('0');
            handleAjaxSuccess(res.data.message);
        })
        .catch(err => handleAjaxError(err))
        .finally(() => btn.prop('disabled', false).html('<i class="ti ti-send icon me-1"></i>Gönder'));
});

// AI toggle
$('#toggle-ai-btn').on('click', function () {
    axios.post($(this).data('url'))
        .then(function (res) {
            const enabled = res.data.data.ai_enabled;
            $('#toggle-ai-btn')
                .removeClass('btn-warning btn-success')
                .addClass(enabled ? 'btn-warning' : 'btn-success');
            $('#ai-btn-label').text(enabled ? "AI'yı Kapat (Manuel Moda Al)" : "AI'yı Aç");
            $('#ai-status-text').html(enabled
                ? '<span class="badge bg-blue-lt">Aktif</span>'
                : '<span class="badge bg-yellow-lt">Manuel</span>');
            handleAjaxSuccess(res.data.message);
        })
        .catch(err => handleAjaxError(err));
});

// Konuşmayı kapat
$('#close-btn, #reopen-btn').on('click', function () {
    axios.post($(this).data('url'))
        .then(function (res) {
            handleAjaxSuccess(res.data.message);
            setTimeout(() => window.location.reload(), 1200);
        })
        .catch(err => handleAjaxError(err));
});
</script>
@endpush
