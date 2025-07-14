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
        Schema::create('dossier_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('type_document'); // radiographie, analyse, prescription, etc.
            $table->string('fichier')->nullable(); // chemin vers le fichier
            $table->string('nom_fichier')->nullable(); // nom original du fichier
            $table->string('taille_fichier')->nullable(); // taille en bytes
            $table->string('mime_type')->nullable(); // type MIME du fichier
            $table->enum('statut', ['actif', 'archive', 'supprime'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_medicaux');
    }
};
