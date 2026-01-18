<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Post management endpoints"
 * )
 */
class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * @OA\Get(
     *     path="/public/posts",
     *     summary="Get all published posts (Public)",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search in title",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     )
     * )
     */
    public function publicIndex(Request $request): JsonResponse
    {
        $query = Post::with(['user'])->where('status', 'published');

        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->input('title')}%");
        }

        $posts = $query->latest()->paginate($request->input('per_page', 15));

        return response()->json([
            'message' => 'Public posts retrieved successfully',
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/public/posts/{post}",
     *     summary="Get published post details (Public)",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully"
     *     ),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function publicShow(Post $post): JsonResponse
    {
        if ($post->status !== 'published') {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json([
            'data' => new PostResource($post->load(['user'])),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (draft, published, archived)",
     *         @OA\Schema(type="string", enum={"draft", "published", "archived"})
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filter by user ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="others_only",
     *         in="query",
     *         description="Filter to show only posts by other users",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search in title",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['user']);

        // Filter out posts by the current user by default, 
        // unless they specifically ask for their own or all posts
        if (!$request->has('user_id') || $request->boolean('others_only')) {
            $query->where('user_id', '!=', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('user_id') && !$request->boolean('others_only')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->input('title')}%");
        }

        $posts = $query->latest()->paginate($request->input('per_page', 15));

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'from' => $posts->firstItem(),
                'last_page' => $posts->lastPage(),
                'path' => $posts->path(),
                'per_page' => $posts->perPage(),
                'to' => $posts->lastItem(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/posts/{post}",
     *     summary="Get post details",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}),
     *                 @OA\Property(property="user_id", type="string", format="uuid"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="user", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'data' => new PostResource($post->load(['user'])),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "contact_phone_number"},
     *             @OA\Property(property="title", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", maxLength=2048),
     *             @OA\Property(property="contact_phone_number", type="string", maxLength=20),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}, default="draft")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'contact_phone_number' => 'required|string|max:20',
            'content' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        $validated['user_id'] = $request->user()->id;

        $post = $this->postService->createPost($validated);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => new PostResource($post->load(['user'])),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/posts/{post}",
     *     summary="Update a post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", maxLength=2048),
     *             @OA\Property(property="contact_phone_number", type="string", maxLength=20),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="status", type="string", enum={"draft", "published", "archived"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2048',
            'contact_phone_number' => 'sometimes|string|max:20',
            'content' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post->load(['user']),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/posts/{post}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}