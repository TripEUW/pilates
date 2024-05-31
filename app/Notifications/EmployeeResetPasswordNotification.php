<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// ! Error notifications class
class EmployeeResetPasswordNotification extends Notification
{
  use Queueable;
  public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
     $this->token =$token;
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
        return (new MailMessage)
                    ->subject('Restablecimiento de contraseña')
                    ->from('studio.sandra.galan@pilates.com', 'Studio Sandra Galán Pilates & Fisioterapia')
                    ->greeting('Hola '.$notifiable->name.' '.$notifiable->last_name.'!')
                    ->line('Recibió este correo  electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
                ->action('Restablecer mi contraseña', route('employee.password.reset',$this->token))
                    ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna acción adicional');
                    
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
