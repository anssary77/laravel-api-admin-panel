<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports dashboard with overview.
     */
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        
        // Overview statistics
        $overviewStats = Cache::remember("reports_overview_{$dateRange}", 3600, function () use ($startDate) {
            return [
                'total_users' => User::count(),
                'new_users' => User::where('created_at', '>=', $startDate)->count(),
                'total_activities' => ActivityLog::count(),
                'recent_activities' => ActivityLog::where('created_at', '>=', $startDate)->count(),
            ];
        });

        return view('admin.reports.index', compact('overviewStats', 'dateRange'));
    }

    /**
     * Display user reports dashboard.
     */
    public function users(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');
        
        $startDate = Carbon::now()->subDays($dateRange);
        
        // Base query for user filtering
        $query = User::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role) {
            $query->where('role', $role);
        }
        
        if ($status) {
            $query->where('status', $status);
        }

        // Statistics based on filtered query or global depending on context
        // Usually reports are better with filters applied to the data shown
        $totalUsers = User::count();
        $filteredUsersCount = (clone $query)->count();
        
        $userStats = [
            'total_users' => $totalUsers,
            'filtered_users' => $filteredUsersCount,
            'new_users' => (clone $query)->where('created_at', '>=', $startDate)->count(),
            'active_users' => (clone $query)->where('last_login_at', '>=', $startDate)->count(),
            'users_by_role' => (clone $query)->select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role'),
            'users_by_status' => (clone $query)->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'users_by_month' => (clone $query)->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'),
            // Get detailed list of users for the report
            'users_list' => $query->latest()->paginate(15)->withQueryString(),
        ];

        return view('admin.reports.users', compact('userStats', 'dateRange', 'search', 'role', 'status'));
    }

    /**
     * Display activity reports dashboard.
     */
    public function activity(Request $request)
    {
        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);
        
        // Activity statistics
        $activityStats = Cache::remember("activity_stats_{$dateRange}", 3600, function () use ($startDate) {
            return [
                'total_activities' => ActivityLog::count(),
                'recent_activities' => ActivityLog::where('created_at', '>=', $startDate)->count(),
                'activities_by_type' => ActivityLog::select('description', DB::raw('count(*) as total'))
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('description')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->pluck('total', 'description'),
                'activities_by_user' => ActivityLog::select('causer_id', 'users.name', DB::raw('count(*) as total'))
                    ->leftJoin('users', 'activity_logs.causer_id', '=', 'users.id')
                    ->where('activity_logs.created_at', '>=', $startDate)
                    ->groupBy('causer_id', 'users.name')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get(),
                'activities_by_day' => ActivityLog::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date'),
            ];
        });

        // Recent activities for display
        $recentActivities = ActivityLog::with('causer')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.reports.activity', compact('activityStats', 'recentActivities', 'dateRange'));
    }

    /**
     * Display system reports dashboard.
     */
    public function system(Request $request)
    {
        $systemStats = Cache::remember('system_stats', 3600, function () {
            return [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'database_driver' => config('database.default'),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
                'disk_free' => $this->formatBytes(disk_free_space('.')) ?? 'Unknown',
                'disk_total' => $this->formatBytes(disk_total_space('.')) ?? 'Unknown',
                'disk_usage_percent' => $this->getDiskUsagePercent(),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'mail_driver' => config('mail.default'),
            ];
        });

        // Database statistics
        $dbStats = Cache::remember('db_stats', 3600, function () {
            return [
                'users_count' => User::count(),
                'activities_count' => ActivityLog::count(),
                'roles_count' => DB::table('roles')->count(),
                'permissions_count' => DB::table('permissions')->count(),
                'database_size' => $this->getDatabaseSize(),
            ];
        });

        return view('admin.reports.system', compact('systemStats', 'dbStats'));
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes === 0 || $bytes === null) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get disk usage percentage.
     */
    private function getDiskUsagePercent()
    {
        $total = disk_total_space('.');
        $free = disk_free_space('.');
        
        if ($total === false || $free === false) return 'Unknown';
        
        $used = $total - $free;
        return round(($used / $total) * 100, 2) . '%';
    }

    /**
     * Get database size.
     */
    private function getDatabaseSize()
    {
        try {
            $driver = config('database.default');
            
            if ($driver === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if (file_exists($dbPath)) {
                    return $this->formatBytes(filesize($dbPath));
                }
            } elseif ($driver === 'mysql') {
                $database = config('database.connections.mysql.database');
                $result = DB::select("SELECT SUM(data_length + index_length) as size FROM information_schema.tables WHERE table_schema = ?", [$database]);
                return $this->formatBytes($result[0]->size ?? 0);
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }
        
        return 'Unknown';
    }
}