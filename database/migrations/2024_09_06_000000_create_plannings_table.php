<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->unsignedSmallInteger('duree_consultation')->default(30)->comment('Durée en minutes');
            $table->timestamps();
            
            // Contrainte d'unicité pour s'assurer qu'un médecin n'a qu'un seul planning
            $table->unique('medecin_id');
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
