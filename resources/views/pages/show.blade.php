@extends('layouts.menu')

@section('title', $page->localized_title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">{{ $page->localized_title }}</h1>

            <div class="page-content">
                {!! $page->localized_content !!}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.page-content img { max-width: 100%; height: auto; border-radius: .5rem; margin: 1rem 0; }
.page-content h1,.page-content h2,.page-content h3 { margin-top: 1.5rem; margin-bottom: .75rem; font-weight: 600; }
.page-content p { line-height: 1.8; margin-bottom: 1rem; }
.page-content blockquote { border-left: 4px solid #e9ecef; padding-left: 1rem; color: #6c757d; font-style: italic; }
.page-content ul,.page-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
</style>
@endpush
@endsection
