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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('motif')->nullable()->after('type');
            $table->text('description')->nullable()->after('motif');
            $table->string('patient_name')->nullable()->after('description');
            $table->string('patient_phone')->nullable()->after('patient_name');
            $table->json('photos')->nullable()->after('patient_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['motif', 'description', 'patient_name', 'patient_phone', 'photos']);
        });
    }
}; 