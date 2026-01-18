<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['causer', 'subject']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->where('causer_id', $request->user);
        }

        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate(15);
        $users = User::all();
        $logNames = ActivityLog::select('log_name')->distinct()->pluck('log_name');
        $events = ActivityLog::select('event')->distinct()->pluck('event');

        return view('admin.activity-logs.index', compact('logs', 'users', 'logNames', 'events'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['causer', 'subject']);
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Remove activity logs.
     */
    public function destroy(Request $request, ActivityLog $log = null)
    {
        // Handle single deletion from route parameter
        if ($log && $log->exists) {
            $log->delete();
            return redirect()->route('admin.activity-logs.index')
                ->with('success', 'Activity log entry deleted successfully.');
        }

        // Handle bulk deletion from request ids
        if ($request->has('ids')) {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:activity_logs,id',
            ]);

            ActivityLog::whereIn('id', $validated['ids'])->delete();

            return redirect()->route('admin.activity-logs.index')
                ->with('success', 'Selected activity logs deleted successfully.');
        }

        return redirect()->route('admin.activity-logs.index')
            ->with('error', 'No logs selected for deletion.');
    }

    /**
     * Clear all activity logs.
     */
    public function clear()
    {
        ActivityLog::truncate();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'All activity logs cleared successfully.');
    }
}