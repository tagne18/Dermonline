<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PharmacyController extends Controller
{
    /**
     * Affiche la page des pharmacies à proximité
     */
    public function index()
    {
        return view('patient.pharmacies');
    }
}
