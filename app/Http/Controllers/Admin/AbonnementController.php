<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement; // <-- AJOUT ICI

class AbonnementController extends Controller
{
    public function index()
    {
        // Exemple : retourne une vue avec la liste des abonnements
        $abonnements = Abonnement::all();
        return view('admin.abonnements.index', compact('abonnements'));
    }
}