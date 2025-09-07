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
        Schema::create('creneau_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_id')->constrained()->onDelete('cascade');
            $table->enum('jour', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->time('pause_debut')->nullable();
            $table->time('pause_fin')->nullable();
            $table->enum('type_consultation', ['presentiel', 'en_ligne', 'les_deux'])->default('presentiel');
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            
            // Contrainte d'unicité pour s'assurer qu'un jour n'apparaît qu'une seule fois par planning
            $table->unique(['planning_id', 'jour']);
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creneau_horaires');
    }
};
