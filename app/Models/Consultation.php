<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date_consultation',
        'symptomes',
        'diagnostic',
        'prescription',
        'notes',
        'statut',
        'prix',
    ];

    protected $casts = [
        'date_consultation' => 'datetime',
        'prix' => 'decimal:2',
    ];

    /**
     * Relation avec le patient
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Scope pour les consultations d'un médecin
     */
    public function scopeForMedecin($query, $medecinId)
    {
        return $query->where('medecin_id', $medecinId);
    }

    /**
     * Scope pour les consultations d'un patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope pour les consultations par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }
}
