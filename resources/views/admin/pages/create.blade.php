@extends('layouts.admin')

@section('title', __('admin.pages.create'))

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">{{ __('common.admin_panel') }}</div>
            <h2 class="page-title">{{ __('admin.pages.create') }}</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
</div>

<form id="page-form" data-action="{{ route('admin.pages.store') }}">
    @csrf
    <input type="hidden" name="lang" value="{{ $editLang }}">
    <div class="row">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Page Content --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-article icon me-1 text-primary"></i>
                        {{ __('admin.pages.form.content_section') }}
                    </h3>
                    <div class="card-actions">
                        @foreach($activeLanguages as $lang)
                            <a href="{{ route('admin.pages.create') }}?lang={{ $lang->code }}"
                               class="badge {{ $lang->code === $editLang ? 'bg-primary text-white' : 'bg-secondary-lt' }} text-decoration-none">
                                {{ strtoupper($lang->code) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.pages.form.title') }}</label>
                        <input type="text" name="title[{{ $editLang }}]" class="form-control" required autofocus>
                        <div class="invalid-feedback" data-field="title.{{ $editLang }}"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">{{ __('admin.pages.form.content') }}</label>
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
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
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
                        {{ __('admin.pages.form.seo_section') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">
                            {{ __('admin.pages.form.slug') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <input type="text" name="slug[{{ $editLang }}]" class="form-control"
                               placeholder="auto-generated-from-title">
                        <div class="invalid-feedback" data-field="slug.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.pages.form.slug_hint') }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">
                            {{ __('admin.pages.form.meta_description') }}
                            <span class="text-muted fw-normal">({{ strtoupper($editLang) }})</span>
                        </label>
                        <textarea name="meta_description[{{ $editLang }}]" class="form-control" rows="2" maxlength="160"></textarea>
                        <div class="invalid-feedback" data-field="meta_description.{{ $editLang }}"></div>
                        <div class="form-hint">{{ __('admin.pages.form.meta_description_hint') }}</div>
                    </div>
                </div>
            </div>

            {{-- Sort Order --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-arrows-sort icon me-1 text-primary"></i>
                        {{ __('admin.pages.form.sort_order') }}
                    </h3>
                </div>
                <div class="card-body">
                    <input type="number" name="sort_order" class="form-control" min="0" value="0">
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
                    <div class="mb-2">
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_homepage" value="1" class="form-check-input">
                            <span class="form-check-label">{{ __('admin.pages.form.is_homepage') }}</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-check form-switch">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input">
                            <span class="form-check-label">{{ __('admin.pages.form.is_published') }}</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
@php $formId = 'page-form' @endphp
@include('admin.pages.partials.quill-init')
@endpush
