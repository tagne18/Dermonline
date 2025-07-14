<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medecin_id',
        'date',
        'heure',
        'statut',
        'type',
        'motif',
        'description',
        'patient_name',
        'patient_phone',
        'photos',
        // autres colonnes selon ta base
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
