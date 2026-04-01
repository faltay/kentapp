@extends('layouts.admin')

@section('title', __('admin.posts.create'))

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.posts.create') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="create-post-form" data-action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="lang" value="{{ $editLang }}">
    <div class="row">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Post Content --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-article icon me-1 text-primary"></i>
                        {{ __('admin.posts.form.content_section') }}
                    </h3>
                    <div class="card-actions">
                        @foreach($activeLanguages as $lang)
                            <a href="{{ route('admin.posts.create') }}?lang={{ $lang->code }}"
                               class="badge {{ $lang->code === $editLang ? 'bg-primary text-white' : 'bg-secondary-lt' }} text-decoration-none">
                                {{ strtoupper($lang->code) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.posts.form.title') }}</label>
                        <input type="text" name="title[{{ $editLang }}]" class="form-control" required autofocus>
                        <div class="invalid-feedback" data-field="title.{{ $editLang }}"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.posts.form.excerpt') }}</label>
                        <textarea name="excerpt[{{ $editLang }}]" class="form-control" rows="2"></textarea>
                        <div class="form-hint">{{ __('admin.posts.form.excerpt_hint') }}</div>
                        <div class="invalid-feedback" data-field="excerpt.{{ $editLang }}"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.posts.form.content') }}</label>
                        <div id="quill-{{ $editLang }}" style="min-height:300px;"></div>
                        <input type="hidden" name="content[{{ $editLang }}]" id="content-{{ $editLang }}">
                        <div class="invalid-feedback" data-field="content.{{ $editLang }}"></div>
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
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
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
                        {{ __('admin.posts.form.seo_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">
                            {{ __('admin.posts.form.slug') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <input type="text" name="slug[{{ $editLang }}]" class="form-control"
                               placeholder="auto-generated-from-title">
                        <div class="invalid-feedback" data-field="slug.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.posts.form.slug_hint') }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">
                            {{ __('admin.posts.form.meta_description') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <textarea name="meta_description[{{ $editLang }}]" class="form-control" rows="2" maxlength="160"></textarea>
                        <div class="invalid-feedback" data-field="meta_description.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.posts.form.meta_description_hint') }}</div>
                    </div>
                </div>
            </div>

            {{-- Featured Image --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo icon me-1 text-primary"></i>
                        {{ __('admin.posts.form.image_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.posts.partials.image-dropzone')
                </div>
            </div>

            {{-- Category --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-category icon me-1 text-primary"></i>
                        {{ __('admin.posts.form.category') }}
                    </h3>
                </div>
                <div class="card-body">
                    <select name="blog_category_id" class="form-select">
                        <option value="">{{ __('admin.posts.form.no_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->localized_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" data-field="blog_category_id"></div>
                </div>
            </div>

            {{-- Publish Settings --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-calendar-event icon me-1 text-primary"></i>
                        {{ __('admin.posts.form.publish_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.posts.form.published_at') }}</label>
                        <input type="datetime-local" name="published_at" class="form-control">
                        <div class="invalid-feedback" data-field="published_at"></div>
                    </div>
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input">
                        <span class="form-check-label">{{ __('admin.posts.form.is_published') }}</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
@php $formId = 'create-post-form' @endphp
@include('admin.posts.partials.quill-init')
@endpush
