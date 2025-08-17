<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusNotification;

class AnnonceController extends Controller
{
    public function index()
    {
        $medecin = auth()->user();
        $appointments = Appointment::where('medecin_id', $medecin->id)
            ->with('user') // Charger les données du patient
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Debug: afficher le nombre de rendez-vous trouvés
        \Log::info('Médecin ID: ' . $medecin->id . ', Rendez-vous trouvés: ' . $appointments->count());
        
        return view('medecin.annonces.index', compact('appointments'));
    }

    public function create()
    {
        return view('medecin.annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        Annonce::create([
            'user_id' => auth()->id(),
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'public' => true,
        ]);

        return redirect()->route('medecin.annonces.index')->with('success', 'Annonce publiée.');
    }

    public function edit(Annonce $annonce)
    {
        return view('medecin.annonces.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        $annonce->update($request->only('titre', 'contenu'));

        return back()->with('success', 'Annonce mise à jour.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }

    public function validateRdv($id)
    {
        $rdv = Appointment::findOrFail($id);
        $rdv->statut = 'valide';
        $rdv->save();
        // Notifier le patient
        $rdv->patient->notify(new AppointmentStatusNotification($rdv, 'validé'));
        return back()->with('success', 'Rendez-vous validé. Le patient a été notifié.');
    }

    public function refuseRdv($id)
    {
        $rdv = Appointment::findOrFail($id);
        $rdv->statut = 'refuse';
        $rdv->save();
        // Notifier le patient
        $rdv->patient->notify(new AppointmentStatusNotification($rdv, 'refusé'));
        return back()->with('success', 'Rendez-vous refusé. Le patient a été notifié.');
    }

    /**
     * Reprogrammer un rendez-vous (proposer une nouvelle date/heure au patient)
     */
    public function rescheduleRdv(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'new_date' => 'required|date|after:today',
            'new_time' => 'required',
        ]);
        $rdv = Appointment::findOrFail($request->appointment_id);
        $rdv->proposed_date = $request->new_date;
        $rdv->proposed_time = $request->new_time;
        $rdv->statut = 'reprogramme'; // statut personnalisé
        $rdv->save();
        // Notifier le patient (notification personnalisée à créer si besoin)
        if ($rdv->patient) {
            $rdv->patient->notify(new AppointmentStatusNotification($rdv, 'proposé'));
        }
        return back()->with('success', 'Proposition de reprogrammation envoyée au patient. Il doit valider ou refuser.');
    }
}

