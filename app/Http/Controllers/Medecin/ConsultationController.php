<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::where('medecin_id', auth()->id())->with('patient')->get();
        return view('medecin.consultations.index', compact('consultations'));
    }

    public function accept($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->status = 'acceptée';
        $consultation->save();

        return back()->with('success', 'Consultation acceptée.');
    }

    public function reject($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->status = 'refusée';
        $consultation->save();

        return back()->with('error', 'Consultation refusée.');
    }
}
