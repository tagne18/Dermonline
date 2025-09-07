<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicament extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nom',
        'description',
        'categorie',
        'forme_galenique',
        'dosage',
        'unite',
        'sur_ordonnance',
        'contre_indications',
        'effets_secondaires',
        'interactions',
        'code_cip',
        'code_cip13',
        'taux_remboursement',
        'est_actif',
    ];

    protected $casts = [
        'sur_ordonnance' => 'boolean',
        'est_actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les prescriptions qui contiennent ce médicament
     */
    public function prescriptions(): BelongsToMany
    {
        return $this->belongsToMany(Prescription::class, 'medicament_prescription')
            ->withPivot(['posologie', 'duree', 'instructions', 'quantite'])
            ->withTimestamps();
    }

    /**
     * Récupère le nom complet du médicament avec son dosage
     */
    public function getNomCompletAttribute(): string
    {
        return trim(sprintf(
            '%s %s %s',
            $this->nom,
            $this->dosage,
            $this->unite
        ));
    }

    /**
     * Scope pour les médicaments actifs
     */
    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }

    /**
     * Scope pour les médicaments sur ordonnance
     */
    public function scopeSurOrdonnance($query, $value = true)
    {
        return $query->where('sur_ordonnance', $value);
    }

    /**
     * Recherche un médicament par son nom ou son code CIP
     */
    public function scopeRechercher($query, $terme)
    {
        return $query->where('nom', 'LIKE', "%{$terme}%")
            ->orWhere('code_cip', 'LIKE', "%{$terme}%")
            ->orWhere('code_cip13', 'LIKE', "%{$terme}%");
    }
}
