<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class PasswordResetNotification extends Notification
{
    private $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $urlToResetFrom = env('CLIENT_URL') . '/sifre-sifirla?token=' . $this->token;

        return (new MailMessage)
            ->from('no-reply@phpuzem.com', 'PHPUzem Şifre Sıfırlama')
            ->subject(Lang::getFromJson('Phpuzem Şifre Sıfırlama'))
            ->line(Lang::getFromJson('Bu e-postayı, hesabınız için bir şifre sıfırlama isteği aldığımız için alıyorsunuz.'))
            ->action(Lang::getFromJson('Şifreyi Sıfırla'), $urlToResetFrom)
            ->line(Lang::getFromJson('Bu parola sıfırlama linki :count dakika sonra sonlanacaktır.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('Parola sıfırlama isteğinde bulunmadıysanız, başka bir işlem yapmanız gerekmez.'));
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
            //
        ];
    }
}
