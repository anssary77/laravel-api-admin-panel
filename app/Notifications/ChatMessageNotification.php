<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $chatMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'chat_message_id' => $this->chatMessage->id,
            'sender_id' => $this->chatMessage->sender_id,
            'title' => 'New Message',
            'message' => 'You have a new message from ' . $this->chatMessage->sender->name,
            'url' => route('chat.index'),
            'icon' => 'fa-comments',
        ];
    }
}
