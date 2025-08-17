<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';
    
    protected $fillable = [
        'medecin_id',
        'patient_id',
        'date_rdv',
        'heure_rdv',
        'type_consultation',
        'motif',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_rdv' => 'date',
        'heure_rdv' => 'datetime',
    ];

    /**
     * Relation avec le modèle User (médecin)
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Relation avec le modèle User (patient)
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Récupère les rendez-vous à venir
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_rdv', '>=', now())
                    ->where('statut', '!=', 'annule')
                    ->orderBy('date_rdv')
                    ->orderBy('heure_rdv');
    }

    /**
     * Récupère les rendez-vous en attente
     */
    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente')
                    ->where('date_rdv', '>=', now())
                    ->orderBy('date_rdv')
                    ->orderBy('heure_rdv');
    }
}
