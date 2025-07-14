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
            $table->string('specialite')->nullable()->after('profession');
            $table->string('ville')->nullable()->after('specialite');
            $table->string('lieu_travail')->nullable()->after('ville');
            $table->enum('langue', ['fr', 'en'])->nullable()->after('lieu_travail');
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
