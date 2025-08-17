<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageAnalysis;

class ImageAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $analyses = ImageAnalysis::where('user_id', $request->user()->id)
            ->latest()
            ->get();
        return view('patient.analyses.index', compact('analyses'));
    }
}
