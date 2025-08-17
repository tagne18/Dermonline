<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $fillable = [
        'medecin_id',
        'patient_id',
        'consultation_id',
        'montant',
        'devise',
        'methode_paiement',
        'reference',
        'statut',
        'date_paiement',
        'date_validation',
        'details',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime',
    ];

    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Relation avec le patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relation avec la consultation
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Marquer le paiement comme payé
     */
    public function marquerPaye()
    {
        $this->update([
            'statut' => 'paye',
            'date_validation' => now(),
        ]);
    }

    /**
     * Récupérer le montant formaté
     */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' ' . strtoupper($this->devise);
    }
}
