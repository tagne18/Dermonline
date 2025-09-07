<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultation::with(['patient', 'medecin'])
            ->latest();

        // Filtrage par statut
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtrage par date
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Recherche par nom de patient ou médecin
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('medecin', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $consultations = $query->paginate(15);
        
        return view('admin.consultations.index', [
            'consultations' => $consultations,
            'filters' => $request->all(),
            'statuses' => [
                'scheduled' => 'Planifiée',
                'completed' => 'Terminée',
                'cancelled' => 'Annulée'
            ]
        ]);
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'examen']);
        return view('admin.consultations.show', [
            'consultation' => $consultation,
            'statuses' => [
                'scheduled' => 'Planifiée',
                'completed' => 'Terminée',
                'cancelled' => 'Annulée'
            ]
        ]);
    }

    /**
     * Met à jour le statut d'une consultation
     */
    public function updateStatus(Request $request, Consultation $consultation)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'cancellation_reason' => 'required_if:status,cancelled|string|max:255',
        ]);

        // Mise à jour du statut
        $consultation->update([
            'status' => $request->status,
            'notes' => $request->filled('notes') 
                ? ($consultation->notes ? $consultation->notes . "\n" . now()->format('d/m/Y H:i') . " - " . $request->notes : $request->notes)
                : $consultation->notes,
            'cancellation_reason' => $request->cancellation_reason ?? $consultation->cancellation_reason
        ]);

        // Enregistrement de l'activité
        activity()
            ->performedOn($consultation)
            ->withProperties([
                'status' => $request->status,
                'notes' => $request->notes ?? null
            ])
            ->log('Statut de la consultation mis à jour');

        // Ici, vous pourriez ajouter une notification par email si nécessaire
        // Mail::to($consultation->patient->email)->send(new ConsultationStatusUpdated($consultation));

        return redirect()
            ->route('admin.consultations.show', $consultation)
            ->with('success', 'Le statut de la consultation a été mis à jour avec succès.');
    }
}
