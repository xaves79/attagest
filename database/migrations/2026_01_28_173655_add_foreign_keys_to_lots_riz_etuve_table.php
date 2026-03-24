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
        Schema::table('lots_riz_etuve', function (Blueprint $table) {
            $table->foreign(['provenance_etuvage_id'], 'fk_lots_riz_etuve_etuvage')->references(['id'])->on('etuvages')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['variete_rice_id'], 'lots_riz_etuve_variete_rice_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lots_riz_etuve', function (Blueprint $table) {
            $table->dropForeign('fk_lots_riz_etuve_etuvage');
            $table->dropForeign('lots_riz_etuve_variete_rice_id_fkey');
        });
    }
};
