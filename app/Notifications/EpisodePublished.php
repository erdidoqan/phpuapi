<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class EpisodePublished extends Notification implements ShouldQueue
{
    use Queueable;

    private $episode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($episode)
    {
        $this->episode = $episode;
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
             ->line('#Merhaba '. $name.',')
             ->subject('Yeni bölüm yayınlandı!')
             ->line('##Yeni bölüm yayınlandı')
             ->line($this->episode->name . ' oluşturuldu. İzlemek için tıklayınız.')
             ->action('Bölümü İzle', $this->episode->client_url)
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
            'name' => 'episode_published',
            'title' => $this->episode->name,
            'message' => 'Yeni bölüm yayınlandı.',
            'path' => $this->episode->client_path,
        ];
    }
}
