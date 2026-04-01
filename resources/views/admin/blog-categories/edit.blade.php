@extends('layouts.admin')

@section('title', __('admin.blog_categories.edit'))

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.blog_categories.edit') }}: {{ $category->localized_name }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="edit-blog-category-form" data-action="{{ route('admin.blog-categories.update', $category) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="lang" value="{{ $editLang }}">
    <div class="row">

        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-category icon me-1 text-primary"></i>
                        {{ __('admin.blog_categories.form.info_section') }}
                    </h3>
                    <div class="card-actions">
                        @foreach($activeLanguages as $lang)
                            @php $hasContent = $category->hasTranslation($lang->code); @endphp
                            <a href="{{ route('admin.blog-categories.edit', $category) }}?lang={{ $lang->code }}"
                               class="badge {{ $lang->code === $editLang ? 'bg-primary text-white' : ($hasContent ? 'bg-green-lt' : 'bg-secondary-lt') }} text-decoration-none">
                                {{ strtoupper($lang->code) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.blog_categories.form.name') }}</label>
                        <input type="text" name="name[{{ $editLang }}]" class="form-control"
                               value="{{ $category->name[$editLang] ?? '' }}" required autofocus>
                        <div class="invalid-feedback" data-field="name.{{ $editLang }}"></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">{{ __('admin.blog_categories.form.description') }}</label>
                        <textarea name="description[{{ $editLang }}]" class="form-control" rows="3">{{ $category->description[$editLang] ?? '' }}</textarea>
                        <div class="invalid-feedback" data-field="description.{{ $editLang }}"></div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <button type="submit" id="submit-btn" class="btn btn-primary">
                        <i class="ti ti-check icon me-1"></i>
                        {{ __('common.save') }}
                    </button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x icon me-1"></i>
                        {{ __('common.cancel') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- SEO & URL --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-link icon me-1 text-primary"></i>
                        {{ __('admin.blog_categories.form.slug_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">
                            {{ __('admin.blog_categories.form.slug') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <input type="text" name="slug[{{ $editLang }}]" class="form-control"
                               value="{{ $category->slug[$editLang] ?? '' }}"
                               placeholder="auto-generated-from-name">
                        <div class="invalid-feedback" data-field="slug.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.blog_categories.form.slug_hint') }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">
                            {{ __('admin.blog_categories.form.meta_description') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <textarea name="meta_description[{{ $editLang }}]" class="form-control" rows="2" maxlength="160">{{ $category->meta_description[$editLang] ?? '' }}</textarea>
                        <div class="invalid-feedback" data-field="meta_description.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.blog_categories.form.meta_description_hint') }}</div>
                    </div>
                </div>
            </div>

            {{-- Sort Order --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-arrows-sort icon me-1 text-primary"></i>
                        {{ __('admin.blog_categories.form.sort_order') }}
                    </h3>
                </div>
                <div class="card-body">
                    <input type="number" name="sort_order" class="form-control" value="{{ $category->sort_order }}" min="0">
                    <div class="invalid-feedback" data-field="sort_order"></div>
                </div>
            </div>

            {{-- Status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-settings icon me-1 text-primary"></i>
                        {{ __('common.status') }}
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input"
                               @checked($category->is_active)>
                        <span class="form-check-label">{{ __('admin.blog_categories.form.is_active') }}</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const form = document.getElementById('edit-blog-category-form');
const submitBtn = document.getElementById('submit-btn');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    submitBtn.disabled = true;

    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    axios.post(form.dataset.action, formData)
        .then(res => {
            handleAjaxSuccess(res.data.message);
            if (res.data.data?.redirect_url) {
                window.location.href = res.data.data.redirect_url;
            }
        })
        .catch(err => {
            handleAjaxError(err);
            if (err.response?.status === 422 && err.response.data.errors) {
                Object.entries(err.response.data.errors).forEach(([field, messages]) => {
                    const feedback = form.querySelector(`[data-field="${field}"]`);
                    if (feedback) {
                        const input = feedback.previousElementSibling?.matches('input,textarea,select')
                            ? feedback.previousElementSibling
                            : feedback.closest('.mb-3,.mb-0')?.querySelector('input,textarea,select');
                        if (input) input.classList.add('is-invalid');
                        feedback.textContent = messages[0];
                    }
                });
            }
        })
        .finally(() => submitBtn.disabled = false);
});
</script>
@endpush
