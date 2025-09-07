<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement;
use App\Models\User;

class AbonnementController extends Controller
{
    public function index()
    {
        $abonnes = Abonnement::where('medecin_id', auth()->id())->with('patient')->get();
        
        $patients = User::whereHas('appointments', function($query) {
                $query->where('medecin_id', auth()->id());
            })
            ->withCount(['abonnements as is_subscribed' => function($query) {
                $query->where('medecin_id', auth()->id())
                      ->where('statut', 'actif')
                      ->where('date_fin', '>=', now());
            }])
            ->with(['appointments' => function($query) {
                $query->where('medecin_id', auth()->id())
                      ->orderBy('date', 'desc')
                      ->limit(1);
            }])
            ->select('users.*')
            ->distinct()
            ->get()
            ->each(function($user) {
                $user->last_appointment = $user->appointments->first();
                unset($user->appointments);
            });
            
        return view('medecin.abonnements.index', compact('abonnes', 'patients'));
    }

    public function updatePrix(Request $request)
    {
        $request->validate([
            'prix' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $user->prix_abonnement = $request->prix;
        $user->save();

        return back()->with('success', 'Prix de l\'abonnement mis à jour.');
    }

    /**
     * Affiche la liste des patients ayant pris rendez-vous avec le médecin
     */
    public function listPatients()
    {
        $patients = User::whereHas('appointments', function($query) {
                $query->where('medecin_id', auth()->id());
            })
            ->withCount(['abonnements as is_subscribed' => function($query) {
                $query->where('medecin_id', auth()->id())
                      ->where('statut', 'actif')
                      ->where('date_fin', '>=', now());
            }])
            ->with(['appointments' => function($query) {
                $query->where('medecin_id', auth()->id())
                      ->orderBy('date', 'desc')
                      ->limit(1);
            }])
            ->select('users.*')
            ->distinct()
            ->get()
            ->each(function($user) {
                $user->last_appointment = $user->appointments->first();
                unset($user->appointments);
            });

        return view('medecin.abonnements.patients', compact('patients'));
    }
}
