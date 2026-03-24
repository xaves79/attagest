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
        Schema::table('stocks_produits_finis', function (Blueprint $table) {
            $table->foreign(['etuvage_id'], 'fk_stocks_etuvage')->references(['id'])->on('etuvages')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['lot_paddy_id'], 'fk_stocks_lot_paddy')->references(['id'])->on('lots_paddy')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['agent_id'], 'stocks_produits_finis_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['decorticage_id'], 'stocks_produits_finis_decorticage_id_fkey')->references(['id'])->on('decorticages')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['variete_rice_id'], 'stocks_produits_finis_variete_rice_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks_produits_finis', function (Blueprint $table) {
            $table->dropForeign('fk_stocks_etuvage');
            $table->dropForeign('fk_stocks_lot_paddy');
            $table->dropForeign('stocks_produits_finis_agent_id_fkey');
            $table->dropForeign('stocks_produits_finis_decorticage_id_fkey');
            $table->dropForeign('stocks_produits_finis_variete_rice_id_fkey');
        });
    }
};
