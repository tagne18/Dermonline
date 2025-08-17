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
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero_licence')->nullable()->after('langue');
            $table->string('matricule_professionnel')->nullable()->after('numero_licence');
            $table->string('experience_professionnelle')->nullable()->after('matricule_professionnel');
            $table->string('domaine_expertise')->nullable()->after('experience_professionnelle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_licence', 'matricule_professionnel', 'experience_professionnelle', 'domaine_expertise']);
        });
    }
};
