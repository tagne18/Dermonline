<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TestimonialStatusNotification extends Notification
{
    use Queueable;

    public $testimonial;
    public $statut;

    public function __construct($testimonial, $statut)
    {
        $this->testimonial = $testimonial;
        $this->statut = $statut;
    }

    public function via($notifiable)
    {
        return ['database']; // Ajoute 'mail' si tu veux aussi un email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Votre témoignage a été ' . $this->statut . '.',
            'content' => $this->testimonial->content,
            'statut' => $this->statut,
        ];
    }
}
