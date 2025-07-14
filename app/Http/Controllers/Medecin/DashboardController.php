<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Consultation;

class DashboardController extends Controller
{
    public function index()
    {
        $medecin = auth()->user();
        $patients = $medecin->abonnes()->count();
        $consultations = Consultation::where('medecin_id', $medecin->id)->latest()->take(5)->get();

        return view('medecin.dashboard', compact('patients', 'consultations'));
    }
}
