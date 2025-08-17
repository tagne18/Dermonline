<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $medecin = auth()->user();
        
        // Statistiques principales
        $patientCount = $medecin->abonnes()->count();
        $consultationCount = $medecin->medecinConsultations()->count();
        $pendingRdvCount = $medecin->medecinRendezVous()
            ->where('statut', 'en_attente')
            ->whereDate('date_rdv', '>=', now())
            ->count();
            
        // Revenus du mois (à adapter selon votre logique de facturation)
        $monthlyRevenue = $medecin->medecinPaiements()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('montant');
            
        $consultationMonthCount = $medecin->medecinConsultations()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Données pour le graphique (30 derniers jours)
        $chartData = $this->getConsultationChartData($medecin->id);
        
        // Prochains rendez-vous (les 5 prochains)
        $upcomingAppointments = $medecin->medecinRendezVous()
            ->with('patient')
            ->where('statut', '!=' , 'annule')
            ->whereDate('date_rdv', '>=', now())
            ->orderBy('date_rdv')
            ->orderBy('heure_rdv')
            ->take(5)
            ->get();
            
        // Dernières notifications
        $notifications = $medecin->userNotifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Activité récente (exemple avec les consultations)
        $recentActivities = $medecin->medecinConsultations()
            ->with('patient')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($consultation) {
                return [
                    'title' => 'Nouvelle consultation',
                    'description' => 'Avec ' . ($consultation->patient->name ?? 'un patient'),
                    'time' => $consultation->created_at->diffForHumans(),
                    'icon' => 'fa-stethoscope'
                ];
            });

        return view('medecin.dashboard', [
            'patient' => (object)['nom' => $patientCount],
            'consultationCount' => $consultationCount,
            'pendingRdvCount' => $pendingRdvCount,
            'monthlyRevenue' => $monthlyRevenue,
            'consultationMonthCount' => $consultationMonthCount,
            'chartData' => $chartData,
            'upcomingAppointments' => $upcomingAppointments,
            'notifications' => $notifications,
            'recentActivities' => $recentActivities
        ]);
    }
    
    /**
     * Génère les données pour le graphique des consultations
     */
    private function getConsultationChartData($medecinId)
    {
        $endDate = now();
        $startDate = now()->subDays(29);
        
        $dates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dates[$currentDate->format('Y-m-d')] = 0;
            $currentDate->addDay();
        }
        
        // Récupération des consultations groupées par jour
        $consultations = Consultation::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('medecin_id', $medecinId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();
        
        // Fusion avec les dates vides
        $consultations = array_merge($dates, $consultations);
        
        // Tri par date
        ksort($consultations);
        
        return [
            'labels' => array_map(function($date) {
                return Carbon::parse($date)->format('d M');
            }, array_keys($consultations)),
            'data' => array_values($consultations)
        ];
    }
}
