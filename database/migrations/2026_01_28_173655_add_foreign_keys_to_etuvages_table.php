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
        Schema::table('etuvages', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'etuvages_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['lot_paddy_id'], 'etuvages_lot_paddy_id_fkey')->references(['id'])->on('lots_paddy')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etuvages', function (Blueprint $table) {
            $table->dropForeign('etuvages_agent_id_fkey');
            $table->dropForeign('etuvages_lot_paddy_id_fkey');
        });
    }
};
