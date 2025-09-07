<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Medicament;
use App\Models\User;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrdonnanceController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Affiche la liste des ordonnances
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'medecin', 'medicaments']);
        
        // Filtrage par patient
        if ($patientId = $request->input('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        
        // Filtrage par médecin
        if ($medecinId = $request->input('medecin_id')) {
            $query->where('medecin_id', $medecinId);
        }
        
        // Filtrage par date
        if ($dateDebut = $request->input('date_debut')) {
            $query->whereDate('date_emission', '>=', $dateDebut);
        }
        
        if ($dateFin = $request->input('date_fin')) {
            $query->whereDate('date_emission', '<=', $dateFin);
        }
        
        // Recherche par texte
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('medecin', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Tri
        $sortField = $request->input('sort', 'date_emission');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $ordonnances = $query->paginate(15)->withQueryString();
        $patients = User::role('patient')->get(['id', 'name', 'prenom']);
        $medecins = User::role('medecin')->get(['id', 'name', 'prenom']);
        
        return view('admin.ordonnances.index', compact('ordonnances', 'patients', 'medecins', 'sortField', 'sortDirection'));
    }

    /**
     * Affiche le formulaire de création d'une ordonnance
     */
    public function create()
    {
        $patients = User::role('patient')->get(['id', 'name', 'prenom', 'date_naissance']);
        $medecins = User::role('medecin')->get(['id', 'name', 'prenom', 'specialite']);
        $medicaments = Medicament::actif()->get(['id', 'nom', 'dosage', 'unite']);
        
        return view('admin.ordonnances.create', compact('patients', 'medecins', 'medicaments'));
    }

    /**
     * Enregistre une nouvelle ordonnance
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medecin_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_emission' => 'required|date',
            'commentaires' => 'nullable|string',
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|exists:medicaments,id',
            'medicaments.*.posologie' => 'required|string|max:255',
            'medicaments.*.duree' => 'required|string|max:100',
            'medicaments.*.instructions' => 'nullable|string',
            'medicaments.*.quantite' => 'required|integer|min:1',
        ]);
        
        // Création de l'ordonnance
        $ordonnance = Prescription::create([
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? null,
            'date_emission' => $validated['date_emission'],
            'commentaires' => $validated['commentaires'] ?? null,
            'statut' => 'valide',
        ]);
        
        // Ajout des médicaments à l'ordonnance
        foreach ($validated['medicaments'] as $medicament) {
            $ordonnance->medicaments()->attach($medicament['id'], [
                'posologie' => $medicament['posologie'],
                'duree' => $medicament['duree'],
                'instructions' => $medicament['instructions'] ?? null,
                'quantite' => $medicament['quantite'],
            ]);
        }
        
        // Génération du PDF
        $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
        $ordonnance->update(['fichier_pdf' => $pdfPath]);
        
        return redirect()
            ->route('admin.ordonnances.show', $ordonnance)
            ->with('success', 'Ordonnance créée avec succès.');
    }

    /**
     * Affiche les détails d'une ordonnance
     */
    public function show(Prescription $ordonnance)
    {
        $ordonnance->load(['patient', 'medecin', 'medicaments']);
        return view('admin.ordonnances.show', compact('ordonnance'));
    }

    /**
     * Télécharge le PDF d'une ordonnance
     */
    public function download(Prescription $ordonnance)
    {
        if (!$ordonnance->fichier_pdf || !Storage::exists('public/' . $ordonnance->fichier_pdf)) {
            // Régénérer le PDF s'il n'existe pas
            $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
            $ordonnance->update(['fichier_pdf' => $pdfPath]);
        }
        
        $filename = 'ordonnance-' . Str::slug($ordonnance->titre) . '-' . $ordonnance->date_emission->format('Y-m-d') . '.pdf';
        
        return Storage::disk('public')->download($ordonnance->fichier_pdf, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une ordonnance
     */
    public function edit(Prescription $ordonnance)
    {
        $ordonnance->load('medicaments');
        $patients = User::role('patient')->get(['id', 'name', 'prenom']);
        $medecins = User::role('medecin')->get(['id', 'name', 'prenom', 'specialite']);
        $medicaments = Medicament::actif()->get(['id', 'nom', 'dosage', 'unite']);
        
        return view('admin.ordonnances.edit', compact('ordonnance', 'patients', 'medecins', 'medicaments'));
    }

    /**
     * Met à jour une ordonnance existante
     */
    public function update(Request $request, Prescription $ordonnance)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medecin_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_emission' => 'required|date',
            'commentaires' => 'nullable|string',
            'statut' => ['required', Rule::in(['brouillon', 'valide', 'annulee'])],
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|exists:medicaments,id',
            'medicaments.*.posologie' => 'required|string|max:255',
            'medicaments.*.duree' => 'required|string|max:100',
            'medicaments.*.instructions' => 'nullable|string',
            'medicaments.*.quantite' => 'required|integer|min:1',
        ]);
        
        // Mise à jour de l'ordonnance
        $ordonnance->update([
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? null,
            'date_emission' => $validated['date_emission'],
            'commentaires' => $validated['commentaires'] ?? null,
            'statut' => $validated['statut'],
        ]);
        
        // Synchronisation des médicaments
        $medicamentsData = [];
        foreach ($validated['medicaments'] as $medicament) {
            $medicamentsData[$medicament['id']] = [
                'posologie' => $medicament['posologie'],
                'duree' => $medicament['duree'],
                'instructions' => $medicament['instructions'] ?? null,
                'quantite' => $medicament['quantite'],
            ];
        }
        
        $ordonnance->medicaments()->sync($medicamentsData);
        
        // Régénération du PDF si nécessaire
        if ($ordonnance->wasChanged()) {
            $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
            $ordonnance->update(['fichier_pdf' => $pdfPath]);
        }
        
        return redirect()
            ->route('admin.ordonnances.show', $ordonnance)
            ->with('success', 'Ordonnance mise à jour avec succès.');
    }

    /**
     * Supprime une ordonnance
     */
    public function destroy(Prescription $ordonnance)
    {
        // Suppression du fichier PDF
        if ($ordonnance->fichier_pdf && Storage::exists('public/' . $ordonnance->fichier_pdf)) {
            Storage::delete('public/' . $ordonnance->fichier_pdf);
        }
        
        // Suppression des relations avec les médicaments
        $ordonnance->medicaments()->detach();
        
        // Suppression de l'ordonnance
        $ordonnance->delete();
        
        return redirect()
            ->route('admin.ordonnances.index')
            ->with('success', 'Ordonnance supprimée avec succès.');
    }
}
