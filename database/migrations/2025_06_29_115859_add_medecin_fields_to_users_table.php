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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'specialite')) {
                $table->string('specialite')->nullable()->after('profession');
            }
            if (!Schema::hasColumn('users', 'ville')) {
                $table->string('ville')->nullable()->after('specialite');
            }
            if (!Schema::hasColumn('users', 'lieu_travail')) {
                $table->string('lieu_travail')->nullable()->after('ville');
            }
            if (!Schema::hasColumn('users', 'langue')) {
                $table->enum('langue', ['fr', 'en'])->default('fr')->after('lieu_travail');
            } else {
                // Mettre à jour le type de la colonne si elle existe déjà
                $table->enum('langue', ['fr', 'en'])->default('fr')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['specialite', 'ville', 'lieu_travail', 'langue']);
        });
    }
};
