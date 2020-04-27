<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentReplied extends Notification
{
    use Queueable;

    private $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
         $array = $this->toArray($notifiable);

         return (new MailMessage)
             ->from('noreply@phpuzem.com', 'Phpuzem')
             ->line('#Merhaba '. $notifiable->name.',')
             ->subject('Yorumunuza cevap yazıldı!')
             ->line('##Yorumunuza cevap yazıldı')
             ->line('Görüntülemek için tıklayınız.')
             ->action('Yorumu Gör', $array['url'])
             ->line('phpuzem.com\'u tercih ettiğiniz için teşekkürler!');
     }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $array = [
            'name' => 'comment_replied',
            'message' => 'Yorumunuza cevap yazıldı.',
        ];

        $commentable = $this->comment->commentable;
        $commentableType = $this->comment->commentable_type;

        if ($commentableType == 'App\Episode') {
            if ($commentable->lesson->isStandalone()) {
                $array['type'] = 'lesson';
                $array['title'] = $commentable->lesson->name;
                $array['path'] = $commentable->lesson->client_path;
                $array['url'] = $commentable->lesson->client_url;
            } else {
                $array['type'] = 'episode';
                $array['title'] = $commentable->name;
                $array['path'] = $commentable->client_path;
                $array['url'] = $commentable->client_url;
            }
        } else if ($commentableType == 'App\Post') {
            $array['type'] = 'post';
            $array['title'] = $commentable->title;
            $array['path'] = $commentable->client_path;
            $array['url'] = $commentable->client_url;
        } else if ($commentableType == 'App\Thread') {
            $array['type'] = 'thread';
            $array['title'] = $commentable->title;
            $array['path'] = $commentable->client_path;
            $array['url'] = $commentable->client_url;
        }

        return $array;
    }
}
