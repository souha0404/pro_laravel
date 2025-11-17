<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade'); // Obligatoire
            $table->string('titre');
            $table->string('fichier');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rapports');
    }
};