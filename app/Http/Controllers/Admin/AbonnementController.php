<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbonnementController extends Controller
{
    public function index(Request $request)
    {
        $query = Abonnement::with(['patient', 'medecin'])
            ->latest('date_debut');

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par type d'abonnement
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_fin', '<=', $request->date_fin);
        }

        // Recherche par nom de patient ou médecin
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('medecin', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $abonnements = $query->paginate(20)->withQueryString();
        
        // Statistiques
        $stats = [
            'total' => Abonnement::count(),
            'actifs' => Abonnement::where('statut', 'actif')->count(),
            'expires' => Abonnement::where('date_fin', '<=', now()->addDays(7))->where('date_fin', '>=', now())->count(),
            'expires_today' => Abonnement::whereDate('date_fin', today())->count(),
        ];

        return view('admin.abonnements.index', [
            'abonnements' => $abonnements,
            'stats' => $stats,
            'filters' => $request->all(),
            'types' => [
                'mensuel' => 'Mensuel',
                'trimestriel' => 'Trimestriel',
                'annuel' => 'Annuel',
                'ponctuel' => 'Ponctuel'
            ],
            'statuts' => [
                'actif' => 'Actif',
                'inactif' => 'Inactif',
                'en_attente' => 'En attente',
                'annule' => 'Annulé',
                'suspendu' => 'Suspendu'
            ]
        ]);
    }

    public function show(Abonnement $abonnement)
    {
        $abonnement->load(['patient', 'medecin']);
        
        // Calcul des jours restants
        $joursRestants = now()->diffInDays(Carbon::parse($abonnement->date_fin), false);
        
        return view('admin.abonnements.show', [
            'abonnement' => $abonnement,
            'joursRestants' => $joursRestants > 0 ? $joursRestants : 0,
            'estExpire' => $joursRestants < 0,
            'vaBientotExpirer' => $joursRestants > 0 && $joursRestants <= 7
        ]);
    }

    public function updateStatus(Request $request, Abonnement $abonnement)
    {
        $request->validate([
            'statut' => 'required|in:actif,inactif,en_attente,annule,suspendu',
            'raison' => 'nullable|string|max:500',
            'date_fin' => 'nullable|date|after_or_equal:today'
        ]);

        $ancienStatut = $abonnement->statut;
        
        $abonnement->update([
            'statut' => $request->statut,
            'date_fin' => $request->date_fin ?? $abonnement->date_fin,
            'notes' => $request->filled('raison') 
                ? ($abonnement->notes ? $abonnement->notes . "\n" . now()->format('d/m/Y H:i') . " - " . $request->raison : $request->raison)
                : $abonnement->notes,
        ]);

        // Enregistrement de l'activité
        activity()
            ->performedOn($abonnement)
            ->withProperties([
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $request->statut,
                'raison' => $request->raison ?? null
            ])
            ->log('Statut de l\'abonnement mis à jour');

        return back()->with('success', 'Statut de l\'abonnement mis à jour avec succès.');
    }

    public function renew(Request $request, Abonnement $abonnement)
    {
        $request->validate([
            'duree' => 'required|in:1,3,6,12',
            'date_debut' => 'required|date|after_or_equal:today',
            'montant' => 'required|numeric|min:0'
        ]);

        // Calcul de la nouvelle date de fin
        $dateFin = Carbon::parse($request->date_debut)->addMonths($request->duree);

        // Mise à jour de l'abonnement existant
        $abonnement->update([
            'date_debut' => $request->date_debut,
            'date_fin' => $dateFin,
            'statut' => 'actif',
            'type' => $this->getTypeFromDuration($request->duree),
            'montant' => $request->montant,
            'notes' => $abonnement->notes . "\n" . now()->format('d/m/Y H:i') . " - Abonnement renouvelé pour " . $request->duree . " mois"
        ]);

        // Enregistrement de l'activité
        activity()
            ->performedOn($abonnement)
            ->withProperties([
                'duree' => $request->duree . ' mois',
                'date_debut' => $request->date_debut,
                'date_fin' => $dateFin,
                'montant' => $request->montant
            ])
            ->log('Abonnement renouvelé');

        return back()->with('success', 'Abonnement renouvelé avec succès jusqu\'au ' . $dateFin->format('d/m/Y'));
    }

    public function export(Request $request)
    {
        $abonnements = Abonnement::with(['patient', 'medecin'])
            ->when($request->filled('statut'), function($q) use ($request) {
                $q->where('statut', $request->statut);
            })
            ->when($request->filled('type'), function($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->filled('date_debut'), function($q) use ($request) {
                $q->whereDate('date_debut', '>=', $request->date_debut);
            })
            ->when($request->filled('date_fin'), function($q) use ($request) {
                $q->whereDate('date_fin', '<=', $request->date_fin);
            })
            ->latest('date_debut')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="abonnements_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($abonnements) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID', 'Patient', 'Email Patient', 'Médecin', 'Type',
                'Date début', 'Date fin', 'Statut', 'Montant', 'Jours restants'
            ]);
            
            // Données
            foreach ($abonnements as $abonnement) {
                $joursRestants = now()->diffInDays(Carbon::parse($abonnement->date_fin), false);
                
                fputcsv($file, [
                    $abonnement->id,
                    $abonnement->patient->name ?? 'N/A',
                    $abonnement->patient->email ?? 'N/A',
                    $abonnement->medecin->name ?? 'N/A',
                    ucfirst($abonnement->type),
                    $abonnement->date_debut->format('d/m/Y'),
                    $abonnement->date_fin->format('d/m/Y'),
                    $this->getStatusBadge($abonnement->statut, true),
                    number_format($abonnement->montant ?? 0, 2, ',', ' ') . ' FCFA',
                    $joursRestants > 0 ? $joursRestants . ' jours' : 'Expiré'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getTypeFromDuration($duree)
    {
        return match($duree) {
            1 => 'mensuel',
            3 => 'trimestriel',
            6 => 'semestriel',
            12 => 'annuel',
            default => 'ponctuel',
        };
    }

    private function getStatusBadge($status, $asText = false)
    {
        $classes = [
            'actif' => 'success',
            'inactif' => 'secondary',
            'en_attente' => 'warning',
            'annule' => 'danger',
            'suspendu' => 'info'
        ][$status] ?? 'secondary';

        $labels = [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'en_attente' => 'En attente',
            'annule' => 'Annulé',
            'suspendu' => 'Suspendu'
        ][$status] ?? $status;

        if ($asText) {
            return $labels;
        }

        return '<span class="badge badge-' . $classes . '">' . $labels . '</span>';
    }
}