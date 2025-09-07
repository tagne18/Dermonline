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
        Schema::table('doctor_applications', function (Blueprint $table) {
            // Supprimer les colonnes existantes si elles existent
            if (Schema::hasColumn('doctor_applications', 'specialite')) {
                $table->dropColumn('specialite');
            }
            if (Schema::hasColumn('doctor_applications', 'ville')) {
                $table->dropColumn('ville');
            }
            if (Schema::hasColumn('doctor_applications', 'langue')) {
                $table->dropColumn('langue');
            }
            if (Schema::hasColumn('doctor_applications', 'lieu_travail')) {
                $table->dropColumn('lieu_travail');
            }
            if (Schema::hasColumn('doctor_applications', 'matricule_professionnel')) {
                $table->dropColumn('matricule_professionnel');
            }
            if (Schema::hasColumn('doctor_applications', 'numero_licence')) {
                $table->dropColumn('numero_licence');
            }
            if (Schema::hasColumn('doctor_applications', 'experience')) {
                $table->dropColumn('experience');
            }
            if (Schema::hasColumn('doctor_applications', 'expertise')) {
                $table->dropColumn('expertise');
            }
            
            // Ajouter les colonnes avec les bonnes propriétés
            $table->string('specialite')->after('cv')->nullable();
            $table->string('ville')->after('specialite')->nullable();
            $table->enum('langue', ['fr', 'en', 'both'])->default('fr')->after('ville');
            $table->string('lieu_travail')->after('langue')->nullable();
            $table->string('matricule_professionnel')->after('lieu_travail')->nullable();
            $table->string('numero_licence')->after('matricule_professionnel')->nullable();
            $table->string('experience')->after('numero_licence')->nullable();
            $table->text('expertise')->after('experience')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'specialite',
                'ville',
                'langue',
                'lieu_travail',
                'matricule_professionnel',
                'numero_licence',
                'experience',
                'expertise'
            ]);
        });
    }
};
