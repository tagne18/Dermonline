<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\NewAnnonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewAnnonceController extends Controller
{
    /**
     * Affiche la liste des annonces du médecin
     */
    public function index(Request $request)
    {
        $query = NewAnnonce::where('user_id', auth()->id())
            ->with('user')
            ->latest();

        // Filtrage par statut
        if ($request->has('statut') && in_array($request->statut, ['publie', 'brouillon', 'archive'])) {
            $query->where('statut', $request->statut);
        } elseif ($request->has('statut') && $request->statut === 'tous') {
            // Afficher tous les statuts
        } else {
            // Par défaut, ne pas afficher les archives
            $query->where('statut', '!=', 'archive');
        }

        // Recherche par mot-clé
        if ($request->has('recherche')) {
            $search = $request->recherche;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%");
            });
        }

        $annonces = $query->paginate(10)->withQueryString();

        return view('medecin.new-annonces.index', compact('annonces'));
    }

    /**
     * Affiche le formulaire de création d'annonce
     */
    public function create()
    {
        return view('medecin.new-annonces.create');
    }

    /**
     * Enregistre une nouvelle annonce
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $action = $request->input('action', 'brouillon');
        
        // Gestion de l'image
        $imagePath = $this->handleImageUpload($request);
        
        // Déterminer le statut en fonction du bouton cliqué
        $statut = ($action === 'publier') ? NewAnnonce::STATUT_PUBLIE : NewAnnonce::STATUT_BROUILLON;
        
        // Création de l'annonce
        $annonce = new NewAnnonce([
            'user_id' => auth()->id(),
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
            'image_path' => $imagePath,
            'statut' => $statut,
            'date_publication' => ($statut === NewAnnonce::STATUT_PUBLIE) ? now() : null,
        ]);

        $annonce->save();

        $message = ($statut === NewAnnonce::STATUT_PUBLIE) 
            ? 'Votre annonce a été publiée avec succès !'
            : 'Votre brouillon a été enregistré.';

        return redirect()
            ->route('medecin.new-annonces.index')
            ->with('success', $message);
    }

    /**
     * Affiche une annonce spécifique
     */
    public function show(NewAnnonce $newAnnonce)
    {
        $this->authorize('view', $newAnnonce);
        return view('medecin.new-annonces.show', compact('newAnnonce'));
    }

    /**
     * Affiche le formulaire d'édition d'une annonce
     */
    public function edit(NewAnnonce $newAnnonce)
    {
        $this->authorize('update', $newAnnonce);
        return view('medecin.new-annonces.edit', compact('newAnnonce'));
    }

    /**
     * Met à jour une annonce existante
     */
    public function update(Request $request, NewAnnonce $newAnnonce)
    {
        $this->authorize('update', $newAnnonce);
        
        $validated = $this->validateRequest($request, $newAnnonce->id);
        $action = $request->input('action', 'brouillon');
        
        // Gestion de l'image
        $imagePath = $this->handleImageUpload($request, $newAnnonce);
        
        // Mise à jour des champs
        $newAnnonce->titre = $validated['titre'];
        $newAnnonce->contenu = $validated['contenu'];
        
        // Gestion de l'image
        if ($imagePath !== null) {
            $newAnnonce->image_path = $imagePath;
        } elseif ($request->has('remove_image') && $request->remove_image) {
            // Suppression de l'image existante si demandée
            if ($newAnnonce->image_path) {
                Storage::disk('public')->delete($newAnnonce->image_path);
                $newAnnonce->image_path = null;
            }
        }
        
        // Gestion du statut de publication
        $newStatut = ($action === 'publier') ? NewAnnonce::STATUT_PUBLIE : NewAnnonce::STATUT_BROUILLON;
        $newAnnonce->statut = $newStatut;
        
        // Mise à jour de la date de publication si on passe en publié
        if ($newStatut === NewAnnonce::STATUT_PUBLIE && !$newAnnonce->date_publication) {
            $newAnnonce->date_publication = now();
        }
        
        $newAnnonce->save();

        return redirect()
            ->route('medecin.new-annonces.index')
            ->with('success', 'L\'annonce a été mise à jour avec succès.');
    }

    /**
     * Supprime une annonce
     */
    public function destroy(NewAnnonce $newAnnonce)
    {
        $this->authorize('delete', $newAnnonce);

        // La suppression de l'image est gérée par l'événement du modèle
        $newAnnonce->delete();

        return redirect()
            ->route('medecin.new-annonces.index')
            ->with('success', 'L\'annonce a été supprimée avec succès.');
    }

    /**
     * Télécharge une image depuis l'éditeur TinyMCE
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('new-annonces/editor', 'public');
            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        }
        
        return response()->json(['error' => 'Erreur lors du téléchargement de l\'image'], 500);
    }
    
    /**
     * Valide les données de la requête
     */
    protected function validateRequest(Request $request, $annonceId = null)
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'contenu' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
            'keep_image' => ['sometimes', 'boolean'],
        ]);
    }
    
    /**
     * Gère le téléchargement de l'image
     */
    protected function handleImageUpload(Request $request, $annonce = null)
    {
        // Si on a une annonce existante et qu'on souhaite garder l'image actuelle
        if ($annonce && $request->has('keep_image') && $request->boolean('keep_image')) {
            return $annonce->image_path;
        }
        
        // Si une nouvelle image est téléchargée
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($annonce && $annonce->image_path) {
                Storage::disk('public')->delete($annonce->image_path);
            }
            
            // Stocker la nouvelle image
            return $request->file('image')->store('new-annonces', 'public');
        }
        
        // Si on doit supprimer l'image existante
        if ($request->has('remove_image') && $request->boolean('remove_image') && $annonce) {
            if ($annonce->image_path) {
                Storage::disk('public')->delete($annonce->image_path);
            }
            return null;
        }
        
        // Par défaut, retourner le chemin actuel ou null
        return $annonce ? $annonce->image_path : null;
    }
}
