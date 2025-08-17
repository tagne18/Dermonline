<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class OrdonnanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::where('medecin_id', auth()->id())
            ->with('patient')
            ->when($request->search, function($q) use ($request) {
                $search = '%' . $request->search . '%';
                return $q->where('titre', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhereHas('patient', function($q) use ($search) {
                        $q->where('name', 'like', $search)
                          ->orWhere('email', 'like', $search);
                    });
            })
            ->when($request->date_from, function($q) use ($request) {
                return $q->whereDate('date_prescription', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                return $q->whereDate('date_prescription', '<=', $request->date_to);
            });

        // Gestion du tri
        $sort = $request->get('sort');
        $direction = $request->get('direction', 'asc');

        switch ($sort) {
            case 'patient':
                $query->join('users', 'prescriptions.patient_id', '=', 'users.id')
                    ->select('prescriptions.*')
                    ->orderBy('users.name', $direction);
                break;
            case 'titre':
                $query->orderBy('titre', $direction);
                break;
            case 'date_prescription':
                $query->orderBy('date_prescription', $direction);
                break;
            default:
                $query->latest('date_prescription');
        }

        $ordonnances = $query->paginate(10)->withQueryString();
        
        return view('medecin.ordonnances.index', compact('ordonnances'));
    }

    public function create(Request $request)
    {
        // Si on vient d'un rendez-vous, on pré-remplit le patient
        $patient = null;
        if ($request->has('appointment')) {
            $appointment = \App\Models\Appointment::find($request->appointment);
            if ($appointment) {
                $patient = $appointment->user;
            }
        }
        if (!$patient) {
            // fallback : liste déroulante des patients
            $patients = User::where('role', 'patient')->get();
            return view('medecin.ordonnances.create', compact('patients'));
        }
        return view('medecin.prescriptions.create', compact('patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'date_prescription' => 'required|date',
        ]);

        $data = $request->only(['patient_id', 'titre', 'description', 'date_prescription']);
        $data['medecin_id'] = auth()->id();

        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('ordonnances', 'public');
        }

        Prescription::create($data);
        return redirect()->route('medecin.ordonnances.index')->with('success', 'Ordonnance créée avec succès.');
    }

    public function show($id)
    {
        $ordonnance = Prescription::where('medecin_id', auth()->id())->findOrFail($id);
        return view('medecin.ordonnances.show', compact('ordonnance'));
    }

    public function edit($id)
    {
        $ordonnance = Prescription::where('medecin_id', auth()->id())->findOrFail($id);
        $patients = User::where('role', 'patient')->get();
        return view('medecin.ordonnances.edit', compact('ordonnance', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $ordonnance = Prescription::where('medecin_id', auth()->id())->findOrFail($id);
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'date_prescription' => 'required|date',
        ]);
        $data = $request->only(['patient_id', 'titre', 'description', 'date_prescription']);
        if ($request->hasFile('fichier')) {
            if ($ordonnance->fichier) {
                Storage::disk('public')->delete($ordonnance->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('ordonnances', 'public');
        }
        $ordonnance->update($data);
        return redirect()->route('medecin.ordonnances.index')->with('success', 'Ordonnance modifiée avec succès.');
    }

    public function destroy($id)
    {
        $ordonnance = Prescription::where('medecin_id', auth()->id())->findOrFail($id);
        if ($ordonnance->fichier) {
            Storage::disk('public')->delete($ordonnance->fichier);
        }
        $ordonnance->delete();
        return redirect()->route('medecin.ordonnances.index')->with('success', 'Ordonnance supprimée.');
    }
}
