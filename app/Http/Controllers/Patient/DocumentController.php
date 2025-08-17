<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DossierMedical;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $documents = DossierMedical::where('patient_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return view('patient.documents.index', compact('documents'));
    }
}
