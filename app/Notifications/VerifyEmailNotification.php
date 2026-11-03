<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $ipLocal = '192.168.0.14';
        $hash = sha1($notifiable->getEmailForVerification());
        
        // Generamos el enlace que apunta al puente usando la IP local
        $urlPuente = "http://{$ipLocal}/inventori/public/redirect-verify/{$notifiable->getKey()}/{$hash}";

        return (new MailMessage)
            ->subject('Inventori - Verificación de Correo Electrónico')
            ->greeting('¡Hola!')
            ->line('Por favor haz clic en el siguiente botón para confirmar y activar tu cuenta.')
            ->action('Confirmar y Activar Cuenta', $urlPuente)
            ->line('Si no creaste esta cuenta, no se requiere ninguna otra acción.');
    }
}