<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserNewPostNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($post, $title = null, $message = null)
    {
        $this->post = $post;
        $this->title = $title ?? 'New Post Alert';
        $this->message = $message ?? 'A new post has been published by ' . ($post->user->name ?? 'another user');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'post_id' => $this->post->id ?? null,
            'title' => $this->title,
            'message' => $this->message,
            'url' => route('user.dashboard'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id ?? null,
            'title' => $this->title,
            'message' => $this->message,
            'url' => route('user.dashboard'),
            'icon' => 'fa-file-alt',
        ];
    }
}
