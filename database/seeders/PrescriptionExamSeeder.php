<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrescriptionExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On suppose que les rôles sont stockés dans la colonne 'role' de la table users
        $patient = \App\Models\User::where('role', 'patient')->first();
        $medecin = \App\Models\User::where('role', 'medecin')->first();

        if (!$patient || !$medecin) {
            echo "Aucun patient ou médecin trouvé. Ajoutez d'abord des utilisateurs de chaque rôle.";
            return;
        }

        // Ordonnances
        \App\Models\Prescription::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'titre' => 'Ordonnance test 1',
            'description' => 'Paracétamol 500mg, 3 fois par jour pendant 5 jours.',
            'fichier' => null,
            'date_prescription' => now()->subDays(10),
        ]);
        \App\Models\Prescription::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'titre' => 'Ordonnance test 2',
            'description' => 'Ibuprofène 200mg, 2 fois par jour pendant 7 jours.',
            'fichier' => null,
            'date_prescription' => now()->subDays(3),
        ]);

        // Examens
        \App\Models\ExamResult::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'titre' => 'Bilan sanguin',
            'description' => 'Résultat normal, aucune anomalie détectée.',
            'fichier' => null,
            'date_examen' => now()->subDays(8),
        ]);
        \App\Models\ExamResult::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'titre' => 'Radiographie thoracique',
            'description' => 'Pas de signe de pneumonie.',
            'fichier' => null,
            'date_examen' => now()->subDays(2),
        ]);
    }
}
