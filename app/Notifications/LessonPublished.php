<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LessonPublished extends Notification implements ShouldQueue
{
    use Queueable;

    private $lesson;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable instanceof User ? ['database', 'mail'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
         $name = $notifiable instanceof User ? ' '.$notifiable->name : '';
         return (new MailMessage)
             ->from('noreply@phpuzem.com', 'Phpuzem')
             ->line('#Merhaba'. $name.',')
             ->subject('Yeni ders yayınlandı!')
             ->line('##Yeni ders yayınlandı')
             ->line($this->lesson->name . ' oluşturuldu. İzlemek için tıklayınız.')
             ->action('Dersi İzle', $this->lesson->client_url)
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
        return [
            'name' => 'lesson_published',
            'title' => $this->lesson->name,
            'message' => 'Yeni ders yayınlandı.',
            'path' => $this->lesson->client_path,
        ];
    }
}
