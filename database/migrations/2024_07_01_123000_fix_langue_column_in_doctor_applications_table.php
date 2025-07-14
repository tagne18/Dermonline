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
            $table->string('langue', 50)->change(); // Augmenter la taille à 50 caractères
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->string('langue', 10)->change(); // Revenir à la taille originale
        });
    }
}; 