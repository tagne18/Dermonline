<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne langue pour utiliser un ENUM avec les valeurs autorisées
        \DB::statement("ALTER TABLE users MODIFY COLUMN langue ENUM('fr', 'en', 'both') DEFAULT 'fr'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à un type de colonne string standard si nécessaire
        \DB::statement("ALTER TABLE users MODIFY COLUMN langue VARCHAR(10) DEFAULT 'fr'");
    }
};
