<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Fetch user stats
        $stats = [
            'total_posts' => Post::where('user_id', $user->id)->count(),
            'account_status' => ucfirst($user->status),
            'role_name' => ucfirst($user->role),
        ];

        // Fetch posts from other users (excluding Admin and Manager)
        // Paginated, limited to 512 chars (handled in view), sorted by most recent first
        $otherPosts = Post::with('user')
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', function($query) {
                $query->whereNotIn('role', ['admin', 'manager']);
            })
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        // Fetch user's recent activity logs
        $recentActivity = \Spatie\Activitylog\Models\Activity::where('causer_id', $user->id)
            ->where('causer_type', get_class($user))
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('user', 'stats', 'recentActivity', 'otherPosts'));
    }

    /**
     * Show all posts from other users.
     */
    public function posts()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $posts = Post::with('user')
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', function($query) {
                $query->whereNotIn('role', ['admin', 'manager']);
            })
            ->where('status', 'published')
            ->latest()
            ->paginate(15);

        return view('user.posts.index', compact('posts'));
    }

    /**
     * Show the profile page.
     */
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mobile_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'bio' => $request->bio,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($updateData);

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request)
    {
        // This is a placeholder for settings update logic
        // You can add more settings here as needed
        return redirect()->route('user.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Get unread notifications for the user.
     */
    public function unreadNotifications()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $notifications = $user->unreadNotifications->take(5)->map(function($n) {
            $n->created_at_human = $n->created_at->diffForHumans();
            return $n;
        });
        
        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notifications as read.
     */
    public function markNotificationsAsRead(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($request->has('id')) {
            $user->unreadNotifications->where('id', $request->id)->markAsRead();
        } else {
            $user->unreadNotifications->markAsRead();
        }
        
        return response()->json(['success' => true]);
    }
}
