<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreneauHoraire extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'planning_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'pause_debut',
        'pause_fin',
        'type_consultation',
        'est_actif',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /**
     * Relation avec le planning.
     */
    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

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
            'les_deux' => 'Les deux',
            default => 'Non défini',
        };
    }
}
