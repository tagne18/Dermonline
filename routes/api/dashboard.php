<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Récupérer les données du tableau de bord
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData']);
    
    // Mettre à jour les données en fonction de la période sélectionnée
    Route::get('/dashboard/update-period', [DashboardController::class, 'updatePeriod']);
    
    // Autres routes API pour le tableau de bord...
});
