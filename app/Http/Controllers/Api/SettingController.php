<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Settings",
     *     description="System settings management endpoints"
 * )
 */
class SettingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/settings",
     *     summary="Get all system settings",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="group",
     *         in="query",
     *         description="Filter by group",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search settings by key or description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Setting"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SystemSetting::query();

        if ($request->filled('group')) {
            $query->where('group', $request->input('group'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $settings = $query->get();

        return SettingResource::collection($settings);
    }

    /**
     * @OA\Put(
     *     path="/settings",
     *     summary="Update multiple settings",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             additionalProperties=true,
     *             example={"app_name": "My App", "app_debug": false}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Settings updated successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Setting"))
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(SettingRequest $request): JsonResponse
    {
        $settings = [];
        
        foreach ($request->all() as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            
            if ($setting) {
                $setting->update(['value' => $value]);
                $settings[] = $setting;
            }
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => SettingResource::collection(collect($settings)),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/settings/{group}",
     *     summary="Get settings by group",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Settings group",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Setting"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Group not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function showGroup(string $group): AnonymousResourceCollection
    {
        $settings = SystemSetting::where('group', $group)->get();

        return SettingResource::collection($settings);
    }

    /**
     * @OA\Put(
     *     path="/settings/{group}",
     *     summary="Update settings by group",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Settings group",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             additionalProperties=true,
     *             example={"app_name": "My App", "app_debug": false}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Settings updated successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Setting"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Group not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function updateGroup(SettingRequest $request, string $group): JsonResponse
    {
        $settings = [];
        
        foreach ($request->all() as $key => $value) {
            $setting = SystemSetting::where('group', $group)->where('key', $key)->first();
            
            if ($setting) {
                $setting->update(['value' => $value]);
                $settings[] = $setting;
            }
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => SettingResource::collection(collect($settings)),
        ]);
    }
}