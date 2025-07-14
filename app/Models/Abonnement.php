<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $table = 'abonnements'; // seulement si Laravel ne le détecte pas automatiquement

    protected $fillable = [
        'user_id',
        'medecin_id',
        'type',
        'date_debut',
        'date_fin',
        'statut',
    ];

    /**
     * Relation avec le patient (utilisateur qui s'abonne)
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Relation avec l'utilisateur (alias pour compatibilité)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
