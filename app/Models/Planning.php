<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planning extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'medecin_id',
        'titre',
        'date_consultation',
        'heure_debut',
        'heure_fin',
        'duree_consultation',
        'type_consultation',
        'prix',
        'description',
        'statut',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_consultation' => 'date',
        'prix' => 'decimal:2',
        'duree_consultation' => 'integer',
    ];

    /**
     * Relation avec le modèle User (médecin).
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Relation avec les créneaux horaires.
     */
    /**
     * Obtenir le libellé du type de consultation.
     *
     * @return string
     */
    public function getTypeConsultationLibelleAttribute(): string
    {
        return match($this->type_consultation) {
            'presentiel' => 'Présentiel',
            'en_ligne' => 'En ligne',
            'hybride' => 'Hybride',
            default => 'Non défini',
        };
    }

    /**
     * Obtenir la durée formatée de la consultation.
     *
     * @return string
     */
    public function getDureeFormatteeAttribute(): string
    {
        $heures = floor($this->duree_consultation / 60);
        $minutes = $this->duree_consultation % 60;
        
        if ($heures > 0) {
            return $heures . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
        }
        
        return $minutes . ' min';
    }
    
    /**
     * Formater la date et l'heure de début.
     *
     * @return string
     */
    public function getDateHeureDebutAttribute(): string
    {
        return $this->date_consultation->format('d/m/Y') . ' à ' . $this->heure_debut;
    }
}
