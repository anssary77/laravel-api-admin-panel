<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use App\Models\User;
use App\Notifications\UserNewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class NotifyUsersOfPostUpdate implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PostUpdated $event): void
    {
        $post = $event->post;
        
        // If update is from admin, notify all users except the admin who did it
        // If update is from user, maybe just notify followers? 
        // For now, let's follow the user's request: "any update from admin"
        
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'admin') {
            $users = User::where('role', 'user')
                ->where('id', '!=', $post->user_id) // Don't notify the owner here if they are already notified in the controller
                ->get();
                
            Notification::send($users, new UserNewPostNotification(
                $post,
                'System Update',
                "Admin has updated a post: {$post->title}"
            ));
        }
    }
}
