<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('admin.login');
});

Route::middleware(['auth'])->group(function () {
    // Shared Chat Routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages/{userId?}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Shared Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// User routes
Route::prefix('user')
    ->name('user.')
    ->middleware(['web', 'auth', 'role:user'])
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        
        // Profile routes
        Route::get('profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::put('profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [UserDashboardController::class, 'updatePassword'])->name('profile.password');
        
        // Settings routes
        Route::get('settings', [UserDashboardController::class, 'settings'])->name('settings');
        Route::put('settings', [UserDashboardController::class, 'updateSettings'])->name('settings.update');

        // Notifications routes
        Route::get('notifications/unread', [UserDashboardController::class, 'unreadNotifications'])->name('notifications.unread');
        Route::post('notifications/mark-as-read', [UserDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-as-read');

        // Posts list route
        Route::get('posts', [UserDashboardController::class, 'posts'])->name('posts.index');
    });

// Admin routes
Route::prefix(config('app.admin_prefix', 'admin'))
    ->name('admin.')
    ->middleware(['web', 'auth', 'role:admin'])
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/bulk-actions', [UserController::class, 'bulkActions'])->name('users.bulk-actions');
        Route::delete('users/bulk-delete', [UserController::class, 'bulkActions'])->name('users.bulk-delete');
        Route::post('users/bulk-deactivate', [UserController::class, 'bulkActions'])->name('users.bulk-deactivate');
        Route::get('users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');

        // Post Management
        Route::resource('posts', PostController::class);
        
        // Role Management
        Route::resource('roles', RoleController::class);
        Route::delete('roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
        Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions');
        
        // Permission Management
        Route::resource('permissions', PermissionController::class)->except(['create', 'store', 'destroy']);
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        
        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::delete('activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
        Route::delete('activity-logs/{log?}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        
        // System Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/edit/{group}', [SettingController::class, 'editGroup'])->name('settings.group');
        Route::put('settings/edit/{group}', [SettingController::class, 'updateGroup'])->name('settings.group.update');
        Route::post('settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
        Route::post('settings/restore', [SettingController::class, 'restore'])->name('settings.restore');
        Route::post('settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test-email');
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/users', [ReportController::class, 'users'])->name('reports.users');
        Route::get('reports/activity', [ReportController::class, 'activity'])->name('reports.activity');
        Route::get('reports/system', [ReportController::class, 'system'])->name('reports.system');

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
        Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        
        // File Manager
        Route::get('file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
        Route::post('file-manager/upload', [FileManagerController::class, 'upload'])->name('file-manager.upload');
        Route::delete('file-manager/{file}', [FileManagerController::class, 'destroy'])->name('file-manager.destroy');
        Route::post('file-manager/create-directory', [FileManagerController::class, 'createDirectory'])->name('file-manager.create-directory');
        Route::post('file-manager/rename', [FileManagerController::class, 'rename'])->name('file-manager.rename');
        Route::get('file-manager/download/{file}', [FileManagerController::class, 'download'])->name('file-manager.download');
        
        // Profile routes
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    });

// Authentication routes
Route::prefix(config('app.admin_prefix', 'admin'))
    ->name('admin.')
    ->middleware('web')
    ->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register'])->name('register.post');
        
        // Password reset routes
        // Route::get('password/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
        // Route::post('password/reset', [AuthController::class, 'reset'])->name('password.reset.post');
    });