<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Medicament;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdonnanceController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
        $this->middleware('auth');
    }

    /**
     * Affiche la liste des ordonnances du patient avec possibilité de recherche et de filtrage
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['medecin', 'medicaments', 'fichiers'])
            ->where('patient_id', Auth::id())
            ->where('statut', 'active')
            ->latest('date_emission');

        // Filtrage par date
        if ($request->has('date_debut')) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin')) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        // Recherche par nom de médecin, spécialité ou médicament
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                // Recherche par nom/prénom du médecin ou spécialité
                $q->whereHas('medecin', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('prenom', 'like', "%$search%")
                      ->orWhere('specialite', 'like', "%$search%");
                })
                // Ou recherche par nom de médicament
                ->orWhereHas('medicaments', function($q) use ($search) {
                    $q->where('nom', 'like', "%$search%");
                });
            });
        }

        $prescriptions = $query->paginate(10);

        return view('patient.ordonnances.index', compact('prescriptions'));
    }

    /**
     * Affiche les détails d'une ordonnance
     */
    public function show($id)
    {
        $prescription = Prescription::with(['medecin', 'medicaments', 'fichiers'])
            ->where('patient_id', Auth::id())
            ->where('statut', 'active')
            ->findOrFail($id);
            
        // Charger les détails supplémentaires si nécessaire
        $prescription->load(['medecin', 'medicaments', 'fichiers']);
        
        // S'assurer que la date d'émission est définie
        if (!$prescription->date_emission) {
            $prescription->date_emission = $prescription->date_prescription;
        }
        
        return view('patient.ordonnances.show', compact('prescription'));
    }

    /**
     * Télécharge le PDF d'une ordonnance
     */
    public function download($id)
    {
        $ordonnance = Prescription::with(['medecin', 'patient', 'medicaments'])
            ->where('patient_id', Auth::id())
            ->where('statut', 'active')
            ->findOrFail($id);
            
        // Vérifier si le fichier PDF existe
        if (!$ordonnance->fichier_pdf || !Storage::disk('public')->exists($ordonnance->fichier_pdf)) {
            // Tenter de générer le PDF s'il n'existe pas
            try {
                $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
                $ordonnance->update(['fichier_pdf' => $pdfPath]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Impossible de générer le PDF de cette ordonnance.');
            }
        }
        
        $filename = 'ordonnance-' . Str::slug($ordonnance->titre) . '-' . $ordonnance->date_emission->format('Y-m-d') . '.pdf';
        
        return Storage::disk('public')->download($ordonnance->fichier_pdf, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    
    /**
     * Affiche un fichier joint à une ordonnance
     */
    public function showFichier($fichierId)
    {
        $fichier = \App\Models\FichierJoint::whereHas('prescription', function($query) {
                $query->where('patient_id', Auth::id())
                      ->where('statut', 'active');
            })
            ->findOrFail($fichierId);
        
        if (!Storage::disk('public')->exists($fichier->chemin)) {
            abort(404, 'Fichier introuvable');
        }
        
        $mimeType = Storage::disk('public')->mimeType($fichier->chemin);
        $file = Storage::disk('public')->get($fichier->chemin);
        
        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fichier->nom_original . '"'
        ]);
    }
    
    /**
     * Télécharge un fichier joint à une ordonnance
     */
    public function downloadFichier($fichierId)
    {
        $fichier = \App\Models\FichierJoint::whereHas('prescription', function($query) {
                $query->where('patient_id', Auth::id())
                      ->where('statut', 'active');
            })
            ->findOrFail($fichierId);
        
        if (!Storage::disk('public')->exists($fichier->chemin)) {
            abort(404, 'Fichier introuvable');
        }
        
        return Storage::disk('public')->download(
            $fichier->chemin, 
            $fichier->nom_original
        );
    }
    
    /**
     * Affiche l'ordonnance la plus récente du patient
     */
    public function latest()
    {
        $ordonnance = Prescription::with(['medecin', 'patient', 'medicaments'])
            ->where('patient_id', Auth::id())
            ->where('statut', 'valide')
            ->latest('date_emission')
            ->firstOrFail();
            
        // Vérifier si le fichier PDF existe déjà
        if (!$ordonnance->fichier_pdf || !Storage::disk('public')->exists($ordonnance->fichier_pdf)) {
            // Générer le PDF s'il n'existe pas
            $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
            $ordonnance->update(['fichier_pdf' => $pdfPath]);
        }
        
        $filePath = storage_path('app/public/' . $ordonnance->fichier_pdf);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    /**
     * Affiche une ordonnance dans le navigateur
     */
    public function view($id)
    {
        $ordonnance = Prescription::with(['medecin', 'patient', 'medicaments'])
            ->where('patient_id', Auth::id())
            ->where('statut', 'valide')
            ->findOrFail($id);
            
        // Vérifier si le fichier PDF existe déjà
        if (!$ordonnance->fichier_pdf || !Storage::disk('public')->exists($ordonnance->fichier_pdf)) {
            // Générer le PDF s'il n'existe pas
            $pdfPath = $this->pdfService->generateOrdonnancePdf($ordonnance);
            $ordonnance->update(['fichier_pdf' => $pdfPath]);
        }
        
        $filePath = storage_path('app/public/' . $ordonnance->fichier_pdf);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
