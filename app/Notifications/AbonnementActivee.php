<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Abonnement;

class AbonnementActivee extends Notification implements ShouldQueue
{
    use Queueable;

    protected $abonnement;
    protected $isPatient;

    public function __construct(Abonnement $abonnement, $isPatient = true)
    {
        $this->abonnement = $abonnement;
        $this->isPatient = $isPatient;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        if ($this->isPatient) {
            return [
                'type' => 'info',
                'title' => 'Abonnement activé',
                'message' => 'Votre abonnement Premium est actif jusqu’au ' . $this->abonnement->date_fin . '.',
            ];
        } else {
            return [
                'type' => 'info',
                'title' => 'Nouveau patient abonné',
                'message' => 'Un patient s’est abonné à votre suivi Premium.',
            ];
        }
    }
}
