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
    Schema::table('demandes', function (Blueprint $table) {
        $table->unsignedBigInteger('etudiant_id')->after('id');
        $table->unsignedBigInteger('encadrant_id')->nullable()->after('etudiant_id');
        $table->string('statut')->default('en attente')->after('encadrant_id');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            //
        });
    }
};
