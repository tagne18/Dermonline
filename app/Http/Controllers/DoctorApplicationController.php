<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorApplication;
use Illuminate\Support\Facades\Storage;

class DoctorApplicationController extends Controller
{
    public function create()
    {
        return view('doctor-application.create');
    }

    public function store(Request $request)
    {
        // Validation complète
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:doctor_applications,email',
            'phone' => 'nullable|string|max:20',
            'cv' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'specialite' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'langue' => 'required|in:fr,en,both',
            'lieu_travail' => 'nullable|string|max:255',
            'matricule_professionnel' => 'nullable|string|max:255',
            'numero_licence' => 'nullable|string|max:255',
            'experience' => 'required|string|max:255',
            'expertise' => 'nullable|string|max:1000',
        ]);

        // Stockage du fichier CV
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $validated['cv'] = $cvPath;
        }

        // Création de l'enregistrement
        DoctorApplication::create($validated);

        return redirect()->route('home')->with('success', 'Votre demande a été soumise avec succès. Nous vous contacterons sous 48h.');
    }
}
