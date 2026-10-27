<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    // GET /api/posts  (public)
    public function index(): AnonymousResourceCollection
    {
        $posts = Post::with('author:id,name')->published()->latest('published_at')->paginate(10);

        return PostResource::collection($posts);
    }

    // GET /api/posts/{slug}  (public)
    public function show(Post $post): PostResource
    {
        abort_unless($post->isPublished(), 404);

        return new PostResource($post->load('author:id,name'));
    }

    // GET /api/my/posts  (auth)
    public function mine(Request $request): AnonymousResourceCollection
    {
        return PostResource::collection(
            $request->user()->posts()->latest()->paginate(50)
        );
    }

    // POST /api/posts  (auth)
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['published'] ? now() : null;
        unset($data['published']);

        $post = $request->user()->posts()->create($data);

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    // PUT /api/posts/{id}  (auth, owner only)
    public function update(Request $request, Post $post): PostResource
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $data = $this->validated($request);
        $data['published_at'] = $data['published']
            ? ($post->published_at ?? now())
            : null;
        unset($data['published']);

        $post->update($data);

        return new PostResource($post);
    }

    // DELETE /api/posts/{id}  (auth, owner only)
    public function destroy(Request $request, Post $post): JsonResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $post->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'     => ['required', 'string', 'max:200'],
            'excerpt'   => ['nullable', 'string', 'max:300'],
            'body'      => ['required', 'string'],
            'published' => ['required', 'boolean'],
        ]);
    }
}
