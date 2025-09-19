<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function listAll(?string $search = null, int $perPage = 10)
    {
        $query = Post::with('author')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function show(Post $post)
    {
        return $post->load('author');
    }

    public function create(array $data): Post
    {
        return Post::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => Auth::id(),
        ]);
    }

    public function update(Post $post, array $data): Post
    {
        // Ensure current user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You cannot update this post.');
        }

        $post->update($data);
        return $post->fresh('author');
    }

    public function delete(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You cannot delete this post.');
        }

        $post->delete();
    }

}
