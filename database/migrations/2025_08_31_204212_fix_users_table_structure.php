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
        \DB::statement("ALTER TABLE users MODIFY COLUMN langue ENUM('fr', 'en', 'both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fr'");
        
        // Ajouter les colonnes manquantes si elles n'existent pas
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'specialite')) {
                $table->string('specialite')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'ville')) {
                $table->string('ville')->nullable()->after('specialite');
            }
            if (!Schema::hasColumn('users', 'lieu_travail')) {
                $table->string('lieu_travail')->nullable()->after('langue');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'medecin', 'patient'])->default('patient')->after('lieu_travail');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à un type de colonne string standard si nécessaire
        \DB::statement("ALTER TABLE users MODIFY COLUMN langue VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fr'");
        
        // Supprimer les colonnes ajoutées si nécessaire
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'phone',
                'specialite',
                'ville',
                'lieu_travail',
                'role'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
