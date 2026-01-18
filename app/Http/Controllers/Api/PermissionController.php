<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="Permission management endpoints"
 * )
 */
class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/permissions",
     *     summary="Get all permissions",
     *     tags={"Permissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search permissions by name or description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="group",
     *         in="query",
     *         description="Filter by group",
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
     *         description="Permissions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Permission")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $query->where('group', $request->input('group'));
        }

        $permissions = $query->paginate($request->input('per_page', 15));

        return PermissionResource::collection($permissions);
    }

    /**
     * @OA\Get(
     *     path="/permissions/{permission}",
     *     summary="Get permission details",
     *     tags={"Permissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         required=true,
     *         description="Permission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Permission")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Permission not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(Permission $permission): JsonResponse
    {
        return response()->json([
            'data' => new PermissionResource($permission),
        ]);
    }
}