<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mouvements_sacs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_sac_id')->constrained('stocks_sacs')->onDelete('cascade');
            $table->integer('quantite');
            $table->string('type_mouvement', 10); // 'entree' ou 'sortie'
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->dateTime('date_mouvement');
            $table->string('unique_hash', 50)->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mouvements_sacs');
    }
};