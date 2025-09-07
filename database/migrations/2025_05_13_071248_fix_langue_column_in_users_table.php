<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Forcer la modification sans vérification de données
        DB::statement('ALTER TABLE users MODIFY langue VARCHAR(50) NOT NULL DEFAULT "fr"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('langue', 10)->change(); // Revenir à la taille originale
        });
    }
}; 