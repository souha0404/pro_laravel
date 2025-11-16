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
    Schema::table('rapports', function (Blueprint $table) {
        $table->unsignedBigInteger('etudiant_id')->after('id'); // obligatoire
        $table->unsignedBigInteger('encadrant_id')->nullable()->after('etudiant_id'); // facultatif
    });
}

public function down()
{
    Schema::table('rapports', function (Blueprint $table) {
        $table->dropColumn(['etudiant_id', 'encadrant_id']);
    });
}

};
