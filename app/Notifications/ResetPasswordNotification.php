<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $ipLocal = '192.168.0.14';
        
        // Construimos el enlace usando la IP fija para que el teléfono SIEMPRE alcance el Apache de tu PC
        $urlPuente = "http://{$ipLocal}/inventori/public/redirect-reset/{$this->token}?email=" . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Inventori - Solicitud de Restablecimiento de Contraseña')
            ->greeting('¡Hola!')
            ->line('Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta en Inventori.')
            ->action('Restablecer Contraseña', $urlPuente)
            ->line('Este enlace caducará en 60 minutos.')
            ->line('Si no solicitaste este cambio, no se requiere ninguna otra acción.');
    }
}