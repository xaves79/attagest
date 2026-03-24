<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks_sacs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_sac_id')->constrained('stocks_sacs')->onDelete('cascade');
            $table->foreignId('sac_id')->constrained('sacs_produits_finis')->onDelete('cascade');
            $table->integer('quantite')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks_sacs');
    }
};