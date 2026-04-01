{{-- $existingUrl = mevcut görsel URL (edit'te) veya null (create'te) --}}
@php $existingUrl = $existingUrl ?? null; @endphp

<div id="image-dropzone" class="dropzone-area {{ $existingUrl ? 'has-image' : '' }}">
    <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" class="d-none">

    {{-- Preview --}}
    <div id="dropzone-preview" class="{{ $existingUrl ? '' : 'd-none' }}">
        <div class="position-relative d-inline-block">
            <img id="dropzone-img" src="{{ $existingUrl ?? '' }}" class="rounded img-fluid" alt="">
            <button type="button" id="dropzone-remove" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-icon px-2">
                <i class="ti ti-x" style="font-size:14px"></i>
            </button>
        </div>
    </div>

    {{-- Placeholder --}}
    <div id="dropzone-placeholder" class="{{ $existingUrl ? 'd-none' : '' }}" style="cursor:pointer">
        <div class="text-center text-secondary py-3">
            <i class="ti ti-cloud-upload" style="font-size:2rem"></i>
            <div class="mt-1 small">{{ __('admin.posts.form.image_drop') }}</div>
            <div class="text-muted" style="font-size:0.75rem">{{ __('admin.posts.form.image_hint') }}</div>
        </div>
    </div>
</div>
<div class="invalid-feedback" data-field="image"></div>

<style>
    .dropzone-area {
        border: 2px dashed var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        transition: border-color .15s, background-color .15s;
    }
    .dropzone-area:hover,
    .dropzone-area.dragover {
        border-color: var(--tblr-primary);
        background-color: rgba(var(--tblr-primary-rgb), .03);
    }
    .dropzone-area.has-image {
        border-style: solid;
    }
    #dropzone-preview img {
        max-height: 200px;
        display: block;
        margin: 0 auto;
    }
    #dropzone-preview {
        padding: .5rem;
        text-align: center;
    }
</style>

<script>
(function() {
    var input = document.getElementById('image-input');
    var area = document.getElementById('image-dropzone');
    var preview = document.getElementById('dropzone-preview');
    var placeholder = document.getElementById('dropzone-placeholder');
    var img = document.getElementById('dropzone-img');
    var removeBtn = document.getElementById('dropzone-remove');

    function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
            area.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }

    function reset() {
        input.value = '';
        img.src = '';
        preview.classList.add('d-none');
        placeholder.classList.remove('d-none');
        area.classList.remove('has-image');
    }

    // Click to select
    placeholder.addEventListener('click', function() { input.click(); });

    // File selected
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) showPreview(this.files[0]);
    });

    // Remove
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        reset();
    });

    // Drag & drop
    ['dragenter', 'dragover'].forEach(function(evt) {
        area.addEventListener(evt, function(e) {
            e.preventDefault();
            area.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(function(evt) {
        area.addEventListener(evt, function(e) {
            e.preventDefault();
            area.classList.remove('dragover');
        });
    });

    area.addEventListener('drop', function(e) {
        var files = e.dataTransfer.files;
        if (files && files[0] && files[0].type.startsWith('image/')) {
            input.files = files;
            showPreview(files[0]);
        }
    });
})();
</script>
