<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->string('specialite')->nullable();
            $table->string('ville')->nullable();
            $table->enum('langue', ['fr', 'en'])->nullable();
            $table->string('lieu_travail')->nullable();
            $table->string('matricule_professionnel')->nullable();
            $table->string('numero_licence')->nullable();
            $table->text('experience')->nullable();
            $table->string('expertise')->nullable();
        });
    }

    public function down()
    {
        Schema::create('doctor_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('specialite');
            $table->string('ville');
            $table->string('langue');
            $table->string('lieu_travail')->nullable();
            $table->string('matricule_professionnel')->nullable();
            $table->string('numero_licence')->nullable();
            $table->text('experience')->nullable();
            $table->string('expertise')->nullable();
            $table->text('cv')->nullable();
            $table->timestamps();
        });

        
    }


    /**
     * Reverse the migrations.
     */

};
