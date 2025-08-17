<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'titre',
        'description',
        'fichier',
        'date_examen',
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
