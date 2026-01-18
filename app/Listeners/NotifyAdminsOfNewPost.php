<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfNewPost implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PostCreated $event): void
    {
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewPostNotification($event->post));
    }
}
