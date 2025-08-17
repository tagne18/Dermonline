<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ExamenController extends Controller
{
    public function index()
    {
        $examens = ExamResult::where('medecin_id', auth()->id())->latest()->paginate(10);
        return view('medecin.examens.index', compact('examens'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        return view('medecin.examens.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'date_examen' => 'required|date',
        ]);

        $data = $request->only(['patient_id', 'titre', 'description', 'date_examen']);
        $data['medecin_id'] = auth()->id();

        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('examens', 'public');
        }

        ExamResult::create($data);
        return redirect()->route('medecin.examens.index')->with('success', 'Examen créé avec succès.');
    }

    public function show($id)
    {
        $examen = ExamResult::where('medecin_id', auth()->id())->findOrFail($id);
        return view('medecin.examens.show', compact('examen'));
    }

    public function edit($id)
    {
        $examen = ExamResult::where('medecin_id', auth()->id())->findOrFail($id);
        $patients = User::where('role', 'patient')->get();
        return view('medecin.examens.edit', compact('examen', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $examen = ExamResult::where('medecin_id', auth()->id())->findOrFail($id);
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'date_examen' => 'required|date',
        ]);
        $data = $request->only(['patient_id', 'titre', 'description', 'date_examen']);
        if ($request->hasFile('fichier')) {
            if ($examen->fichier) {
                Storage::disk('public')->delete($examen->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('examens', 'public');
        }
        $examen->update($data);
        return redirect()->route('medecin.examens.index')->with('success', 'Examen modifié avec succès.');
    }

    public function destroy($id)
    {
        $examen = ExamResult::where('medecin_id', auth()->id())->findOrFail($id);
        if ($examen->fichier) {
            Storage::disk('public')->delete($examen->fichier);
        }
        $examen->delete();
        return redirect()->route('medecin.examens.index')->with('success', 'Examen supprimé.');
    }
}
