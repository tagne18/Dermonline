<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DossierMedical;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DossierController extends Controller
{
    public function index()
    {
        $dossiers = DossierMedical::where('medecin_id', auth()->id())
            ->with(['patient', 'consultation'])
            ->latest()
            ->get();

        return view('medecin.dossiers.index', compact('dossiers'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        return view('medecin.dossiers.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_document' => 'required|string|max:255',
            'fichier' => 'nullable|file|max:10240', // 10MB max
        ]);

        $data = $request->only(['patient_id', 'titre', 'description', 'type_document']);
        $data['medecin_id'] = auth()->id();

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $path = $file->store('dossiers', 'public');
            
            $data['fichier'] = $path;
            $data['nom_fichier'] = $file->getClientOriginalName();
            $data['taille_fichier'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        DossierMedical::create($data);

        return redirect()->route('medecin.dossiers.index')
            ->with('success', 'Dossier médical créé avec succès.');
    }

    public function show($id)
    {
        $dossier = DossierMedical::where('medecin_id', auth()->id())
            ->with(['patient', 'consultation'])
            ->findOrFail($id);

        return view('medecin.dossiers.show', compact('dossier'));
    }

    public function edit($id)
    {
        $dossier = DossierMedical::where('medecin_id', auth()->id())->findOrFail($id);
        $patients = User::where('role', 'patient')->get();

        return view('medecin.dossiers.edit', compact('dossier', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $dossier = DossierMedical::where('medecin_id', auth()->id())->findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_document' => 'required|string|max:255',
            'fichier' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['patient_id', 'titre', 'description', 'type_document']);

        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($dossier->fichier) {
                Storage::disk('public')->delete($dossier->fichier);
            }

            $file = $request->file('fichier');
            $path = $file->store('dossiers', 'public');
            
            $data['fichier'] = $path;
            $data['nom_fichier'] = $file->getClientOriginalName();
            $data['taille_fichier'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $dossier->update($data);

        return redirect()->route('medecin.dossiers.index')
            ->with('success', 'Dossier médical mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $dossier = DossierMedical::where('medecin_id', auth()->id())->findOrFail($id);

        if ($dossier->fichier) {
            Storage::disk('public')->delete($dossier->fichier);
        }

        $dossier->delete();

        return redirect()->route('medecin.dossiers.index')
            ->with('success', 'Dossier médical supprimé avec succès.');
    }

    public function download($id)
    {
        $dossier = DossierMedical::where('medecin_id', auth()->id())->findOrFail($id);

        if (!$dossier->fichier || !Storage::disk('public')->exists($dossier->fichier)) {
            abort(404, 'Fichier non trouvé.');
        }

        return Storage::disk('public')->download($dossier->fichier, $dossier->nom_fichier);
    }
}

