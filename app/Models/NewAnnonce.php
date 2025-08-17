<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewAnnonce extends Model
{
    protected $fillable = [
        'user_id',
        'titre',
        'contenu',
        'image_path',
        'statut',
        'date_publication',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_PUBLIE = 'publie';
    public const STATUT_ARCHIVE = 'archive';

    /**
     * Relation avec l'utilisateur (médecin) qui a créé l'annonce
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    /**
     * Vérifie si l'annonce est publiée
     */
    public function estPubliee()
    {
        return $this->statut === self::STATUT_PUBLIE;
    }

    /**
     * Vérifie si l'annonce est un brouillon
     */
    public function estBrouillon()
    {
        return $this->statut === self::STATUT_BROUILLON;
    }

    /**
     * Publier l'annonce
     */
    public function publier()
    {
        $this->update([
            'statut' => self::STATUT_PUBLIE,
            'date_publication' => $this->date_publication ?? now(),
        ]);
    }

    /**
     * Mettre en brouillon l'annonce
     */
    public function mettreEnBrouillon()
    {
        $this->update(['statut' => self::STATUT_BROUILLON]);
    }

    /**
     * Archiver l'annonce
     */
    public function archiver()
    {
        $this->update(['statut' => self::STATUT_ARCHIVE]);
    }

    /**
     * Scope pour les annonces publiées
     */
    public function scopePubliees($query)
    {
        return $query->where('statut', self::STATUT_PUBLIE);
    }

    /**
     * Scope pour les brouillons
     */
    public function scopeBrouillons($query)
    {
        return $query->where('statut', self::STATUT_BROUILLON);
    }

    /**
     * Scope pour les annonces archivées
     */
    public function scopeArchivees($query)
    {
        return $query->where('statut', self::STATUT_ARCHIVE);
    }

    /**
     * Supprime le fichier image associé lors de la suppression du modèle
     */
    protected static function booted()
    {
        static::deleting(function ($annonce) {
            if ($annonce->image_path) {
                Storage::disk('public')->delete($annonce->image_path);
            }
        });
    }
}
