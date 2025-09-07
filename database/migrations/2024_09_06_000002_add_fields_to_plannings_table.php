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
        Schema::table('plannings', function (Blueprint $table) {
            // Supprimer la colonne existante qui n'est plus nécessaire
            $table->dropColumn('duree_consultation');
            
            // Ajouter les nouvelles colonnes
            $table->string('titre')->after('medecin_id');
            $table->date('date_consultation')->after('titre');
            $table->time('heure_debut')->after('date_consultation');
            $table->time('heure_fin')->after('heure_debut');
            $table->unsignedInteger('duree_consultation')->default(30)->comment('Durée en minutes')->after('heure_fin');
            $table->enum('type_consultation', ['presentiel', 'en_ligne', 'hybride'])->default('presentiel')->after('duree_consultation');
            $table->decimal('prix', 10, 2)->default(0)->after('type_consultation');
            $table->text('description')->nullable()->after('prix');
            $table->enum('statut', ['planifie', 'confirme', 'annule', 'termine'])->default('planifie')->after('description');
            
            // Ajouter des index pour les recherches fréquentes
            $table->index('date_consultation');
            $table->index('type_consultation');
            $table->index('statut');
        });
        
        // Supprimer la table creneau_horaires qui n'est plus nécessaire
        Schema::dropIfExists('creneau_horaires');
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::table('plannings', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'titre',
                'date_consultation',
                'heure_debut',
                'heure_fin',
                'duree_consultation',
                'type_consultation',
                'prix',
                'description',
                'statut'
            ]);
            
            // Recréer la colonne d'origine
            $table->unsignedSmallInteger('duree_consultation')->default(30)->comment('Durée en minutes');
        });
    }
};
