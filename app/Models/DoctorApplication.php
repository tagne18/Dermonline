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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'langue' => 'string',
    ];

    /**
     * Get the langue attribute.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getLangueAttribute($value)
    {
        // S'assurer que la valeur est dans la liste des valeurs autorisées
        return in_array($value, ['fr', 'en', 'both']) ? $value : 'fr';
    }
}
