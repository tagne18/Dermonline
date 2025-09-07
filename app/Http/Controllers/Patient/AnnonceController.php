<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\NewAnnonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les annonces des médecins (NewAnnonce) avec leurs informations utilisateur
        $annoncesMedecins = NewAnnonce::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'medecin')
                      ->whereNull('blocked_at'); // Exclure les médecins bloqués
            })
            ->where('statut', 'publie') // Seulement les annonces publiées
            ->latest('date_publication')
            ->get()
            ->map(function($annonce) {
                $annonce->type = 'medecin';
                $annonce->image = $annonce->image_path; // Normaliser le nom de l'attribut
                $annonce->created_at = $annonce->date_publication ?? $annonce->created_at;
                return $annonce;
            });

        // Récupérer les annonces de l'administrateur (Annonce)
        $annoncesAdmin = Annonce::latest()
            ->get()
            ->map(function($annonce) {
                $annonce->type = 'admin';
                return $annonce;
            });

        // Fusionner et trier par date de création
        $annonces = $annoncesMedecins->concat($annoncesAdmin)
            ->sortByDesc('created_at');

        // Filtrage par type si demandé
        if ($request->has('type') && $request->type !== 'tous') {
            $annonces = $annonces->filter(function($annonce) use ($request) {
                return $annonce->type === $request->type;
            });
        }

        // Recherche par mot-clé
        if ($request->has('recherche') && !empty($request->recherche)) {
            $searchTerm = strtolower($request->recherche);
            $annonces = $annonces->filter(function($annonce) use ($searchTerm) {
                return str_contains(strtolower($annonce->titre), $searchTerm) ||
                       str_contains(strtolower(strip_tags($annonce->contenu)), $searchTerm) ||
                       ($annonce->user && str_contains(strtolower($annonce->user->name), $searchTerm));
            });
        }

        // Pagination manuelle
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $total = $annonces->count();
        $annonces = $annonces->slice($offset, $perPage)->values();

        // Statistiques pour le dashboard
        $stats = [
            'total' => $total,
            'medecins' => $annoncesMedecins->count(),
            'admin' => $annoncesAdmin->count(),
        ];

        return view('patient.annonces.index', compact('annonces', 'stats'));
    }

    /**
     * Afficher une annonce spécifique
     */
    public function show($id, $type)
    {
        if ($type === 'medecin') {
            $annonce = NewAnnonce::with('user')
                ->where('id', $id)
                ->where('statut', 'publie')
                ->whereHas('user', function($query) {
                    $query->where('role', 'medecin')
                          ->whereNull('blocked_at');
                })
                ->firstOrFail();
            
            $annonce->type = 'medecin';
            $annonce->image = $annonce->image_path;
        } else {
            $annonce = Annonce::findOrFail($id);
            $annonce->type = 'admin';
        }

        return view('patient.annonces.show', compact('annonce'));
    }
}
