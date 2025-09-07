<?php

namespace App\Notifications;

use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelAbonne extends Notification implements ShouldQueue
{
    use Queueable;

    public $abonnement;
    public $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Abonnement $abonnement, User $patient)
    {
        $this->abonnement = $abonnement;
        $this->patient = $patient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvel abonné à votre suivi')
            ->greeting('Bonjour Dr. ' . $notifiable->name . ',')
            ->line('Un nouveau patient s\'est abonné à votre suivi médical.')
            ->line('**Détails du patient :**')
            ->line('- Nom : ' . $this->patient->name)
            ->line('- Email : ' . $this->patient->email)
            ->line('**Détails de l\'abonnement :**')
            ->line('- Type : ' . ucfirst($this->abonnement->type))
            ->line('- Référence : ' . $this->abonnement->reference)
            ->line('- Date de début : ' . $this->abonnement->date_debut->format('d/m/Y'))
            ->line('- Date de fin : ' . $this->abonnement->date_fin->format('d/m/Y'))
            ->action('Voir le profil du patient', url('/medecin/patients/' . $this->patient->id))
            ->line('Merci de votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'nouvel_abonne',
            'message' => 'Un nouveau patient s\'est abonné à votre suivi : ' . $this->patient->name,
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->name,
            'abonnement_id' => $this->abonnement->id,
            'abonnement_type' => $this->abonnement->type
        ];
    }
}
