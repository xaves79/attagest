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
        Schema::table('stocks_paddy', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'stocks_paddy_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['lot_paddy_id'], 'stocks_paddy_lot_paddy_id_fkey')->references(['id'])->on('lots_paddy')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks_paddy', function (Blueprint $table) {
            $table->dropForeign('stocks_paddy_agent_id_fkey');
            $table->dropForeign('stocks_paddy_lot_paddy_id_fkey');
        });
    }
};
