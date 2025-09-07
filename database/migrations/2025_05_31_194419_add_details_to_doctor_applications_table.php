<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor_applications', 'specialite')) {
                $table->string('specialite')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'ville')) {
                $table->string('ville')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'langue')) {
                $table->enum('langue', ['fr', 'en'])->default('fr');
            } else {
                // Mettre à jour le type de la colonne si elle existe déjà
                $table->enum('langue', ['fr', 'en'])->default('fr')->change();
            }
            if (!Schema::hasColumn('doctor_applications', 'lieu_travail')) {
                $table->string('lieu_travail')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'matricule_professionnel')) {
                $table->string('matricule_professionnel')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'numero_licence')) {
                $table->string('numero_licence')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'experience')) {
                $table->text('experience')->nullable();
            }
            if (!Schema::hasColumn('doctor_applications', 'expertise')) {
                $table->string('expertise')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $columns = [
                'specialite',
                'ville',
                'langue',
                'lieu_travail',
                'matricule_professionnel',
                'numero_licence',
                'experience',
                'expertise'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('doctor_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }


    /**
     * Reverse the migrations.
     */

};
