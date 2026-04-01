@php $existingDocs = $existingDocs ?? collect(); @endphp

{{-- Mevcut belgeler (edit modunda) --}}
@if($existingDocs->isNotEmpty())
<div class="mb-3" id="existing-docs-list">
    @foreach($existingDocs as $media)
    <div class="d-flex align-items-center gap-2 mb-2 border rounded p-2">
        @if(str_ends_with(strtolower($media->file_name), '.pdf'))
            <span class="avatar avatar-sm bg-red-lt flex-shrink-0">
                <i class="ti ti-file-type-pdf icon text-red"></i>
            </span>
        @else
            <a href="{{ $media->getUrl() }}" class="glightbox flex-shrink-0"
               data-gallery="listing-docs" data-description="{{ $media->file_name }}">
                <img src="{{ $media->getUrl() }}" class="rounded"
                     style="width:32px;height:32px;object-fit:cover;cursor:zoom-in;" alt="{{ $media->file_name }}">
            </a>
        @endif
        <span class="text-truncate flex-fill small">{{ $media->file_name }}</span>
        <a href="{{ $media->getUrl() }}" target="_blank"
           class="btn btn-sm btn-ghost-secondary btn-icon">
            <i class="ti ti-eye icon"></i>
        </a>
        <label class="form-check mb-0 d-flex align-items-center gap-1">
            <input type="checkbox" name="remove_documents[]" value="{{ $media->id }}"
                   class="form-check-input mt-0">
            <span class="text-danger small">{{ __('common.delete') }}</span>
        </label>
    </div>
    @endforeach
</div>
@endif

{{-- Dropzone --}}
<div id="docs-dropzone" class="listing-dropzone">
    <input type="file" name="documents[]" id="docs-input"
           accept=".pdf,.jpg,.jpeg,.png" multiple class="d-none">

    <div id="docs-placeholder" class="dropzone-placeholder text-center text-secondary py-4"
         style="cursor:pointer">
        <i class="ti ti-cloud-upload" style="font-size:2rem;opacity:.5"></i>
        <div class="mt-1 small">{{ __('admin.listings.form.documents_drop') }}</div>
        <div class="text-muted" style="font-size:.75rem">{{ __('admin.listings.form.documents_hint') }}</div>
    </div>

    <div id="docs-filelist" class="d-none p-2">
        <div id="docs-files"></div>
        <button type="button" id="docs-add-more"
                class="btn btn-sm btn-ghost-secondary mt-2">
            <i class="ti ti-plus icon me-1"></i>{{ __('admin.listings.form.add_more') }}
        </button>
    </div>
</div>
<div class="invalid-feedback" data-field="documents"></div>

<style>
.listing-dropzone {
    border: 2px dashed var(--tblr-border-color);
    border-radius: var(--tblr-border-radius);
    transition: border-color .15s, background-color .15s;
}
.listing-dropzone:hover,
.listing-dropzone.dragover {
    border-color: var(--tblr-primary);
    background-color: rgba(var(--tblr-primary-rgb), .03);
}
.listing-dropzone.has-files {
    border-style: solid;
}
.docs-file-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .4rem .5rem;
    border-radius: var(--tblr-border-radius-sm);
    background: var(--tblr-bg-surface-secondary);
    margin-bottom: .25rem;
}
</style>

<script>
(function () {
    var input       = document.getElementById('docs-input');
    var area        = document.getElementById('docs-dropzone');
    var placeholder = document.getElementById('docs-placeholder');
    var filelist    = document.getElementById('docs-filelist');
    var filesWrap   = document.getElementById('docs-files');
    var addMoreBtn  = document.getElementById('docs-add-more');

    var dt = new DataTransfer();

    function render() {
        filesWrap.innerHTML = '';
        if (dt.files.length === 0) {
            filelist.classList.add('d-none');
            placeholder.classList.remove('d-none');
            area.classList.remove('has-files');
            return;
        }
        filelist.classList.remove('d-none');
        placeholder.classList.add('d-none');
        area.classList.add('has-files');

        Array.from(dt.files).forEach(function (file, index) {
            var isPdf = file.type === 'application/pdf' || file.name.endsWith('.pdf');
            var item = document.createElement('div');
            item.className = 'docs-file-item';

            var icon = document.createElement('span');
            if (isPdf) {
                icon.className = 'avatar avatar-sm bg-red-lt flex-shrink-0';
                icon.innerHTML = '<i class="ti ti-file-type-pdf icon text-red" style="font-size:1rem"></i>';
            } else {
                icon.className = 'avatar avatar-sm bg-blue-lt flex-shrink-0';
                icon.innerHTML = '<i class="ti ti-photo icon text-blue" style="font-size:1rem"></i>';
            }

            var name = document.createElement('span');
            name.className = 'text-truncate flex-fill small';
            name.style.maxWidth = '200px';
            name.textContent = file.name;

            var size = document.createElement('span');
            size.className = 'text-secondary small flex-shrink-0';
            size.textContent = (file.size / 1024).toFixed(0) + ' KB';

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-ghost-danger btn-icon ms-auto';
            removeBtn.innerHTML = '<i class="ti ti-x icon"></i>';
            removeBtn.addEventListener('click', function () {
                var newDt = new DataTransfer();
                Array.from(dt.files).forEach(function (f, i) {
                    if (i !== index) newDt.items.add(f);
                });
                dt = newDt;
                input.files = dt.files;
                render();
            });

            item.appendChild(icon);
            item.appendChild(name);
            item.appendChild(size);
            item.appendChild(removeBtn);
            filesWrap.appendChild(item);
        });
    }

    function addFiles(files) {
        Array.from(files).forEach(function (file) { dt.items.add(file); });
        input.files = dt.files;
        render();
    }

    placeholder.addEventListener('click', function () { input.click(); });
    addMoreBtn.addEventListener('click', function () { input.click(); });

    input.addEventListener('change', function () {
        if (this.files.length) addFiles(this.files);
        this.value = '';
    });

    ['dragenter', 'dragover'].forEach(function (evt) {
        area.addEventListener(evt, function (e) {
            e.preventDefault();
            area.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
        area.addEventListener(evt, function (e) {
            e.preventDefault();
            area.classList.remove('dragover');
        });
    });
    area.addEventListener('drop', function (e) {
        if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
    });
})();
</script>
