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
        Schema::table('transferts_points_vente', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'transferts_points_vente_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['point_vente_id'], 'transferts_points_vente_point_vente_id_fkey')->references(['id'])->on('points_vente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transferts_points_vente', function (Blueprint $table) {
            $table->dropForeign('transferts_points_vente_agent_id_fkey');
            $table->dropForeign('transferts_points_vente_point_vente_id_fkey');
        });
    }
};
