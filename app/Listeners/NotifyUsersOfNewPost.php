<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Models\User;
use App\Notifications\UserNewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUsersOfNewPost implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PostCreated $event): void
    {
        $post = $event->post;
        
        // Notify all users except the author and admins (admins already have their own notification)
        $users = User::where('role', 'user')
            ->where('id', '!=', $post->user_id)
            ->get();
            
        Notification::send($users, new UserNewPostNotification($post));
    }
}
