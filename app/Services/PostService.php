<?php

namespace App\Services;

use App\Events\PostCreated;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostService
{
    /**
     * Create a new post and dispatch events.
     */
    public function createPost(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create($data);

            // Dispatch event for any side effects (notifications, logging, etc.)
            event(new PostCreated($post));

            return $post;
        });
    }
}
