<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Post Created')
                    ->line('A new post has been created: ' . $this->post->title)
                    ->action('View Post', route('admin.posts.show', $this->post->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'user_name' => $this->post->user->name ?? 'Unknown',
            'message' => 'New post created by ' . ($this->post->user->name ?? 'Unknown'),
            'url' => route('admin.posts.show', $this->post->id),
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
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'user_name' => $this->post->user->name ?? 'Unknown',
            'message' => 'New post created by ' . ($this->post->user->name ?? 'Unknown'),
            'url' => route('admin.posts.show', $this->post->id),
        ];
    }
}
