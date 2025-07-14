<?php

namespace App\Http\Controllers\Patient;
use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    public function index()
    {
        $medecins = User::where('role', 'medecin')
            ->where('is_blocked', false)
            ->orderBy('name')
            ->get();
        
        // Récupérer toutes les spécialités uniques (avec valeur par défaut si nécessaire)
        $specialites = $medecins->pluck('specialite')
            ->filter()
            ->unique()
            ->sort()
            ->values();
        
        // Si aucune spécialité n'est définie, ajouter une valeur par défaut
        if ($specialites->isEmpty()) {
            $specialites = collect(['Dermatologie']);
        }
        
        return view('patient.appointments.index', compact('medecins', 'specialites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string',
            'patient_phone' => 'required|string',
            'doctor' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'custom_time' => 'nullable|date_format:H:i',
            'consultation_reason' => 'required|string',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120',
            'type' => 'required|in:en_ligne,presentiel',
        ]);

        $user = auth()->user();
        
        // Gérer l'heure (prédéfinie ou personnalisée)
        $heure = $request->appointment_time === 'custom' ? $request->custom_time : $request->appointment_time;
        
        $data = [
            'user_id' => $user->id,
            'medecin_id' => $request->doctor,
            'date' => $request->appointment_date,
            'heure' => $heure,
            'statut' => 'en_attente',
            'type' => $request->type,
            'motif' => $request->consultation_reason,
            'description' => $request->description,
            'patient_name' => $request->patient_name,
            'patient_phone' => $request->patient_phone,
        ];

        // Gestion des photos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('appointment-photos', 'public');
            }
            $data['photos'] = json_encode($photos);
        }

        \App\Models\Appointment::create($data);

        // (Optionnel) Notifier le médecin ici

        return redirect()->route('patient.appointments.index')->with('success', 'Votre demande de rendez-vous a été envoyée au médecin.');
    }
}
