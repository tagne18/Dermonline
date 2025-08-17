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
        Schema::create('new_annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu');
            $table->string('image_path')->nullable();
            $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->timestamp('date_publication')->nullable();
            $table->timestamps();
            
            // Index pour les recherches fréquentes
            $table->index('user_id');
            $table->index('statut');
            $table->index('date_publication');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_annonces');
    }
};
