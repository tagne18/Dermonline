<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MedecinAccountCreated extends Notification
{
    use Queueable;

    public $user;
    public $plainPassword;

    public function __construct($user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Compte Médecin Approuvé')
                    ->greeting('Bonjour Dr. ' . $this->user->name)
                    ->line('Votre compte a été approuvé avec succès.')
                    ->line('Vous pouvez maintenant vous connecter avec les identifiants suivants :')
                    ->line('**Email :** ' . $this->user->email)
                    ->line('**Mot de passe :** ' . $this->plainPassword)
                    ->line('⚠️ Veuillez changer votre mot de passe après votre première connexion.')
                    ->action('Connexion', url('/login'))
                    ->line('Merci de rejoindre notre équipe médicale.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Compte médecin créé',
            'message' => 'Votre compte médecin a été créé avec succès.',
        ];
    }
}
