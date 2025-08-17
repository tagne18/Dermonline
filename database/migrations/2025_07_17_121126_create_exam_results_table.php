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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
$table->unsignedBigInteger('patient_id');
$table->unsignedBigInteger('medecin_id');
$table->string('titre');
$table->text('description')->nullable();
$table->string('fichier')->nullable();
$table->date('date_examen');
$table->timestamps();

// Clés étrangères
$table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
$table->foreign('medecin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
