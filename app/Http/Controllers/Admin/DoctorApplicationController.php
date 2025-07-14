<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Notifications\MedecinAccountCreated;
use App\Notifications\AdminAlert;

class DoctorApplicationController extends Controller
{
    public function index()
    {
        $applications = DoctorApplication::latest()->get();
        $users = User::pluck('email')->toArray();
        return view('admin.doctor_applications.index', compact('applications', 'users'));
    }

    public function show($id)
    {
        $application = DoctorApplication::findOrFail($id);
        return view('admin.doctor_applications.show', compact('application'));
    }

    public function approve($id)
    {
        $application = DoctorApplication::findOrFail($id);
        Log::info("Approving doctor application ID: $id");

        if ($application->status !== 'pending') {
            return redirect()->back()->with('info', 'Cette demande a déjà été traitée.');
        }

        // Vérifie si l'utilisateur existe déjà
        if (User::where('email', $application->email)->exists()) {
            return redirect()->back()->with('error', 'Un utilisateur avec cet email existe déjà.');
        }

        $password = 'Tagne@1234';

        $user = User::create([
            'name' => $application->name,
            'email' => $application->email,
            'password' => Hash::make($password),
            'phone' => $application->phone,
            'specialite' => $application->specialite,
            'ville' => $application->ville,
            'langue' => $application->langue,
            'lieu_travail' => $application->lieu_travail,
            'role' => 'medecin',
        ]);

        // Mise à jour du statut de la demande
        $application->status = 'approved';
        $application->approved_at = now();
        $application->save();

        // Envoi de notification
        try {
            $user->notify(new MedecinAccountCreated($user, $password));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de notification: ' . $e->getMessage());
        }

        return redirect()->back()->with([
            'success' => 'Compte médecin approuvé avec succès.',
            'approved_password' => $password,
            'approved_email' => $application->email
        ]);
    }

    public function reject($id)
    {
        $application = DoctorApplication::findOrFail($id);

        if ($application->status !== 'pending') {
            return redirect()->back()->with('info', 'Cette demande a déjà été traitée.');
        }

        $application->status = 'rejected';
        $application->save();

        return redirect()->back()->with('success', 'Demande rejetée avec succès.');
    }

    public function createUser($id)
    {
        $app = DoctorApplication::findOrFail($id);

        if (User::where('email', $app->email)->exists()) {
            return back()->with('info', 'Le compte utilisateur existe déjà.');
        }

        $password = Str::random(8);
        $user = User::create([
            'name' => $app->name,
            'email' => $app->email,
            'password' => Hash::make($password),
            'phone' => $app->phone,
            'specialite' => $app->specialite,
            'ville' => $app->ville,
            'langue' => $app->langue,
            'lieu_travail' => $app->lieu_travail,
            'role' => 'medecin',
        ]);

        return back()->with([
            'success' => 'Compte utilisateur créé.',
            'approved_email' => $user->email,
            'approved_password' => $password,
        ]);
    }

    public function medecins(Request $request)
    {
        $query = User::where('role', 'medecin');

        // Recherche par nom, email, spécialité ou ville
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialite', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('lieu_travail', 'like', "%{$search}%");
            });
        }

        // Filtre par statut (bloqué/non bloqué)
        if ($request->filled('statut')) {
            if ($request->statut === 'bloque') {
                $query->where('is_blocked', true);
            } elseif ($request->statut === 'actif') {
                $query->where('is_blocked', false);
            }
        }

        // Filtre par spécialité
        if ($request->filled('specialite')) {
            $query->where('specialite', $request->specialite);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $medecins = $query->withCount(['abonnes', 'consultationsAsMedecin'])->paginate(15);

        // Statistiques
        $stats = [
            'total' => User::where('role', 'medecin')->count(),
            'actifs' => User::where('role', 'medecin')->where('is_blocked', false)->count(),
            'bloques' => User::where('role', 'medecin')->where('is_blocked', true)->count(),
            'nouveaux' => User::where('role', 'medecin')
                ->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        // Liste des spécialités pour le filtre
        $specialites = User::where('role', 'medecin')
            ->whereNotNull('specialite')
            ->distinct()
            ->pluck('specialite')
            ->filter()
            ->sort()
            ->values();

        return view('admin.medecins.index', compact('medecins', 'stats', 'specialites'));
    }

    public function bloquerMedecin($id)
    {
        $medecin = User::where('role', 'medecin')->findOrFail($id);
        $medecin->is_blocked = true;
        $medecin->blocked_at = now();
        $medecin->save();

        return redirect()->back()->with('success', 'Médecin bloqué avec succès. Il ne pourra plus se connecter.');
    }

    public function debloquerMedecin($id)
    {
        $medecin = User::where('role', 'medecin')->findOrFail($id);
        $medecin->is_blocked = false;
        $medecin->blocked_at = null;
        $medecin->save();

        return redirect()->back()->with('success', 'Médecin débloqué avec succès. Il peut maintenant se connecter.');
    }

    public function envoyerAlerte(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,warning,danger'
        ]);

        $medecin = User::where('role', 'medecin')->findOrFail($id);

        // Créer la notification
        $medecin->notifications()->create([
            'type' => $request->type,
            'title' => 'Alerte de l\'Administration',
            'message' => $request->message,
            'is_read' => false
        ]);

        // Optionnel : Envoyer un email de notification
        // Mail::to($medecin->email)->send(new AdminAlert($medecin, $request->message));
        
        return redirect()->back()->with('success', 'Message d\'alerte envoyé au médecin avec succès.');
    }

    public function showMedecin($id)
    {
        $medecin = User::where('role', 'medecin')
            ->withCount(['abonnes', 'consultationsAsMedecin'])
            ->with(['abonnes.patient', 'consultationsAsMedecin.patient'])
            ->findOrFail($id);

        // Statistiques du médecin
        $stats = [
            'total_abonnes' => $medecin->abonnes_count,
            'total_consultations' => $medecin->consultations_as_medecin_count,
            'consultations_ce_mois' => $medecin->consultationsAsMedecin()
                ->whereMonth('created_at', now()->month)
                ->count(),
            'abonnes_ce_mois' => $medecin->abonnes()
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('admin.medecins.show', compact('medecin', 'stats'));
    }

    public function exportMedecins()
    {
        $medecins = User::where('role', 'medecin')
            ->withCount(['abonnes', 'consultationsAsMedecin'])
            ->get();

        $filename = 'medecins_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($medecins) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID', 'Nom', 'Email', 'Téléphone', 'Spécialité', 'Ville', 
                'Lieu de travail', 'Langue', 'Statut', 'Date d\'inscription',
                'Nombre d\'abonnés', 'Nombre de consultations'
            ]);

            // Données
            foreach ($medecins as $medecin) {
                fputcsv($file, [
                    $medecin->id,
                    $medecin->name,
                    $medecin->email,
                    $medecin->phone ?? '',
                    $medecin->specialite ?? '',
                    $medecin->ville ?? '',
                    $medecin->lieu_travail ?? '',
                    $medecin->langue === 'fr' ? 'Français' : 'Anglais',
                    $medecin->is_blocked ? 'Bloqué' : 'Actif',
                    $medecin->created_at->format('d/m/Y H:i'),
                    $medecin->abonnes_count,
                    $medecin->consultations_as_medecin_count
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statistiques()
    {
        $stats = [
            'total' => User::where('role', 'medecin')->count(),
            'actifs' => User::where('role', 'medecin')->where('is_blocked', false)->count(),
            'bloques' => User::where('role', 'medecin')->where('is_blocked', true)->count(),
            'nouveaux_30j' => User::where('role', 'medecin')
                ->where('created_at', '>=', now()->subDays(30))->count(),
            'nouveaux_7j' => User::where('role', 'medecin')
                ->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Top 5 des spécialités
        $topSpecialites = User::where('role', 'medecin')
            ->whereNotNull('specialite')
            ->selectRaw('specialite, COUNT(*) as count')
            ->groupBy('specialite')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Top 5 des villes
        $topVilles = User::where('role', 'medecin')
            ->whereNotNull('ville')
            ->selectRaw('ville, COUNT(*) as count')
            ->groupBy('ville')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Évolution mensuelle des inscriptions
        $evolution = User::where('role', 'medecin')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, COUNT(*) as count')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return view('admin.medecins.statistiques', compact('stats', 'topSpecialites', 'topVilles', 'evolution'));
    }

    public function deleteRejected()
    {
        $count = DoctorApplication::where('status', 'rejected')->count();

        if ($count > 0) {
            DoctorApplication::where('status', 'rejected')->delete();
            return back()->with('success', "$count demande(s) rejetée(s) supprimée(s).");
        }

        return back()->with('info', 'Aucune demande rejetée à supprimer.');
    }
}
