<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ExamResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamResultController extends Controller
{
    // Liste des résultats d'examens du patient connecté
    public function index()
    {
        $user = Auth::user();
        $examResults = ExamResult::where('patient_id', $user->id)->orderBy('date_examen', 'desc')->get();
        return view('patient.examens.index', compact('examResults'));
    }

    // Détail d'un résultat d'examen
    public function show($id)
    {
        $user = Auth::user();
        $examResult = ExamResult::where('id', $id)->where('patient_id', $user->id)->firstOrFail();
        return view('patient.examens.show', compact('examResult'));
    }

    // Téléchargement du fichier
    public function download($id)
    {
        $user = Auth::user();
        $examResult = ExamResult::where('id', $id)->where('patient_id', $user->id)->firstOrFail();
        if ($examResult->fichier && Storage::disk('public')->exists($examResult->fichier)) {
            return Storage::disk('public')->download($examResult->fichier);
        }
        abort(404, 'Fichier non trouvé');
    }
}
