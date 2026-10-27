<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with('author:id,name')->published()->latest('published_at')->paginate(8);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->isPublished(), 404);

        return view('blog.show', ['post' => $post->load('author:id,name')]);
    }
}
