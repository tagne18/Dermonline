<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index()
    {
        // Récupérer les annonces des médecins avec leurs informations utilisateur
        $annoncesMedecins = \App\Models\NewAnnonce::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'medecin');
            })
            ->latest()
            ->get()
            ->map(function($annonce) {
                $annonce->type = 'medecin';
                return $annonce;
            });

        // Récupérer les annonces de l'administrateur
        $annoncesAdmin = Annonce::latest()->get()
            ->map(function($annonce) {
                $annonce->type = 'admin';
                return $annonce;
            });

        // Fusionner et trier par date de création
        $annonces = $annoncesMedecins->merge($annoncesAdmin)
            ->sortByDesc('created_at');

        return view('patient.annonces.index', compact('annonces'));
    }
}
