<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\Appointment;
use App\Models\CommunityMessage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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

    /**
     * Récupère les statistiques pour le tableau de bord (API)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            // Utilisation du cache pour optimiser les performances
            $stats = Cache::remember('admin_dashboard_stats', now()->addMinutes(5), function () {
                return [
                    'patientsInscrits' => User::where('role', 'patient')->count(),
                    'patientsBloques' => User::where('role', 'patient')->where('is_blocked', true)->count(),
                    'medecinsActifs' => User::where('role', 'medecin')->count(),
                    'rendezVousEnAttente' => Appointment::where('statut', 'en_attente')->count(),
                    'consultationsEffectuees' => Appointment::where('statut', 'termine')->count(),
                    'patientsAbonnes' => User::where('role', 'patient')
                        ->whereHas('abonnement', function($query) {
                            $query->where('statut', 'actif');
                        })->count(),
                    'updated_at' => now()->toDateTimeString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les notifications récentes (API)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications()
    {
        try {
            $notifications = Cache::remember('admin_notifications', now()->addMinutes(5), function () {
                return CommunityMessage::with('user')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function($notif) {
                        return [
                            'id' => $notif->id,
                            'user_name' => $notif->user ? $notif->user->name : 'Utilisateur inconnu',
                            'content' => $notif->content,
                            'date' => $notif->created_at->format('d/m/Y'),
                            'time' => $notif->created_at->format('H:i'),
                            'avatar' => $notif->user ? ($notif->user->photo ? asset('storage/' . $notif->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($notif->user->name)) : null
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère la liste des patients (API)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatients()
    {
        try {
            $patients = Cache::remember('admin_patients_list', now()->addMinutes(10), function () {
                return User::where('role', 'patient')
                    ->with('abonnement')
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function($patient) {
                        return [
                            'id' => $patient->id,
                            'name' => $patient->name,
                            'email' => $patient->email,
                            'avatar' => $patient->photo ? asset('storage/' . $patient->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($patient->name),
                            'abonnement' => $patient->abonnement ? [
                                'statut' => $patient->abonnement->statut,
                                'libelle' => $patient->abonnement->statut === 'actif' ? 'Abonné' : 'Non abonné',
                                'date_fin' => $patient->abonnement->date_fin ? $patient->abonnement->date_fin->format('d/m/Y') : null
                            ] : null,
                            'created_at' => $patient->created_at->format('d/m/Y')
                        ];
                    });
            });

            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la liste des patients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rafraîchit les données du cache
     *
     * @param string $key
     * @return void
     */
    protected function refreshCache($key)
    {
        Cache::forget($key);
    }
}
