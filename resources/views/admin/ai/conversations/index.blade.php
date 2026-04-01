@extends('layouts.admin')

@section('title', 'AI Konuşmaları')

@push('styles')
<style>
:root {
    --chat-border:      #e6e7e9;
    --chat-bg:          #ffffff;
    --chat-text-muted:  #667382;
    --chat-active-bg:   rgba(32, 107, 196, .08);
    --chat-blue-lt:     rgba(32, 107, 196, .12);
    --chat-orange-lt:   rgba(247, 103,   7, .10);
    --chat-green-lt:    rgba(47,  179, 108, .12);
}

.chat-layout {
    display: flex;
    height: calc(100vh - 130px);
    min-height: 500px;
    border: 1px solid var(--chat-border);
    border-radius: 8px;
    overflow: hidden;
    background: var(--chat-bg);
}

/* ── Sol panel ── */
.chat-sidebar {
    width: 310px;
    flex-shrink: 0;
    border-right: 1px solid var(--chat-border);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.chat-sidebar-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--chat-border);
    display: flex;
    flex-direction: column;
    gap: .5rem;
}
.chat-sidebar-list {
    flex: 1;
    overflow-y: auto;
}
.chat-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid var(--chat-border);
    transition: background .12s;
}
.chat-item:hover  { background: rgba(0,0,0,.04); }
.chat-item.active { background: var(--chat-active-bg); }
.chat-item-avatar  { flex-shrink: 0; }
.chat-item-body    { flex: 1; min-width: 0; }
.chat-item-name    { font-weight: 600; font-size: .8125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-item-email   { font-size: .75rem; color: var(--chat-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-item-preview { font-size: .75rem; color: var(--chat-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
.chat-item-meta    { flex-shrink: 0; text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 3px; }
.chat-item-time    { font-size: .7rem; color: var(--chat-text-muted); }

/* ── Sağ panel ── */
.chat-detail {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0;
}
.chat-detail-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--chat-border);
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-shrink: 0;
}
.chat-detail-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: .75rem;
    background: #f8f9fa;
}
.chat-detail-reply {
    padding: .75rem 1rem;
    border-top: 1px solid var(--chat-border);
    flex-shrink: 0;
    background: var(--chat-bg);
}

/* ── Mesaj balonları ── */
.msg-row          { display: flex; align-items: flex-start; gap: .5rem; }
.msg-row-right    { flex-direction: row-reverse; }
.msg-bubble       { padding: .625rem .875rem; border-radius: 12px; font-size: .875rem; line-height: 1.55; word-break: break-word; }
.msg-bubble-user  { background: var(--chat-blue-lt);   border-top-left-radius:  3px; }
.msg-bubble-ai    { background: var(--chat-orange-lt); border-top-right-radius: 3px; }
.msg-bubble-admin { background: var(--chat-green-lt);  border-top-right-radius: 3px; }
.msg-meta         { font-size: .7rem; color: var(--chat-text-muted); margin-bottom: 3px; font-weight: 600; }

/* Markdown içeriği */
.msg-bubble p            { margin: 0 0 .4rem; }
.msg-bubble p:last-child { margin-bottom: 0; }
.msg-bubble h1, .msg-bubble h2, .msg-bubble h3 { font-size: .9rem; font-weight: 700; margin: .5rem 0 .25rem; }
.msg-bubble ul, .msg-bubble ol { margin: .25rem 0 .25rem 1.1rem; padding: 0; }
.msg-bubble li     { margin-bottom: .15rem; }
.msg-bubble strong { font-weight: 600; }
.msg-bubble code   { background: rgba(0,0,0,.08); padding: .1rem .3rem; border-radius: 3px; font-size: .8rem; }

/* ── Boş durum ── */
.chat-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--chat-text-muted);
    gap: .5rem;
    background: #f8f9fa;
}

.chat-sidebar-loading,
.chat-detail-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: var(--chat-text-muted);
}
</style>
@endpush

@section('content')
<div class="page-header mb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">AI Konuşmaları</h2>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <a href="{{ route('admin.ai.settings') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-settings icon me-1"></i>AI Ayarları
            </a>
            <a href="{{ route('admin.ai.prompt') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-prompt icon me-1"></i>Prompt
            </a>
        </div>
    </div>
</div>

<div class="chat-layout">

    {{-- ── Sol: Konuşma Listesi ── --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-semibold" style="font-size:.875rem">Konuşmalar</span>
                <span id="total-unread" class="badge bg-red d-none"></span>
            </div>
            <select id="filter-status" class="form-select form-select-sm">
                <option value="">Tümü</option>
                <option value="open">Açık</option>
                <option value="closed">Kapalı</option>
            </select>
        </div>
        <div class="chat-sidebar-list" id="conversations-list">
            <div class="chat-sidebar-loading">
                <div class="spinner-border spinner-border-sm text-secondary me-2"></div> Yükleniyor…
            </div>
        </div>
    </div>

    {{-- ── Sağ: Konuşma Detayı ── --}}
    <div class="chat-detail">

        {{-- Boş durum --}}
        <div class="chat-empty" id="chat-empty">
            <i class="ti ti-messages" style="font-size:2.5rem;opacity:.25"></i>
            <span style="font-size:.875rem">Bir konuşma seçin</span>
        </div>

        {{-- Detay (gizli başlar) --}}
        <div id="chat-content" style="display:none;flex:1;flex-direction:column;overflow:hidden;">

            {{-- Başlık --}}
            <div class="chat-detail-header">
                <span id="detail-avatar" class="avatar avatar-sm"></span>
                <div style="flex:1;min-width:0">
                    <div id="detail-name" class="fw-semibold" style="font-size:.875rem"></div>
                    <div id="detail-email" class="text-secondary" style="font-size:.75rem"></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="detail-status-badge"></span>
                    <span id="detail-ai-badge"></span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical icon"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <button class="dropdown-item" id="menu-toggle-ai">
                                <i class="ti ti-robot icon me-1"></i><span id="menu-toggle-ai-label"></span>
                            </button>
                            <button class="dropdown-item" id="menu-toggle-status">
                                <i class="ti ti-lock icon me-1" id="menu-status-icon"></i><span id="menu-status-label"></span>
                            </button>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id="menu-user-link" href="#" target="_blank">
                                <i class="ti ti-external-link icon me-1"></i>Kullanıcı Profili
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mesajlar --}}
            <div class="chat-detail-messages" id="messages-container">
                <div class="chat-detail-loading" id="messages-loading">
                    <div class="spinner-border spinner-border-sm text-secondary me-2"></div> Yükleniyor…
                </div>
            </div>

            {{-- Yanıt alanı --}}
            <div class="chat-detail-reply" id="reply-area">
                <div class="d-flex gap-2 align-items-end">
                    <textarea id="reply-input" class="form-control form-control-sm"
                              rows="2" placeholder="Yanıt yaz…" maxlength="2000"
                              style="resize:none"></textarea>
                    <button id="reply-btn" class="btn btn-primary btn-sm" style="height:38px;white-space:nowrap">
                        <i class="ti ti-send icon me-1"></i>Gönder
                    </button>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="text-secondary" style="font-size:.7rem"><span id="reply-count">0</span>/2000</span>
                    <span id="closed-notice" class="text-secondary" style="font-size:.7rem;display:none">
                        <i class="ti ti-lock icon"></i> Konuşma kapalı — yanıt gönderilemez
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
marked.setOptions({ breaks: true, gfm: true });
</script>
<script>
const ROUTES = {
    list:   '{{ route('admin.ai.conversations.list') }}',
    detail: (id) => `{{ url('admin/ai/conversations') }}/${id}/detail`,
    reply:  (id) => `{{ url('admin/ai/conversations') }}/${id}/reply`,
    toggleAi: (id) => `{{ url('admin/ai/conversations') }}/${id}/toggle-ai`,
    close:  (id) => `{{ url('admin/ai/conversations') }}/${id}/close`,
    reopen: (id) => `{{ url('admin/ai/conversations') }}/${id}/reopen`,
};

let activeId   = null;
let activeData = null;

/* ── Yardımcılar ──────────────────────────────────────── */
function avatarUrl(name) {
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'U')}&background=066fd1&color=fff&size=64`;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function roleLabel(role) {
    if (role === 'user')      return activeData?.user?.name ?? 'Kullanıcı';
    if (role === 'assistant') return 'KentApp';
    return 'Admin';
}

function renderContent(role, content) {
    if (role === 'user') {
        return escHtml(content).replace(/\n/g, '<br>');
    }
    // AI ve admin mesajlarında markdown render et
    return marked.parse(content);
}

/* ── Sol panel ─────────────────────────────────────────── */
function loadList() {
    const status = $('#filter-status').val();
    $.get(ROUTES.list, { status })
     .done(function (res) {
         const items = res.data;

         const totalUnread = items.reduce((s, c) => s + c.unread_count, 0);
         if (totalUnread > 0) {
             $('#total-unread').text(totalUnread).removeClass('d-none');
         } else {
             $('#total-unread').addClass('d-none');
         }

         if (!items.length) {
             $('#conversations-list').html('<div class="p-3 text-secondary text-center" style="font-size:.8125rem">Konuşma yok</div>');
             return;
         }

         let html = '';
         items.forEach(c => {
             const active = c.id === activeId ? 'active' : '';
             html += `
             <div class="chat-item ${active}" data-id="${c.id}">
                 <div class="chat-item-avatar">
                     <span class="avatar avatar-sm" style="background-image:url('${escHtml(c.user_avatar)}')"></span>
                 </div>
                 <div class="chat-item-body">
                     <div class="chat-item-name">${escHtml(c.user_name)}</div>
                     <div class="chat-item-email">${escHtml(c.user_email)}</div>
                     <div class="chat-item-preview">${escHtml(c.last_message)}</div>
                 </div>
                 <div class="chat-item-meta">
                     <span class="chat-item-time">${escHtml(c.date_label)}</span>
                     ${c.unread_count > 0 ? `<span class="badge bg-red">${c.unread_count}</span>` : ''}
                     ${c.status === 'closed' ? '<span class="badge bg-secondary-lt" style="font-size:.6rem">Kapalı</span>' : ''}
                 </div>
             </div>`;
         });

         $('#conversations-list').html(html);

         // Tıklama
         $('#conversations-list .chat-item').on('click', function () {
             loadDetail(parseInt($(this).data('id')));
         });
     });
}

/* ── Sağ panel ─────────────────────────────────────────── */
function loadDetail(id) {
    activeId = id;

    // Aktif stil
    $('.chat-item').removeClass('active');
    $(`.chat-item[data-id="${id}"]`).addClass('active');

    // İskelet göster
    $('#chat-empty').hide();
    $('#chat-content').css('display', 'flex');
    $('#messages-container').html(`
        <div class="chat-detail-loading">
            <div class="spinner-border spinner-border-sm text-secondary me-2"></div> Yükleniyor…
        </div>`);

    $.get(ROUTES.detail(id))
     .done(function (res) {
         activeData = res.data;
         renderDetail(activeData);
     })
     .fail(function () {
         $('#messages-container').html('<div class="p-3 text-danger text-center">Yüklenemedi.</div>');
     });
}

function renderDetail(d) {
    const user = d.user;

    // Başlık
    $('#detail-avatar').css('background-image', `url('${avatarUrl(user?.name)}')`);
    $('#detail-name').text(user?.name ?? '—');
    $('#detail-email').text(user?.email ?? '—');
    $('#detail-status-badge').html(d.status === 'open'
        ? '<span class="badge bg-green-lt">Açık</span>'
        : '<span class="badge bg-secondary-lt">Kapalı</span>');
    $('#detail-ai-badge').html(d.ai_enabled
        ? '<span class="badge bg-blue-lt">AI</span>'
        : '<span class="badge bg-yellow-lt">Manuel</span>');

    // Menü
    updateMenuLabels(d.ai_enabled, d.status);
    if (user?.url) {
        $('#menu-user-link').attr('href', user.url).show();
    } else {
        $('#menu-user-link').hide();
    }

    // Mesajlar
    renderMessages(d.messages);

    // Yanıt alanı
    const isOpen = d.status === 'open';
    $('#reply-input, #reply-btn').prop('disabled', !isOpen);
    $('#closed-notice').toggle(!isOpen);

    // Sol listede unread sıfırla
    $(`.chat-item[data-id="${d.id}"] .badge.bg-red`).remove();
}

function renderMessages(messages) {
    if (!messages.length) {
        $('#messages-container').html('<div class="p-3 text-secondary text-center" style="font-size:.8125rem">Henüz mesaj yok.</div>');
        return;
    }

    let html = '';
    messages.forEach(m => {
        const isUser  = m.role === 'user';
        const isAi    = m.role === 'assistant';
        const rowCls  = isUser ? '' : 'msg-row-right';
        const bubCls  = isUser ? 'msg-bubble-user' : (isAi ? 'msg-bubble-ai' : 'msg-bubble-admin');
        const metaCls = isUser ? '' : 'text-end';

        html += `
        <div class="msg-row ${rowCls}">
            ${isUser ? `<span class="avatar avatar-xs flex-shrink-0 mt-1" style="background-image:url('${avatarUrl(activeData?.user?.name)}')"></span>` : ''}
            <div style="max-width:68%">
                <div class="msg-meta ${metaCls}">${escHtml(roleLabel(m.role))} · ${escHtml(m.created_at)}</div>
                <div class="msg-bubble ${bubCls}">${renderContent(m.role, m.content)}</div>
            </div>
        </div>`;
    });

    $('#messages-container').html(html);
    const el = document.getElementById('messages-container');
    el.scrollTop = el.scrollHeight;
}

function appendMessage(m) {
    const isUser  = m.role === 'user';
    const isAi    = m.role === 'assistant';
    const rowCls  = isUser ? '' : 'msg-row-right';
    const bubCls  = isUser ? 'msg-bubble-user' : (isAi ? 'msg-bubble-ai' : 'msg-bubble-admin');
    const metaCls = isUser ? '' : 'text-end';

    const html = `
    <div class="msg-row ${rowCls}">
        ${isUser ? `<span class="avatar avatar-xs flex-shrink-0 mt-1" style="background-image:url('${avatarUrl(activeData?.user?.name)}')"></span>` : ''}
        <div style="max-width:68%">
            <div class="msg-meta ${metaCls}">${escHtml(roleLabel(m.role))} · ${escHtml(m.created_at)}</div>
            <div class="msg-bubble ${bubCls}">${renderContent(m.role, m.content)}</div>
        </div>
    </div>`;

    $('#messages-container').append(html);
    const el = document.getElementById('messages-container');
    el.scrollTop = el.scrollHeight;
}

/* ── Menü etiketleri ───────────────────────────────────── */
function updateMenuLabels(aiEnabled, status) {
    $('#menu-toggle-ai-label').text(aiEnabled ? "AI'yı Kapat" : "AI'yı Aç");
    if (status === 'open') {
        $('#menu-status-icon').attr('class', 'ti ti-lock icon me-1');
        $('#menu-status-label').text('Konuşmayı Kapat');
    } else {
        $('#menu-status-icon').attr('class', 'ti ti-lock-open icon me-1');
        $('#menu-status-label').text('Yeniden Aç');
    }
}

/* ── Olaylar ───────────────────────────────────────────── */
$('#filter-status').on('change', loadList);

$('#reply-input').on('input', function () {
    $('#reply-count').text(this.value.length);
});

$('#reply-input').on('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        $('#reply-btn').trigger('click');
    }
});

$('#reply-btn').on('click', function () {
    const content = $('#reply-input').val().trim();
    if (!content || !activeId) return;

    const btn = $(this).prop('disabled', true).html('<i class="ti ti-loader-2 icon me-1"></i>Gönderiliyor…');

    axios.post(ROUTES.reply(activeId), { content })
        .then(function (res) {
            appendMessage(res.data.data.message);
            $('#reply-input').val('');
            $('#reply-count').text('0');
            handleAjaxSuccess(res.data.message);
        })
        .catch(err => handleAjaxError(err))
        .finally(() => btn.prop('disabled', false).html('<i class="ti ti-send icon me-1"></i>Gönder'));
});

$('#menu-toggle-ai').on('click', function () {
    if (!activeId) return;
    axios.post(ROUTES.toggleAi(activeId))
        .then(function (res) {
            activeData.ai_enabled = res.data.data.ai_enabled;
            updateMenuLabels(activeData.ai_enabled, activeData.status);
            $('#detail-ai-badge').html(activeData.ai_enabled
                ? '<span class="badge bg-blue-lt">AI</span>'
                : '<span class="badge bg-yellow-lt">Manuel</span>');
            handleAjaxSuccess(res.data.message);
        })
        .catch(err => handleAjaxError(err));
});

$('#menu-toggle-status').on('click', function () {
    if (!activeId) return;
    const isOpen = activeData.status === 'open';
    const url = isOpen ? ROUTES.close(activeId) : ROUTES.reopen(activeId);

    axios.post(url)
        .then(function (res) {
            activeData.status = res.data.data.status;
            updateMenuLabels(activeData.ai_enabled, activeData.status);
            const isNowOpen = activeData.status === 'open';
            $('#detail-status-badge').html(isNowOpen
                ? '<span class="badge bg-green-lt">Açık</span>'
                : '<span class="badge bg-secondary-lt">Kapalı</span>');
            $('#reply-input, #reply-btn').prop('disabled', !isNowOpen);
            $('#closed-notice').toggle(!isNowOpen);
            handleAjaxSuccess(res.data.message);
            loadList();
        })
        .catch(err => handleAjaxError(err));
});

/* ── İlk yükleme ───────────────────────────────────────── */
loadList();

// Her 30 saniyede sol paneli yenile (yeni mesaj kontrolü)
setInterval(loadList, 30000);
</script>
@endpush
