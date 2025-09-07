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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('fichier_pdf')->nullable()->after('fichier');
            $table->dateTime('date_emission')->nullable()->after('date_prescription');
            $table->text('commentaires')->nullable()->after('description');
            $table->string('statut', 20)->default('active')->after('commentaires');
            
            // Index pour les recherches et les tris
            $table->index('date_emission');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['fichier_pdf', 'date_emission', 'commentaires', 'statut']);
            
            // Suppression des index
            $table->dropIndex(['date_emission']);
            $table->dropIndex(['statut']);
        });
    }
};
