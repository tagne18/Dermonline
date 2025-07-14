<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class AbonnementController extends Controller
{
    public function index()
    {
        $abonnes = Subscription::where('medecin_id', auth()->id())->with('patient')->get();
        return view('medecin.abonnements.index', compact('abonnes'));
    }

    public function updatePrix(Request $request)
    {
        $request->validate([
            'prix' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $user->prix_abonnement = $request->prix;
        $user->save();

        return back()->with('success', 'Prix de l’abonnement mis à jour.');
    }
}

