@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        <div class="meta">{{ $post->author->name }} · {{ $post->published_at->format('F j, Y') }}</div>
        <div class="body">
            @foreach (preg_split('/\R{2,}/', trim($post->body)) as $paragraph)
                <p>{!! nl2br(e($paragraph)) !!}</p>
            @endforeach
        </div>
    </article>
    <p><a href="{{ route('blog.index') }}">Back to all posts</a></p>
@endsection
