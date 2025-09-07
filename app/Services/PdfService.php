<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateOrdonnancePdf($data, $filename)
    {
        $path = 'ordonnances/' . now()->format('Y/m/d') . '/' . $filename . '.pdf';
        
        // Créer le PDF
        $pdf = Pdf::loadView('pdf.ordonnance', ['data' => $data]);
        
        // Sauvegarder le fichier
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }
}
