<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) return redirect()->route('admin.login');
        
        $notifications = $user->notifications()->paginate(20);
        $view = $user->role === 'admin' ? 'admin.notifications.index' : 'user.notifications.index';
        return view($view, compact('notifications'));
    }

    public function getUnread()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) return response()->json(['count' => 0, 'notifications' => []]);
        
        $notifications = $user->unreadNotifications->take(5)->map(function($n) {
            $n->created_at_human = $n->created_at->diffForHumans();
            return $n;
        });
        
        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        
        if ($request->has('id')) {
            $user->unreadNotifications->where('id', $request->id)->markAsRead();
        } else {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
