<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    //Creates a new notification instance.
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    //Gets the notification's delivery channels.
    public function via($notifiable)
    {
        return ['database'];
    }

    //Gets the array representation of the notification.
    public function toArray($notifiable)
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'commenter_name' => $this->comment->user->name,
            'content' => $this->comment->content,
        ];
    }
}
