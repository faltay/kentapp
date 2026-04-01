@php $existingPhotos = $existingPhotos ?? collect(); @endphp


{{-- Mevcut fotoğraflar (edit modunda) --}}
@if($existingPhotos->isNotEmpty())
<div class="mb-3" id="existing-photos-list">
    <div class="d-flex flex-wrap gap-2">
        @foreach($existingPhotos as $media)
        <div class="position-relative">
            <a href="{{ $media->getUrl() }}" class="glightbox" data-gallery="listing-photos"
               data-description="{{ $media->file_name }}">
                <img src="{{ $media->getUrl() }}" class="rounded"
                     style="width:72px;height:72px;object-fit:cover;cursor:zoom-in;" alt="{{ $media->file_name }}">
            </a>
            <label class="position-absolute top-0 end-0"
                   style="background:rgba(255,255,255,.85);border-radius:0 var(--tblr-border-radius-sm) 0 var(--tblr-border-radius-sm);padding:2px 4px;z-index:1;">
                <input type="checkbox" name="remove_photos[]" value="{{ $media->id }}"
                       class="form-check-input mt-0" style="width:.7rem;height:.7rem;">
                <i class="ti ti-trash icon text-danger" style="font-size:.65rem"></i>
            </label>
        </div>
        @endforeach
    </div>
    <div class="form-hint mt-1">{{ __('admin.listings.form.photos_existing_hint') }}</div>
</div>
@endif

{{-- Dropzone --}}
<div id="photos-dropzone" class="listing-dropzone">
    <input type="file" name="photos[]" id="photos-input"
           accept=".jpg,.jpeg,.png,.webp" multiple class="d-none">

    <div id="photos-placeholder" class="dropzone-placeholder text-center text-secondary py-4"
         style="cursor:pointer">
        <i class="ti ti-photo-up" style="font-size:2rem;opacity:.5"></i>
        <div class="mt-1 small">{{ __('admin.listings.form.photos_drop') }}</div>
        <div class="text-muted" style="font-size:.75rem">{{ __('admin.listings.form.photos_hint') }}</div>
    </div>

    <div id="photos-filelist" class="d-none p-2">
        <div id="photos-grid" class="d-flex flex-wrap gap-2"></div>
        <button type="button" id="photos-add-more"
                class="btn btn-sm btn-ghost-secondary mt-2">
            <i class="ti ti-plus icon me-1"></i>{{ __('admin.listings.form.add_more') }}
        </button>
    </div>
</div>
<div class="invalid-feedback" data-field="photos"></div>

<script>
(function () {
    var input       = document.getElementById('photos-input');
    var area        = document.getElementById('photos-dropzone');
    var placeholder = document.getElementById('photos-placeholder');
    var filelist    = document.getElementById('photos-filelist');
    var grid        = document.getElementById('photos-grid');
    var addMoreBtn  = document.getElementById('photos-add-more');

    var dt = new DataTransfer();

    function render() {
        grid.innerHTML = '';
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
            var wrap = document.createElement('div');
            wrap.className = 'position-relative flex-shrink-0';
            wrap.style.cssText = 'width:72px;height:72px;';

            var img = document.createElement('img');
            img.className = 'rounded';
            img.style.cssText = 'width:72px;height:72px;object-fit:cover;';
            img.alt = file.name;

            var reader = new FileReader();
            reader.onload = function (e) { img.src = e.target.result; };
            reader.readAsDataURL(file);

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-icon position-absolute top-0 end-0 p-0';
            removeBtn.style.cssText = 'width:18px;height:18px;background:rgba(255,255,255,.9);border-radius:0 var(--tblr-border-radius-sm) 0 var(--tblr-border-radius-sm);';
            removeBtn.innerHTML = '<i class="ti ti-x" style="font-size:.6rem;color:#d63939"></i>';
            removeBtn.addEventListener('click', function () {
                var newDt = new DataTransfer();
                Array.from(dt.files).forEach(function (f, i) {
                    if (i !== index) newDt.items.add(f);
                });
                dt = newDt;
                input.files = dt.files;
                render();
            });

            wrap.appendChild(img);
            wrap.appendChild(removeBtn);
            grid.appendChild(wrap);
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
