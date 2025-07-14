<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment, $status)
    {
        $this->appointment = $appointment;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre rendez-vous')
            ->line('Votre rendez-vous du ' . $this->appointment->date . ' a été ' . $this->status . ' par le médecin.')
            ->action('Voir mes consultations', url('/patient/consultations'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Statut du rendez-vous',
            'message' => 'Votre rendez-vous du ' . $this->appointment->date . ' a été ' . $this->status . ' par le médecin.',
            'appointment_id' => $this->appointment->id,
            'status' => $this->status,
        ];
    }
}
