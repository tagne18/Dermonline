<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Paiement;
use App\Models\Notification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Les relations existantes sont conservées, nous n'ajoutons que celles qui n'existent pas
    
    /**
     * Relation avec les rendez-vous en tant que médecin
     */
    public function medecinRendezVous()
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }
    
    /**
     * Relation avec les rendez-vous en tant que patient
     */
    public function patientRendezVous()
    {
        return $this->hasMany(RendezVous::class, 'patient_id');
    }

    /**
     * Relation avec les consultations en tant que médecin
     */
    public function medecinConsultations()
    {
        return $this->hasMany(Consultation::class, 'medecin_id');
    }

    /**
     * Relation avec les paiements reçus (pour les médecins)
     */
    public function medecinPaiements()
    {
        return $this->hasMany(Paiement::class, 'medecin_id');
    }

    /**
     * Relation avec les notifications
     */
    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'profession',
        'city',
        'gender',
        'role',
        'is_blocked',
        'blocked_at',
        'specialite',
        'ville',
        'lieu_travail',
        'langue',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // Si le chemin commence par http, c'est déjà une URL complète
            if (str_starts_with($this->profile_photo_path, 'http')) {
                return $this->profile_photo_path;
            }
            
            // Vérifier si le fichier existe dans le stockage
            if (Storage::disk($this->profilePhotoDisk())->exists($this->profile_photo_path)) {
                return Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path);
            }
            
            // Si le fichier n'existe pas, retourner une image par défaut
            return $this->defaultProfilePhotoUrl();
        }
        
        // Si aucune photo de profil n'est définie, utiliser l'avatar par défaut
        return $this->defaultProfilePhotoUrl();
    }
    
    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));
        
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
    
    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return 'public';
    }
    
    /**
     * Relation pour les médecins : obtenir tous les abonnements de patients
     */
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'medecin_id');
    }

    /**
     * Relation pour les patients : obtenir leur abonnement
     */
    public function abonnement()
    {
        return $this->hasOne(Abonnement::class, 'user_id');
    }
    
    /**
     * Relation pour les médecins : obtenir les patients abonnés
     * Cette méthode permet de récupérer tous les patients qui sont abonnés à ce médecin
     */
    public function abonnes()
    {
        return $this->belongsToMany(User::class, 'abonnements', 'medecin_id', 'user_id')
            ->wherePivot('statut', 'actif')
            ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Consultations où l'utilisateur est le patient
     */
    public function consultationsAsPatient()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }

    /**
     * Consultations où l'utilisateur est le médecin
     */
    public function consultationsAsMedecin()
    {
        return $this->hasMany(Consultation::class, 'medecin_id');
    }

    /**
     * Dossiers médicaux où l'utilisateur est le patient
     */
    public function dossiersAsPatient()
    {
        return $this->hasMany(DossierMedical::class, 'patient_id');
    }

    /**
     * Dossiers médicaux où l'utilisateur est le médecin
     */
    public function dossiersAsMedecin()
    {
        return $this->hasMany(DossierMedical::class, 'medecin_id');
    }

    /**
     * Notifications de l'utilisateur
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Notifications non lues
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    public function redirectToDashboard()
    {
        return match ($this->role) {
            'admin' => route('admin.dashboard'),
            'medecin' => route('medecin.dashboard'),
            default => route('patient.dashboard'),
        };
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur a l'un des rôles spécifiés
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }
}
