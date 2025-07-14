<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $appointments = Appointment::where('user_id', $user->id)
            ->whereIn('statut', ['valide', 'en_attente'])
            ->orderBy('date')
            ->get();
        $notifications = $user->notifications()->where('type', 'App\\Notifications\\AppointmentStatusNotification')->latest()->take(10)->get();
        return view('patient.consultations.index', compact('appointments', 'notifications'));
    }

    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Vérifie que la consultation appartient bien au patient connecté
        if ($consultation->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('patient.consultations.show', compact('consultation'));
    }

    public function enligne(Appointment $appointment)
    {
        $user = auth()->user();
        if ($appointment->user_id !== $user->id) {
            abort(403);
        }
        return view('patient.consultations.enligne', compact('appointment'));
    }
}
