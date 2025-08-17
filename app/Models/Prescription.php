<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'titre',
        'description',
        'fichier',
        'date_prescription',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }
}
