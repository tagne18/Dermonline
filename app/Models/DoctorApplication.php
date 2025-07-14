<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cv',
        'status',
        'specialite',
        'ville',
        'langue',
        'lieu_travail',
        'matricule_professionnel',
        'numero_licence',
        'experience',
        'expertise',
    ];
}
