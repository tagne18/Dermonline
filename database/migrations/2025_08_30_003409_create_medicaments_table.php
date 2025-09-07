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
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->string('categorie', 50)->nullable();
            $table->string('forme_galenique', 50)->nullable();
            $table->string('dosage', 50)->nullable();
            $table->string('unite', 20)->nullable();
            $table->boolean('sur_ordonnance')->default(true);
            $table->text('contre_indications')->nullable();
            $table->text('effets_secondaires')->nullable();
            $table->text('interactions')->nullable();
            $table->string('code_cip', 20)->nullable()->unique();
            $table->string('code_cip13', 20)->nullable()->unique();
            $table->string('taux_remboursement', 10)->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour les recherches
            $table->index('nom');
            $table->index('categorie');
            $table->index('code_cip');
            $table->index('code_cip13');
        });
        
        // Table de liaison entre les prescriptions et les médicaments
        Schema::create('medicament_prescription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicament_id')->constrained()->onDelete('cascade');
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->string('posologie');
            $table->string('duree');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('quantite')->default(1);
            $table->timestamps();
            
            // Clé unique pour éviter les doublons
            $table->unique(['medicament_id', 'prescription_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicament_prescription');
        Schema::dropIfExists('medicaments');
    }
};
