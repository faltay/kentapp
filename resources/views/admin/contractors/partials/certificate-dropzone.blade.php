@php
    $existingUrl  = $existingUrl ?? null;
    $existingName = $existingName ?? null;
    $isPdf        = $existingName ? str_ends_with(strtolower($existingName), '.pdf') : false;
@endphp

<div id="cert-dropzone" class="cert-dropzone {{ $existingUrl ? 'has-file' : '' }}">
    <input type="file" name="certificate_file" id="cert-input"
           accept="application/pdf,image/jpeg,image/png" class="d-none">
    <input type="hidden" name="remove_certificate" id="cert-remove-flag" value="0">

    {{-- Preview --}}
    <div id="cert-preview" class="{{ $existingUrl ? '' : 'd-none' }}">
        <div class="d-flex align-items-center gap-3 p-3">
            <div id="cert-icon-wrap">
                @if($existingUrl && !$isPdf)
                    <img id="cert-img" src="{{ $existingUrl }}" class="rounded"
                         style="width:56px;height:56px;object-fit:cover;" alt="">
                @else
                    <span class="avatar bg-red-lt" id="cert-pdf-icon">
                        <i class="ti ti-file-type-pdf icon text-red" style="font-size:1.4rem"></i>
                    </span>
                @endif
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div id="cert-filename" class="fw-medium text-truncate">
                    {{ $existingName ?? '' }}
                </div>
                <div class="text-secondary small">
                    @if($existingUrl)
                        <a href="{{ $existingUrl }}" target="_blank" class="text-secondary">
                            {{ __('common.view') }} <i class="ti ti-external-link icon" style="font-size:.75rem"></i>
                        </a>
                    @endif
                </div>
            </div>
            <button type="button" id="cert-remove" class="btn btn-sm btn-ghost-danger btn-icon">
                <i class="ti ti-trash icon"></i>
            </button>
        </div>
    </div>

    {{-- Placeholder --}}
    <div id="cert-placeholder" class="{{ $existingUrl ? 'd-none' : '' }}" style="cursor:pointer">
        <div class="text-center text-secondary py-4">
            <i class="ti ti-cloud-upload" style="font-size:2rem;opacity:.5"></i>
            <div class="mt-1 small">{{ __('admin.contractors.form.certificate_drop') }}</div>
            <div class="text-muted" style="font-size:.75rem">{{ __('admin.contractors.form.certificate_hint') }}</div>
        </div>
    </div>
</div>
<div class="invalid-feedback" data-field="certificate_file"></div>

<style>
.cert-dropzone {
    border: 2px dashed var(--tblr-border-color);
    border-radius: var(--tblr-border-radius);
    transition: border-color .15s, background-color .15s;
}
.cert-dropzone:hover,
.cert-dropzone.dragover {
    border-color: var(--tblr-primary);
    background-color: rgba(var(--tblr-primary-rgb), .03);
}
.cert-dropzone.has-file {
    border-style: solid;
}
</style>

<script>
(function () {
    var input       = document.getElementById('cert-input');
    var area        = document.getElementById('cert-dropzone');
    var preview     = document.getElementById('cert-preview');
    var placeholder = document.getElementById('cert-placeholder');
    var filename    = document.getElementById('cert-filename');
    var removeBtn   = document.getElementById('cert-remove');
    var removeFlag  = document.getElementById('cert-remove-flag');
    var imgEl       = document.getElementById('cert-img');
    var pdfIcon     = document.getElementById('cert-pdf-icon');
    var iconWrap    = document.getElementById('cert-icon-wrap');

    function showFile(file) {
        filename.textContent = file.name;
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if (!imgEl) {
                    imgEl = document.createElement('img');
                    imgEl.id = 'cert-img';
                    imgEl.className = 'rounded';
                    imgEl.style = 'width:56px;height:56px;object-fit:cover;';
                    iconWrap.innerHTML = '';
                    iconWrap.appendChild(imgEl);
                }
                imgEl.src = e.target.result;
                if (pdfIcon) pdfIcon.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            if (imgEl) imgEl.classList.add('d-none');
            if (!pdfIcon) {
                pdfIcon = document.createElement('span');
                pdfIcon.id = 'cert-pdf-icon';
                pdfIcon.className = 'avatar bg-red-lt';
                pdfIcon.innerHTML = '<i class="ti ti-file-type-pdf icon text-red" style="font-size:1.4rem"></i>';
                iconWrap.innerHTML = '';
                iconWrap.appendChild(pdfIcon);
            } else {
                pdfIcon.classList.remove('d-none');
            }
        }
        preview.classList.remove('d-none');
        placeholder.classList.add('d-none');
        area.classList.add('has-file');
        removeFlag.value = '0';
    }

    function reset() {
        input.value = '';
        preview.classList.add('d-none');
        placeholder.classList.remove('d-none');
        area.classList.remove('has-file');
        removeFlag.value = '1';
    }

    placeholder.addEventListener('click', function () { input.click(); });
    input.addEventListener('change', function () {
        if (this.files && this.files[0]) showFile(this.files[0]);
    });
    removeBtn.addEventListener('click', function (e) { e.stopPropagation(); reset(); });

    ['dragenter', 'dragover'].forEach(function (evt) {
        area.addEventListener(evt, function (e) { e.preventDefault(); area.classList.add('dragover'); });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
        area.addEventListener(evt, function (e) { e.preventDefault(); area.classList.remove('dragover'); });
    });
    area.addEventListener('drop', function (e) {
        var file = e.dataTransfer.files[0];
        if (file) { input.files = e.dataTransfer.files; showFile(file); }
    });
})();
</script>
