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
        Schema::create('fichier_joints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->string('nom_original');
            $table->string('chemin');
            $table->string('mime_type');
            $table->string('extension', 10);
            $table->unsignedBigInteger('taille');
            $table->timestamps();
            
            // Index pour les performances de recherche
            $table->index('prescription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichier_joints');
    }
};
