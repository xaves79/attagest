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
        Schema::create('lignes_facture', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('facture_id')->index('idx_lignes_facture_id');
            $table->bigInteger('article_id')->index('idx_lignes_article_id');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 0);
            $table->decimal('montant', 10, 0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_facture');
    }
};
