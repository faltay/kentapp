@extends('layouts.menu')

@section('title', __('blog.title'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">{{ __('blog.title') }}</h1>
            <p class="text-muted">{{ __('blog.subtitle') }}</p>
        </div>
    </div>

    @if($categories->isNotEmpty())
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('blog.index') }}"
           class="btn btn-sm {{ !$activeCategory ? 'btn-primary' : 'btn-outline-primary' }}">
            {{ __('blog.all_categories') }}
        </a>
        @foreach($categories as $cat)
            @if($cat->posts_count > 0)
            <a href="{{ route('blog.index', ['category' => $cat->localized_slug]) }}"
               class="btn btn-sm {{ $activeCategory && $activeCategory->id === $cat->id ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $cat->localized_name }}
                <span class="badge bg-primary-lt ms-1">{{ $cat->posts_count }}</span>
            </a>
            @endif
        @endforeach
    </div>
    @endif

    @if($posts->isEmpty())
        <div class="text-center py-5 text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" class="mb-3 d-block mx-auto"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11l3 3v11a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/><path d="M8 4v4h7v-4"/><path d="M9 15h.01"/><path d="M13 15h.01"/><path d="M9 11h6"/></svg>
            <p>{{ __('blog.no_posts') }}</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold">{{ $post->localized_title }}</h5>
                        @if($post->localized_excerpt)
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($post->localized_excerpt, 120) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                {{ $post->published_at?->format('d M Y') }}
                            </small>
                            <a href="{{ route('blog.show', $post->localized_slug) }}" class="btn btn-sm btn-outline-primary">
                                {{ __('blog.read_more') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
