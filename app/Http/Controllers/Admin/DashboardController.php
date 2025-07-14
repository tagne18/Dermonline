<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\Appointment;
use App\Models\CommunityMessage;

class DashboardController extends Controller
{
    public function index()
    {
        // Patients abonnés (utilisateurs avec rôle patient qui ont un abonnement actif)
        $patientsAbonnes = User::where('role', 'patient')
            ->whereHas('abonnement', function($query) {
                $query->where('statut', 'actif');
            })
            ->count();

        // Patients bloqués
        $patientsBloques = User::where('role', 'patient')
            ->where('is_blocked', true)
            ->count();

        // Médecins actifs (utilisateurs avec rôle medecin)
        $medecinsActifs = User::where('role', 'medecin')->count();

        // Rendez-vous en attente
        $rendezVousEnAttente = Appointment::where('statut', 'en_attente')->count();

        // Consultations effectuées (rendez-vous avec statut terminé)
        $consultationsEffectuees = Appointment::where('statut', 'termine')->count();

        // Dernières notifications (messages de la communauté)
        $notifications = CommunityMessage::latest()->take(5)->get();

        // Liste de tous les patients
        $patients = User::where('role', 'patient')->get();
        
        // Nombre total de patients inscrits
        $patientsInscrits = User::where('role', 'patient')->count();

        return view('admin.dashboard', compact(
            'patientsAbonnes',
            'patientsBloques', 
            'medecinsActifs',
            'rendezVousEnAttente',
            'consultationsEffectuees',
            'notifications',
            'patients',
            'patientsInscrits'
        ));
    }
}
