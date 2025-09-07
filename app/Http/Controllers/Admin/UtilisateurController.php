<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'patient')
            ->with(['abonnement', 'appointments']);

        // Recherche par nom, email ou ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filtre par statut d'abonnement
        if ($request->filled('abonnement')) {
            if ($request->abonnement === 'actif') {
                $query->whereHas('abonnement', function($q) {
                    $q->where('statut', 'actif');
                });
            } elseif ($request->abonnement === 'inactif') {
                $query->whereDoesntHave('abonnement')
                      ->orWhereHas('abonnement', function($q) {
                          $q->where('statut', '!=', 'actif');
                      });
            }
        }

        // Filtre par statut de blocage
        if ($request->filled('statut')) {
            if ($request->statut === 'bloque') {
                $query->where('is_blocked', true);
            } elseif ($request->statut === 'actif') {
                $query->where('is_blocked', false);
            }
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $patients = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => User::where('role', 'patient')->count(),
            'abonnes' => User::where('role', 'patient')
                ->whereHas('abonnement', function($q) {
                    $q->where('statut', 'actif');
                })->count(),
            'bloques' => User::where('role', 'patient')->where('is_blocked', true)->count(),
            'nouveaux' => User::where('role', 'patient')
                ->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.users.patients', compact('patients', 'stats'));
    }

    public function bloquer(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $user = User::findOrFail($id);
        $user->is_blocked = true;
        $user->blocked_at = now();
        $user->block_reason = $request->input('reason');
        $user->save();

        return redirect()->back()->with('success', 'Patient bloqué avec succès. Il ne pourra plus se connecter.');
    }

    public function debloquer($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = false;
        $user->blocked_at = null;
        $user->save();

        return redirect()->back()->with('success', 'Patient débloqué avec succès. Il peut maintenant se connecter.');
    }

    public function show($id)
    {
        $patient = User::where('role', 'patient')
            ->with(['abonnement', 'appointments'])
            ->findOrFail($id);

        return view('admin.users.patient-detail', compact('patient'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Patient supprimé avec succès.');
    }

    public function blocked()
    {
        $users = User::where('is_blocked', true)->get();
        return view('admin.users.blocked', compact('users'));
    }
}

