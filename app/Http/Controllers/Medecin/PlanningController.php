<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PlanningController extends Controller
{
    /**
     * Affiche la liste des plannings du médecin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $plannings = Planning::where('medecin_id', Auth::id())
            ->orderBy('date_consultation', 'desc')
            ->orderBy('heure_debut')
            ->paginate(10);

        return view('medecin.planning.index', compact('plannings'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau planning.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('medecin.planning.create');
    }

    /**
     * Enregistre un nouveau planning.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'date_consultation' => ['required', 'date', 'after_or_equal:today'],
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'duree_consultation' => 'required|integer|min:15|max:240',
            'type_consultation' => ['required', Rule::in(['presentiel', 'en_ligne', 'hybride'])],
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupérer les données validées
        $validated = $validator->validated();

        // Formater la date au format Y-m-d pour la base de données
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $validated['date_consultation']);
            $validated['date_consultation'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['date_consultation' => 'Le format de date est invalide.']);
        }

        // Vérifier les chevauchements de planning
        $existingPlanning = Planning::where('medecin_id', Auth::id())
            ->where('date_consultation', $validated['date_consultation'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('heure_debut', [$validated['heure_debut'], $validated['heure_fin']])
                    ->orWhereBetween('heure_fin', [$validated['heure_debut'], $validated['heure_fin']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('heure_debut', '<=', $validated['heure_debut'])
                            ->where('heure_fin', '>=', $validated['heure_fin']);
                    });
            })
            ->exists();

        if ($existingPlanning) {
            return back()
                ->withInput()
                ->withErrors(['planning' => 'Un créneau existe déjà pour cette plage horaire.']);
        }

        // Créer le planning
        $planning = new Planning($validated);
        $planning->medecin_id = Auth::id();
        $planning->statut = 'planifie';
        $planning->save();

        return redirect()
            ->route('medecin.planning.index')
            ->with('success', 'Le planning a été créé avec succès.');
    }

    /**
     * Affiche un planning spécifique.
     *
     * @param  \App\Models\Planning  $planning
     * @return \Illuminate\View\View
     */
    public function show(Planning $planning)
    {
        $this->authorize('view', $planning);
        return view('medecin.planning.show', compact('planning'));
    }

    /**
     * Affiche le formulaire d'édition d'un planning.
     *
     * @param  \App\Models\Planning  $planning
     * @return \Illuminate\View\View
     */
    public function edit(Planning $planning)
    {
        $this->authorize('update', $planning);
        return view('medecin.planning.edit', compact('planning'));
    }

    /**
     * Met à jour un planning existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Planning  $planning
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Planning $planning)
    {
        $this->authorize('update', $planning);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'date_consultation' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'duree_consultation' => 'required|integer|min:15|max:240',
            'type_consultation' => ['required', Rule::in(['presentiel', 'en_ligne', 'hybride'])],
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => ['required', Rule::in(['planifie', 'confirme', 'annule', 'termine'])]
        ]);

        $planning->update($validated);

        return redirect()
            ->route('medecin.planning.index')
            ->with('success', 'Le planning a été mis à jour avec succès.');
    }

    /**
     * Supprime un planning.
     *
     * @param  \App\Models\Planning  $planning
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Planning $planning)
    {
        $this->authorize('delete', $planning);
        
        if ($planning->statut !== 'planifie') {
            return back()
                ->with('error', 'Seuls les plannings en statut "Planifié" peuvent être supprimés.');
        }

        $planning->delete();

        return redirect()
            ->route('medecin.planning.index')
            ->with('success', 'Le planning a été supprimé avec succès.');
    }

    /**
     * Affiche l'historique des consultations passées.
     *
     * @return \Illuminate\View\View
     */
    public function getHistorique(Request $request)
    {
        $query = Planning::where('medecin_id', Auth::id())
            ->orderBy('date_consultation', 'desc')
            ->orderBy('heure_debut', 'desc');

        // Filtres
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type_consultation') && $request->type_consultation != '') {
            $query->where('type_consultation', $request->type_consultation);
        }

        if ($request->has('date_debut') && $request->date_debut != '') {
            $query->whereDate('date_consultation', '>=', $request->date_debut);
        }

        if ($request->has('date_fin') && $request->date_fin != '') {
            $query->whereDate('date_consultation', '<=', $request->date_fin);
        }

        $plannings = $query->paginate(10);

        // Statistiques
        $stats = [
            'total' => Planning::where('medecin_id', Auth::id())->count(),
            'termines' => Planning::where('medecin_id', Auth::id())->where('statut', 'termine')->count(),
            'en_cours' => Planning::where('medecin_id', Auth::id())
                ->whereIn('statut', ['planifie', 'confirme'])
                ->whereDate('date_consultation', '>=', now())
                ->count(),
            'annules' => Planning::where('medecin_id', Auth::id())->where('statut', 'annule')->count(),
        ];

        return view('medecin.planning.historique', compact('plannings', 'stats'));
    }
}
