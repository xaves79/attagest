<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pieces_comptables', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();        // ex: ACHAT-PADDY, VENTE-RIZ
            $table->string('libelle');               // ex: "Achat paddy", "Vente riz"
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pieces_comptables');
    }
};
