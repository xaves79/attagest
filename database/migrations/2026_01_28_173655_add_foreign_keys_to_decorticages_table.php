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
        Schema::table('decorticages', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'decorticages_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['etuvage_id'], 'decorticages_etuvage_id_fkey')->references(['id'])->on('etuvages')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['lot_paddy_id'], 'decorticages_lot_paddy_id_fkey')->references(['id'])->on('lots_paddy')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['variete_rice_id'], 'decorticages_variete_rice_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['lot_riz_etuve_id'], 'fk_decorticages_lot_riz_etuve')->references(['id'])->on('lots_riz_etuve')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decorticages', function (Blueprint $table) {
            $table->dropForeign('decorticages_agent_id_fkey');
            $table->dropForeign('decorticages_etuvage_id_fkey');
            $table->dropForeign('decorticages_lot_paddy_id_fkey');
            $table->dropForeign('decorticages_variete_rice_id_fkey');
            $table->dropForeign('fk_decorticages_lot_riz_etuve');
        });
    }
};
