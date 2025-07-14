<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorWelcomeNotification extends Notification
{
    use Queueable;

    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur la plateforme de téléconsultation')
            ->greeting('Bonjour Dr. ' . $notifiable->name . ',')
            ->line('Votre compte a été validé par notre équipe.')
            ->line('Voici vos identifiants de connexion :')
            ->line('Email : ' . $notifiable->email)
            ->line('Mot de passe temporaire : ' . $this->password)
            ->line('Merci de vous connecter et de modifier votre mot de passe dans votre profil.')
            ->action('Se connecter', url('/login'))
            ->line('Bienvenue à bord !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Bienvenue docteur',
            'message' => 'Bienvenue dans notre équipe médicale !',
        ];
    }
}
