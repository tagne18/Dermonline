<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Prescription extends Model
{
    protected $table = 'prescriptions';
    
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'titre',
        'description',
        'fichier',
        'fichier_pdf',
        'date_prescription',
        'date_emission',
        'commentaires',
        'statut',
    ];

    protected $dates = [
        'date_prescription',
        'date_emission',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['date_formattee'];

    public function getDateFormatteeAttribute()
    {
        return $this->date_emission ? $this->date_emission->format('d/m/Y') : null;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id')->withDefault([
            'nom_complet' => 'Patient inconnu',
            'date_naissance' => null,
        ]);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id')->withDefault([
            'nom_complet' => 'Médecin inconnu',
            'specialite' => 'Non spécifiée',
            'adresse' => 'Non spécifiée',
            'telephone' => 'Non spécifié',
        ]);
    }

    public function medicaments(): BelongsToMany
    {
        return $this->belongsToMany(Medicament::class, 'medicament_prescription')
            ->withPivot(['posologie', 'duree', 'instructions'])
            ->withTimestamps();
    }

    /**
     * Relation avec les fichiers joints à la prescription
     */
    public function fichiers()
    {
        return $this->hasMany(FichierJoint::class, 'prescription_id');
    }

    public function getCheminPdfAttribute(): ?string
    {
        return $this->fichier_pdf ? storage_path('app/public/' . $this->fichier_pdf) : null;
    }

    public function getUrlPdfAttribute(): ?string
    {
        return $this->fichier_pdf ? asset('storage/' . $this->fichier_pdf) : null;
    }

    public function scopePourPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeDateBetween($query, $debut, $fin = null)
    {
        if (!$fin) {
            $fin = now();
        }
        
        return $query->whereBetween('date_emission', [
            Carbon::parse($debut)->startOfDay(),
            Carbon::parse($fin)->endOfDay()
        ]);
    }

    /**
     * Supprime les fichiers joints lors de la suppression de l'ordonnance
     */
    protected static function booted()
    {
        static::deleting(function ($prescription) {
            // Supprimer les fichiers joints
            foreach ($prescription->fichiers as $fichier) {
                // Supprimer le fichier physique
                if (Storage::disk('public')->exists($fichier->chemin)) {
                    Storage::disk('public')->delete($fichier->chemin);
                }
                // Supprimer l'entrée en base de données
                $fichier->delete();
            }
            
            // Supprimer le fichier PDF s'il existe
            if ($prescription->fichier_pdf && Storage::disk('public')->exists($prescription->fichier_pdf)) {
                Storage::disk('public')->delete($prescription->fichier_pdf);
            }
        });
    }
}
