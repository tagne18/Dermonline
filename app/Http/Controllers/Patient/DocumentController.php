<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DossierMedical;
use App\Models\Consultation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Affiche la liste des documents du patient
     */
    public function index()
    {
        $user = auth()->user();
        $documents = DossierMedical::where('patient_id', $user->id)
            ->with(['medecin', 'consultation'])
            ->orderByDesc('created_at')
            ->paginate(10);
            
        return view('patient.documents.index', compact('documents'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau document
     */
    public function create()
    {
        $user = auth()->user();
        $consultations = Consultation::where('patient_id', $user->id)
            ->orderBy('date_consultation', 'desc')
            ->pluck('date_consultation', 'id');
            
        return view('patient.documents.create', compact('consultations'));
    }

    /**
     * Enregistre un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_document' => 'required|in:ordonnance,analyse,compte_rendu,certificat,autre',
            'fichier' => 'required|file|max:10240', // 10MB max
            'consultation_id' => 'nullable|exists:consultations,id',
        ]);

        $file = $request->file('fichier');
        $path = $file->store('documents/' . auth()->id(), 'public');

        $document = new DossierMedical([
            'patient_id' => auth()->id(),
            'medecin_id' => auth()->user()->current_medecin_id, // À adapter selon votre logique
            'consultation_id' => $validated['consultation_id'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'type_document' => $validated['type_document'],
            'fichier' => $path,
            'nom_fichier' => $file->getClientOriginalName(),
            'taille_fichier' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'statut' => 'actif',
        ]);

        $document->save();

        return redirect()->route('patient.documents.index')
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un document
     */
    public function show(DossierMedical $document)
    {
        $this->authorize('view', $document);
        
        return view('patient.documents.show', compact('document'));
    }

    /**
     * Télécharge un document
     */
    public function download(DossierMedical $document)
    {
        $this->authorize('view', $document);
        
        if (!Storage::disk('public')->exists($document->fichier)) {
            abort(404);
        }
        
        return Storage::disk('public')->download(
            $document->fichier, 
            $document->nom_fichier
        );
    }

    /**
     * Supprime un document
     */
    public function destroy(DossierMedical $document)
    {
        $this->authorize('delete', $document);
        
        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($document->fichier)) {
            Storage::disk('public')->delete($document->fichier);
        }
        
        $document->delete();
        
        return redirect()->route('patient.documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }
}
