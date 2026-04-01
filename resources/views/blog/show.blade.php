@extends('layouts.menu')

@section('title', $post->localized_title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-3">
                <a href="{{ route('blog.index') }}" class="text-muted text-decoration-none small">
                    ← {{ __('blog.back_to_blog') }}
                </a>
            </div>

            <h1 class="fw-bold mb-2">{{ $post->localized_title }}</h1>

            <div class="text-muted small mb-4">
                @if($post->blogCategory)
                    <a href="{{ route('blog.index', ['category' => $post->blogCategory->localized_slug]) }}"
                       class="badge bg-primary-lt text-decoration-none me-2">{{ $post->blogCategory->localized_name }}</a>
                @endif
                {{ __('blog.by') }} <strong>{{ $post->author->name ?? '' }}</strong>
                &bull;
                {{ $post->published_at?->format('d M Y') }}
            </div>

            @if($post->localized_excerpt)
            <p class="lead text-muted border-start border-3 ps-3 mb-4">{{ $post->localized_excerpt }}</p>
            @endif

            <div class="blog-content">
                {!! $post->localized_content !!}
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.blog-content img { max-width: 100%; height: auto; border-radius: .5rem; margin: 1rem 0; }
.blog-content h1,.blog-content h2,.blog-content h3 { margin-top: 1.5rem; margin-bottom: .75rem; font-weight: 600; }
.blog-content p { line-height: 1.8; margin-bottom: 1rem; }
.blog-content blockquote { border-left: 4px solid #e9ecef; padding-left: 1rem; color: #6c757d; font-style: italic; }
.blog-content ul,.blog-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.blog-content li { margin-bottom: .25rem; }
</style>
@endpush
@endsection
