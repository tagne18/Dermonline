<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    protected $table = 'dossier_medicaux';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'consultation_id',
        'titre',
        'description',
        'type_document',
        'fichier',
        'nom_fichier',
        'taille_fichier',
        'mime_type',
        'statut',
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
     * Relation avec la consultation
     */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Scope pour les dossiers d'un médecin
     */
    public function scopeForMedecin($query, $medecinId)
    {
        return $query->where('medecin_id', $medecinId);
    }

    /**
     * Scope pour les dossiers d'un patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope pour les dossiers par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }

    /**
     * Obtenir l'URL du fichier
     */
    public function getFichierUrlAttribute()
    {
        if ($this->fichier) {
            return asset('storage/' . $this->fichier);
        }
        return null;
    }

    /**
     * Obtenir la taille formatée du fichier
     */
    public function getTailleFormateeAttribute()
    {
        if (!$this->taille_fichier) {
            return '0 B';
        }

        $bytes = $this->taille_fichier;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
