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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            
            // Informations de paiement
            $table->decimal('montant', 10, 2);
            $table->string('devise', 3)->default('XAF');
            $table->string('methode_paiement'); // Carte, Mobile Money, Virement, etc.
            $table->string('reference')->unique();
            $table->string('statut')->default('en_attente'); // en_attente, paye, echoue, rembourse
            
            // Dates importantes
            $table->dateTime('date_paiement');
            $table->dateTime('date_validation')->nullable();
            
            // Détails supplémentaires
            $table->json('details')->nullable();
            
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index(['medecin_id', 'statut', 'date_paiement']);
            $table->index(['patient_id', 'statut']);
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
