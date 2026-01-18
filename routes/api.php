<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JwtAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::post('/login', [JwtAuthController::class, 'login']);
    Route::post('/register', [JwtAuthController::class, 'register']);
    Route::post('/forgot-password', [JwtAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [JwtAuthController::class, 'resetPassword']);
    Route::get('/verify-email/{id}/{hash}', [JwtAuthController::class, 'verifyEmail'])->name('verification.verify');
    
    // Public Posts
    Route::get('/public/posts', [PostController::class, 'publicIndex']);
    Route::get('/public/posts/{post}', [PostController::class, 'publicShow']);
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        
        // Auth routes
        Route::post('/logout', [JwtAuthController::class, 'logout']);
        Route::post('/refresh', [JwtAuthController::class, 'refresh']);
        Route::get('/profile', [JwtAuthController::class, 'profile']);
        Route::put('/profile', [JwtAuthController::class, 'updateProfile']);
        Route::post('/change-password', [JwtAuthController::class, 'changePassword']);
        Route::post('/resend-verification', [JwtAuthController::class, 'resendVerificationEmail']);
        
        // User management
        Route::middleware('permission:users.view')->group(function () {
            Route::get('users', [UserController::class, 'index']);
            Route::get('users/{user}', [UserController::class, 'show']);
        });
        
        Route::middleware('permission:users.create')->group(function () {
            Route::post('users', [UserController::class, 'store']);
        });
        
        Route::middleware('permission:users.update')->group(function () {
            Route::put('users/{user}', [UserController::class, 'update']);
            Route::post('users/{user}/restore', [UserController::class, 'restore']);
        });
        
        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('users/{user}', [UserController::class, 'destroy']);
            Route::post('users/bulk-delete', [UserController::class, 'bulkDelete']);
            Route::post('users/{user}/force-delete', [UserController::class, 'forceDelete']);
        });
        
        // Role management
        Route::middleware('permission:roles.view')->group(function () {
            Route::get('roles', [RoleController::class, 'index']);
            Route::get('roles/{role}', [RoleController::class, 'show']);
        });
        
        Route::middleware('permission:roles.create')->group(function () {
            Route::post('roles', [RoleController::class, 'store']);
        });
        
        Route::middleware('permission:roles.update')->group(function () {
            Route::put('roles/{role}', [RoleController::class, 'update']);
            Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);
            Route::delete('roles/{role}/permissions', [RoleController::class, 'revokePermissions']);
        });
        
        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('roles/{role}', [RoleController::class, 'destroy']);
        });
        
        // Permission management
        Route::middleware('permission:permissions.view')->group(function () {
            Route::get('permissions', [PermissionController::class, 'index']);
            Route::get('permissions/{permission}', [PermissionController::class, 'show']);
        });
        
        // Activity logs
        Route::middleware('permission:activity-logs.view')->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index']);
            Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show']);
        });
        
        // System settings
        Route::middleware('permission:settings.view')->group(function () {
            Route::get('/settings', [SettingController::class, 'index']);
            Route::get('/settings/{group}', [SettingController::class, 'showGroup']);
        });
        
        Route::middleware('permission:settings.update')->group(function () {
            Route::put('/settings', [SettingController::class, 'update']);
            Route::put('/settings/{group}', [SettingController::class, 'updateGroup']);
        });
        
        // Posts
        Route::middleware('permission:posts.view,api')->group(function () {
            Route::get('/posts', [PostController::class, 'index']);
            Route::get('/posts/{post}', [PostController::class, 'show']);
        });
        
        Route::middleware('permission:posts.create,api')->group(function () {
            Route::post('/posts', [PostController::class, 'store']);
        });
        
        Route::middleware('permission:posts.update,api')->group(function () {
            Route::put('/posts/{post}', [PostController::class, 'update']);
        });
        
        Route::middleware('permission:posts.delete,api')->group(function () {
            Route::delete('/posts/{post}', [PostController::class, 'destroy']);
        });
    });
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
        ]);
    });
});