<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::latest()->get(); // Tu peux filtrer par `->where('visible', true)` si nécessaire
        return view('patient.annonces.index', compact('annonces'));
    }
}
