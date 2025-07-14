<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Démarrer le framework
$kernel->bootstrap();

// Mettre à jour tous les rendez-vous sans medecin_id
DB::table('appointments')->whereNull('medecin_id')->update(['medecin_id' => 7]); // Remplace 1 par l'ID du médecin par défaut souhaité

echo "Tous les rendez-vous sans medecin_id ont été mis à jour.\n"; 