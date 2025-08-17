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
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->date('date_rdv');
            $table->time('heure_rdv');
            $table->string('type_consultation')->nullable();
            $table->text('motif')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'annule', 'termine'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index(['medecin_id', 'date_rdv', 'statut']);
            $table->index(['patient_id', 'date_rdv']);
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
