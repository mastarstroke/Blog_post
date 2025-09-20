<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    // Public: list all posts
    public function index()
    {
        // get ?search= and ?per_page= from query params
        $search = request()->query('search');
        $perPage = (int) request()->query('per_page', 10);

        $posts = $this->postService->listAll($search, $perPage);

        return PostResource::collection($posts);
    }

    public function userPosts(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }

    // Public: view single post
    public function show(Post $post)
    {
        $post = $this->postService->show($post);
        return new PostResource($post);
    }

    // Authenticated: create post
    public function store(StorePostRequest $request): PostResource
    {
        $post = $this->postService->create($request->validated());
        return new PostResource($post);
    }

    // Authenticated: update own post
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post = $this->postService->update($post, $request->validated());
        return new PostResource($post);
    }

    // Authenticated: delete own post
    public function destroy(Post $post): JsonResponse
    {
        $this->postService->delete($post);
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
