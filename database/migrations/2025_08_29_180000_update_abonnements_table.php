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
        Schema::table('abonnements', function (Blueprint $table) {
            // Ajouter la colonne medecin_id si elle n'existe pas
            if (!Schema::hasColumn('abonnements', 'medecin_id')) {
                $table->foreignId('medecin_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }
            
            // Ajouter la colonne reference si elle n'existe pas
            if (!Schema::hasColumn('abonnements', 'reference')) {
                $table->string('reference')->nullable()->after('type');
            }
            
            // Ajouter la colonne montant si elle n'existe pas
            if (!Schema::hasColumn('abonnements', 'montant')) {
                $table->decimal('montant', 10, 2)->default(0)->after('reference');
            }
            
            // Ajouter la colonne transaction_id si elle n'existe pas
            if (!Schema::hasColumn('abonnements', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('montant');
            }
            
            // Modifier le type de la colonne type pour permettre des valeurs plus longues
            $table->string('type', 50)->change();
            
            // Ajouter des index pour améliorer les performances
            $table->index(['user_id', 'medecin_id']);
            $table->index('reference');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['user_id', 'medecin_id']);
            $table->dropIndex(['reference']);
            $table->dropIndex(['transaction_id']);
            
            // Ne pas supprimer les colonnes pour éviter les pertes de données
            // Ces colonnes seront conservées mais peuvent être supprimées manuellement si nécessaire
        });
    }
};
