<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Notifications\UserNewPostNotification;
use App\Events\PostUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function index(Request $request)
    {
        $query = Post::with('user');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->title}%");
        }

        // Only show posts from OTHER users if requested
        if ($request->has('others_only') && $request->others_only == '1') {
            $query->where('user_id', '!=', Auth::id());
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::all();

        return view('admin.posts.index', compact('posts', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.posts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048', // 2KB limit
            'contact_phone_number' => 'required|string|max:20',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $this->postService->createPost($validated);
            return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create post: ' . $e->getMessage());
        }
    }

    public function show(Post $post)
    {
        $post->load('user');
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $users = User::all();
        return view('admin.posts.edit', compact('post', 'users'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048', // 2KB limit
            'contact_phone_number' => 'required|string|max:20',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $post->update($validated);
            
            // Dispatch update event
            event(new PostUpdated($post));
            
            // Notify the author if the admin updated their post
            if ($post->user_id != Auth::id()) {
                $post->user->notify(new UserNewPostNotification(
                    $post, 
                    'Post Updated by Admin', 
                    "Admin has updated your post: {$post->title}"
                ));
            }

            DB::commit();
            return redirect()->route('admin.posts.index', ['page' => $request->query('page')])->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update post: ' . $e->getMessage());
        }
    }

    public function destroy(Post $post)
    {
        DB::beginTransaction();
        try {
            $post->delete();
            DB::commit();
            return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete post: ' . $e->getMessage());
        }
    }
}
