<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Activity Logs",
 *     description="Activity log management endpoints"
 * )
 */
class ActivityLogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/activity-logs",
     *     summary="Get all activity logs",
     *     tags={"Activity Logs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filter by user ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="log_name",
     *         in="query",
     *         description="Filter by log name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Search in description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter by start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter by end date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Activity logs retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ActivityLog")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ActivityLog::with(['causer', 'subject']);

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->input('user_id'));
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->input('log_name'));
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', "%{$request->input('description')}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $logs = $query->latest()->paginate($request->input('per_page', 15));

        return ActivityLogResource::collection($logs);
    }

    /**
     * @OA\Get(
     *     path="/activity-logs/{log}",
     *     summary="Get activity log details",
     *     tags={"Activity Logs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="log",
     *         in="path",
     *         required=true,
     *         description="Activity log ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Activity log retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/ActivityLog")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Activity log not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(ActivityLog $log): JsonResponse
    {
        return response()->json([
            'data' => new ActivityLogResource($log->load(['causer', 'subject'])),
        ]);
    }
}