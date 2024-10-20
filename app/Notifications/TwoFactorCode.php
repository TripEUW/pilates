<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCode extends Notification
{
    use Queueable;

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
        return (new MailMessage)
            //->from(config('mail.MAIL_FROM_ADDRESS'))
            ->line('Tu código de verificación en dos pasos es ' . $notifiable->two_factor_code)
            ->action('Verificar Aquí', route('verify.index'))
            ->line('El código expirará en 10 minutos')
            ->line('Si no has intentado iniciar sesión, ignora este mensaje.');

    }
}