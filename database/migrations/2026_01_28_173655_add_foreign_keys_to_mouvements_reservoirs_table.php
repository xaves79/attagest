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
        Schema::table('mouvements_reservoirs', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'mouvements_reservoirs_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['decorticage_id'], 'mouvements_reservoirs_decorticage_id_fkey')->references(['id'])->on('decorticages')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['reservoir_id'], 'mouvements_reservoirs_reservoir_id_fkey')->references(['id'])->on('reservoirs')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mouvements_reservoirs', function (Blueprint $table) {
            $table->dropForeign('mouvements_reservoirs_agent_id_fkey');
            $table->dropForeign('mouvements_reservoirs_decorticage_id_fkey');
            $table->dropForeign('mouvements_reservoirs_reservoir_id_fkey');
        });
    }
};
