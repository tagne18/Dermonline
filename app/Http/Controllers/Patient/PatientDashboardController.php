<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Notification;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $appointments = Appointment::where('user_id', $user->id)
            ->whereIn('statut', ['valide', 'en_attente'])
            ->orderBy('date')
            ->get();
            
        $consultations = Consultation::where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $notifications = $user->notifications()
            ->where('type', 'App\\Notifications\\AppointmentStatusNotification')
            ->latest()
            ->take(5)
            ->get();
        
        return view('patient.dashboard', compact('appointments', 'consultations', 'notifications'));
    }
}
