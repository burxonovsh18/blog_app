@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    @forelse ($posts as $post)
        <section class="post-item">
            <h2><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h2>
            <div class="meta">{{ $post->author->name }} · {{ $post->published_at->format('M j, Y') }}</div>
            @if ($post->excerpt)
                <p>{{ $post->excerpt }}</p>
            @endif
        </section>
    @empty
        <p class="empty">No posts yet. <a href="{{ route('dashboard') }}">Write the first one.</a></p>
    @endforelse

    @if ($posts->hasPages())
        <div class="pager">
            @if ($posts->previousPageUrl())
                <a href="{{ $posts->previousPageUrl() }}">Newer posts</a>
            @else
                <span></span>
            @endif
            @if ($posts->nextPageUrl())
                <a href="{{ $posts->nextPageUrl() }}">Older posts</a>
            @endif
        </div>
    @endif
@endsection
