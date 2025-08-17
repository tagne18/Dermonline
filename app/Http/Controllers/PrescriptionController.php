<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrescriptionController extends Controller
{
    // Liste des ordonnances du patient connecté
    public function index()
    {
        $user = Auth::user();
        $prescriptions = Prescription::where('patient_id', $user->id)->orderBy('date_prescription', 'desc')->get();
        return view('patient.ordonnances.index', compact('prescriptions'));
    }

    // Détail d'une ordonnance
    public function show($id)
    {
        $user = Auth::user();
        $prescription = Prescription::where('id', $id)->where('patient_id', $user->id)->firstOrFail();
        return view('patient.ordonnances.show', compact('prescription'));
    }

    // Téléchargement du fichier
    public function download($id)
    {
        $user = Auth::user();
        $prescription = Prescription::where('id', $id)->where('patient_id', $user->id)->firstOrFail();
        if ($prescription->fichier && Storage::disk('public')->exists($prescription->fichier)) {
            return Storage::disk('public')->download($prescription->fichier);
        }
        abort(404, 'Fichier non trouvé');
    }
}
