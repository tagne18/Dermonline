<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::latest()->paginate(10);
        return view('admin.annonces.index', compact('annonces'));
    }


public function create()
{
    return view('admin.annonces.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'date_publication' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('annonces', 'public');
    }

    Annonce::create($data);

    return redirect()->route('admin.annonces.index')->with('success', 'Annonce créée avec succès.');
}

public function edit(Annonce $annonce)
{
    return view('admin.annonces.edit', compact('annonce'));
}

public function update(Request $request, Annonce $annonce)
{
    $data = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'date_publication' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Optionnel : supprimer l'ancienne image
        if ($annonce->image) {
            Storage::disk('public')->delete($annonce->image);
        }
        $data['image'] = $request->file('image')->store('annonces', 'public');
    }

    $annonce->update($data);

    return redirect()->route('admin.annonces.index')->with('success', 'Annonce mise à jour.');
}

public function destroy(Annonce $annonce)
{
    // Supprimer l'image s'il y en a une
    if ($annonce->image_path && Storage::exists($annonce->image_path)) {
        Storage::delete($annonce->image_path);
    }

    // Supprimer l'annonce
    $annonce->delete();

    // Rediriger avec un message de succès
    return redirect()->route('admin.annonces.index')
                     ->with('success', 'Annonce supprimée avec succès.');
}
}

