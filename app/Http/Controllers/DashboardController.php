<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupération des données principales
        $patientCount = Patient::count();
        $todayRdvCount = Appointment::whereDate('appointment_date', today())->count();
        $pendingRdvCount = Appointment::where('status', 'pending')->count();
        $consultationCount = Consultation::count();
        
        // Calcul des revenus du mois
        $revenue = Consultation::whereMonth('created_at', now()->month)
            ->sum('amount') ?? 0;
            
        // Prochains rendez-vous (5 prochains)
        $upcomingAppointments = Appointment::with('patient')
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->take(5)
            ->get()
            ->map(function($appointment) {
                return [
                    'patient_name' => $appointment->patient->name ?? 'Patient inconnu',
                    'time' => Carbon::parse($appointment->appointment_date)->format('H:i'),
                    'type' => $appointment->type ?? 'Consultation',
                    'priority' => $appointment->priority ?? 'medium'
                ];
            });
            
        // Dernières notifications (5 plus récentes)
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function($notification) {
                return [
                    'message' => $notification->data['message'] ?? 'Nouvelle notification',
                    'created_at' => $notification->created_at->diffForHumans()
                ];
            });
            
        // Patients récents (5 derniers)
        $recentPatients = Patient::latest()
            ->take(5)
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'age' => $patient->birth_date ? Carbon::parse($patient->birth_date)->age : 'N/A',
                    'last_visit' => $patient->last_visit ? Carbon::parse($patient->last_visit)->diffForHumans() : 'Jamais',
                    'status' => $patient->status ?? 'active'
                ];
            });
            
        // Données pour les graphiques
        $consultationData = $this->getConsultationChartData();
        $revenueData = $this->getRevenueChartData();
        $consultationTypeData = $this->getConsultationTypeData();
        $patientDemographics = $this->getPatientDemographics();
        $weeklyAppointments = $this->getWeeklyAppointments();
        $prescriptionData = $this->getPrescriptionData();
        
        return view('medecin.dashboard', [
            'patientCount' => $patientCount,
            'todayRdvCount' => $todayRdvCount,
            'pendingRdvCount' => $pendingRdvCount,
            'consultationCount' => $consultationCount,
            'revenue' => $revenue,
            'upcomingAppointments' => $upcomingAppointments,
            'notifications' => $notifications,
            'recentPatients' => $recentPatients,
            'chartData' => [
                'consultation' => $consultationData,
                'revenue' => $revenueData,
                'consultationTypes' => $consultationTypeData,
                'demographics' => $patientDemographics,
                'weeklyAppointments' => $weeklyAppointments,
                'prescriptions' => $prescriptionData
            ]
        ]);
    }
    
    protected function getConsultationChartData()
    {
        $data = [];
        $days = 7;
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Consultation::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data['labels'][] = $date->format('D');
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    protected function getRevenueChartData()
    {
        $data = [];
        $months = 6;
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Consultation::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
                
            $data['labels'][] = $date->format('M Y');
            $data['data'][] = $revenue;
        }
        
        return $data;
    }
    
    protected function getConsultationTypeData()
    {
        $types = ['Générale', 'Urgence', 'Suivi', 'Spécialisée'];
        $data = [];
        
        foreach ($types as $type) {
            $count = Consultation::where('type', $type)->count();
            $data['labels'][] = $type;
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    protected function getPatientDemographics()
    {
        // Répartition par âge
        $ageGroups = [
            '0-18' => [0, 18],
            '19-35' => [19, 35],
            '36-50' => [36, 50],
            '51-65' => [51, 65],
            '65+' => [65, 150]
        ];
        
        $ageData = [];
        
        foreach ($ageGroups as $label => $range) {
            $count = Patient::whereHas('user', function($query) use ($range) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN ? AND ?", 
                    [$range[0], $range[1] ?? 150]);
            })->count();
            
            $ageData['labels'][] = $label;
            $ageData['data'][] = $count;
        }
        
        // Répartition par genre
        $genderData = [
            'labels' => ['Hommes', 'Femmes'],
            'data' => [
                Patient::where('gender', 'male')->count(),
                Patient::where('gender', 'female')->count()
            ]
        ];
        
        return [
            'age' => $ageData,
            'gender' => $genderData
        ];
    }
    
    protected function getWeeklyAppointments()
    {
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $data = [];
        
        foreach ($days as $day) {
            $count = rand(5, 15); // À remplacer par une vraie requête
            $data['labels'][] = $day;
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    protected function getPrescriptionData()
    {
        $categories = ['Antibiotiques', 'Antalgiques', 'Vitamines', 'Antihypertenseurs', 'Autres'];
        $data = [];
        
        foreach ($categories as $category) {
            $count = rand(5, 30); // À remplacer par une vraie requête
            $data['labels'][] = $category;
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    // Méthode pour l'API de rafraîchissement en temps réel
    /**
     * Récupère les données pour le rafraîchissement du tableau de bord
     */
    public function getDashboardData()
    {
        return response()->json([
            'patientCount' => Patient::count(),
            'todayRdvCount' => Appointment::whereDate('appointment_date', today())->count(),
            'pendingRdvCount' => Appointment::where('status', 'pending')->count(),
            'consultationCount' => Consultation::count(),
            'revenue' => Consultation::whereMonth('created_at', now()->month)->sum('amount') ?? 0,
            'recentPatients' => Patient::latest()->take(5)->get()->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'age' => $patient->birth_date ? Carbon::parse($patient->birth_date)->age : 'N/A',
                    'last_visit' => $patient->last_visit ? Carbon::parse($patient->last_visit)->diffForHumans() : 'Jamais',
                    'status' => $patient->status ?? 'active'
                ];
            })
        ]);
    }
    
    /**
     * Met à jour les données en fonction de la période sélectionnée
     */
    public function updatePeriod(Request $request)
    {
        $period = $request->query('period', 'week');
        $now = now();
        
        $data = [];
        
        // Données des consultations selon la période
        switch ($period) {
            case 'today':
                $data['consultation'] = $this->getDailyConsultationData($now);
                break;
                
            case 'week':
                $data['consultation'] = $this->getWeeklyConsultationData($now);
                break;
                
            case 'month':
                $data['consultation'] = $this->getMonthlyConsultationData($now);
                break;
                
            case 'year':
                $data['consultation'] = $this->getYearlyConsultationData($now);
                break;
        }
        
        // Statistiques mises à jour
        $data['stats'] = [
            'patientCount' => Patient::count(),
            'todayRdvCount' => Appointment::whereDate('appointment_date', today())->count(),
            'pendingRdvCount' => Appointment::where('status', 'pending')->count(),
            'consultationCount' => Consultation::count(),
            'revenue' => Consultation::whereMonth('created_at', $now->month)->sum('amount') ?? 0
        ];
        
        return response()->json($data);
    }
    
    /**
     * Récupère les données quotidiennes des consultations
     */
    protected function getDailyConsultationData($date)
    {
        $data = [];
        
        // 24 heures
        for ($i = 0; $i < 24; $i++) {
            $hour = $date->copy()->setHour($i)->startOfHour();
            $nextHour = $hour->copy()->addHour();
            
            $count = Consultation::whereBetween('created_at', [$hour, $nextHour])
                ->count();
                
            $data['labels'][] = $hour->format('H:00');
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    /**
     * Récupère les données hebdomadaires des consultations
     */
    protected function getWeeklyConsultationData($date)
    {
        $data = [];
        $startOfWeek = $date->copy()->startOfWeek();
        
        // 7 jours de la semaine
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $nextDay = $day->copy()->addDay();
            
            $count = Consultation::whereBetween('created_at', [$day, $nextDay])
                ->count();
                
            $data['labels'][] = $day->isoFormat('ddd');
            $data['data'][] = $count;
        }
        
        return $data;
    }
    
    /**
     * Récupère les données mensuelles des consultations
     */
    protected function getMonthlyConsultationData($date)
    {
        $data = [];
        $daysInMonth = $date->daysInMonth;
        
        // Jours du mois
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = $date->copy()->day($i);
            $nextDay = $day->copy()->addDay();
            
            $count = Consultation::whereBetween('created_at', [$day, $nextDay])
                ->count();
                
            if ($i % 5 === 0 || $i === 1 || $i === $daysInMonth) {
                $data['labels'][] = $day->format('j M');
                $data['data'][] = $count;
            } else {
                $data['labels'][] = '';
                $data['data'][] = $count;
            }
        }
        
        return $data;
    }
    
    /**
     * Récupère les données annuelles des consultations
     */
    protected function getYearlyConsultationData($date)
    {
        $data = [];
        $startOfYear = $date->copy()->startOfYear();
        
        // 12 mois de l'année
        for ($i = 0; $i < 12; $i++) {
            $month = $startOfYear->copy()->addMonths($i);
            $nextMonth = $month->copy()->addMonth();
            
            $count = Consultation::whereBetween('created_at', [$month, $nextMonth])
                ->count();
                
            $data['labels'][] = $month->isoFormat('MMM');
            $data['data'][] = $count;
        }
        
        return $data;
    }
}
