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
        Schema::table('sacs_produits_finis', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'sacs_produits_finis_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['stock_produit_fini_id'], 'sacs_produits_finis_stock_produit_fini_id_fkey')->references(['id'])->on('stocks_produits_finis')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sacs_produits_finis', function (Blueprint $table) {
            $table->dropForeign('sacs_produits_finis_agent_id_fkey');
            $table->dropForeign('sacs_produits_finis_stock_produit_fini_id_fkey');
        });
    }
};
