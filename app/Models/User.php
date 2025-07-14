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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function adminProfile()
    {
        return $this->hasOne(Admin::class);
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

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
            : asset('images/default.jpeg');
    }
    
    public function abonnement()
    {
        return $this->hasOne(Abonnement::class);
    }

    /**
     * Relation pour les médecins : obtenir tous les patients abonnés
     */
    public function abonnes()
    {
        return $this->hasMany(Abonnement::class, 'medecin_id');
    }

    /**
     * Relation pour les patients : obtenir leur abonnement
     */
    public function patientAbonnement()
    {
        return $this->hasOne(Abonnement::class, 'user_id');
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
