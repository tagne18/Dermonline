<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FichierJoint extends Model
{
    protected $fillable = [
        'prescription_id',
        'nom_original',
        'chemin',
        'taille',
        'mime_type',
        'extension'
    ];

    protected $appends = ['url', 'taille_formatee'];

    /**
     * Relation avec la prescription
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * URL complète du fichier
     */
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->chemin);
    }

    /**
     * Taille formatée du fichier
     */
    public function getTailleFormateeAttribute()
    {
        $bytes = $this->taille;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' octets';
        } elseif ($bytes == 1) {
            return '1 octet';
        } else {
            return '0 octet';
        }
    }

    /**
     * Supprime le fichier physique lors de la suppression du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($fichier) {
            if (Storage::disk('public')->exists($fichier->chemin)) {
                Storage::disk('public')->delete($fichier->chemin);
            }
        });
    }
}
