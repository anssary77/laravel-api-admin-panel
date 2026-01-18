<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        $stats = $this->getDashboardStats();
        $recentActivity = $this->getRecentActivity();
        $recentPosts = $this->getRecentPosts();
        $userGrowth = $this->getUserGrowth();
        $systemHealth = $this->getSystemHealth();
        $postStats = $this->getPostStats();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'recentPosts', 'userGrowth', 'systemHealth', 'postStats'));
    }

    /**
     * Get post statistics by status.
     */
    private function getPostStats(): array
    {
        return [
            'published' => \App\Models\Post::where('status', 'published')->count(),
            'draft' => \App\Models\Post::where('status', 'draft')->count(),
            'pending' => \App\Models\Post::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get recent posts.
     */
    private function getRecentPosts(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Post::with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_posts' => \App\Models\Post::count(),
            'total_roles' => Role::count(),
            'total_permissions' => \App\Models\Permission::count(),
            'recent_activity' => ActivityLog::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'today_activity' => ActivityLog::whereDate('created_at', Carbon::today())->count(),
        ];
    }

    /**
     * Get recent activity logs.
     */
    private function getRecentActivity(): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::with(['causer', 'subject'])
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Get user growth data for charts.
     */
    private function getUserGrowth(): array
    {
        $data = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d')),
            'data' => $data->pluck('count'),
        ];
    }

    /**
     * Get system health information.
     */
    private function getSystemHealth(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_connection' => config('database.default'),
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
            'disk_free' => $this->formatBytes(disk_free_space('.')),
            'disk_total' => $this->formatBytes(disk_total_space('.')),
        ];
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}