<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MedicamentController extends Controller
{
    /**
     * Affiche la liste des médicaments
     */
    public function index(Request $request)
    {
        $query = Medicament::query();
        
        // Filtrage par recherche
        if ($search = $request->input('search')) {
            $query->where('nom', 'LIKE', "%{$search}%")
                ->orWhere('code_cip', 'LIKE', "%{$search}%")
                ->orWhere('code_cip13', 'LIKE', "%{$search}%");
        }
        
        // Filtrage par statut
        if ($request->has('actif') && in_array($request->actif, ['0', '1'])) {
            $query->where('est_actif', $request->actif);
        }
        
        // Filtrage par type d'ordonnance
        if ($request->has('sur_ordonnance') && in_array($request->sur_ordonnance, ['0', '1'])) {
            $query->where('sur_ordonnance', $request->sur_ordonnance);
        }
        
        // Tri
        $sortField = $request->input('sort', 'nom');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        $medicaments = $query->paginate(20)->withQueryString();
        
        return view('admin.medicaments.index', compact('medicaments', 'sortField', 'sortDirection'));
    }

    /**
     * Affiche le formulaire de création d'un médicament
     */
    public function create()
    {
        return view('admin.medicaments.create');
    }

    /**
     * Enregistre un nouveau médicament
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'categorie' => 'nullable|string|max:50',
            'forme_galenique' => 'nullable|string|max:50',
            'dosage' => 'nullable|string|max:50',
            'unite' => 'nullable|string|max:20',
            'code_cip' => 'nullable|string|max:20|unique:medicaments,code_cip',
            'code_cip13' => 'nullable|string|max:20|unique:medicaments,code_cip13',
            'taux_remboursement' => 'nullable|string|max:10',
            'sur_ordonnance' => 'boolean',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'interactions' => 'nullable|string',
            'est_actif' => 'boolean',
        ]);

        $medicament = Medicament::create($validated);
        
        return redirect()
            ->route('admin.medicaments.show', $medicament)
            ->with('success', 'Médicament créé avec succès.');
    }

    /**
     * Affiche les détails d'un médicament
     */
    public function show(Medicament $medicament)
    {
        $medicament->loadCount('prescriptions');
        return view('admin.medicaments.show', compact('medicament'));
    }

    /**
     * Affiche le formulaire de modification d'un médicament
     */
    public function edit(Medicament $medicament)
    {
        return view('admin.medicaments.edit', compact('medicament'));
    }

    /**
     * Met à jour un médicament existant
     */
    public function update(Request $request, Medicament $medicament)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'categorie' => 'nullable|string|max:50',
            'forme_galenique' => 'nullable|string|max:50',
            'dosage' => 'nullable|string|max:50',
            'unite' => 'nullable|string|max:20',
            'code_cip' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('medicaments', 'code_cip')->ignore($medicament->id),
            ],
            'code_cip13' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('medicaments', 'code_cip13')->ignore($medicament->id),
            ],
            'taux_remboursement' => 'nullable|string|max:10',
            'sur_ordonnance' => 'boolean',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'interactions' => 'nullable|string',
            'est_actif' => 'boolean',
        ]);

        $medicament->update($validated);
        
        return redirect()
            ->route('admin.medicaments.show', $medicament)
            ->with('success', 'Médicament mis à jour avec succès.');
    }

    /**
     * Supprime un médicament
     */
    public function destroy(Medicament $medicament)
    {
        // Vérifier s'il est utilisé dans des prescriptions
        if ($medicament->prescriptions()->exists()) {
            return back()
                ->with('error', 'Ce médicament est utilisé dans des prescriptions et ne peut pas être supprimé.');
        }
        
        $medicament->delete();
        
        return redirect()
            ->route('admin.medicaments.index')
            ->with('success', 'Médicament supprimé avec succès.');
    }
    
    /**
     * API: Recherche de médicaments
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $medicaments = Medicament::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('code_cip', 'LIKE', "%{$query}%")
            ->orWhere('code_cip13', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function($medicament) {
                return [
                    'id' => $medicament->id,
                    'text' => $medicament->nom_complet,
                    'dosage' => $medicament->dosage,
                    'unite' => $medicament->unite,
                ];
            });
            
        return response()->json($medicaments);
    }
}
