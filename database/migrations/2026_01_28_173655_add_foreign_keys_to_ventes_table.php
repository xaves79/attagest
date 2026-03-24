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
        Schema::table('ventes', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'ventes_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['client_id'], 'ventes_client_id_fkey')->references(['id'])->on('clients')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['point_vente_id'], 'ventes_point_vente_id_fkey')->references(['id'])->on('points_vente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['transfert_id'], 'ventes_transfert_id_fkey')->references(['id'])->on('transferts_points_vente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropForeign('ventes_agent_id_fkey');
            $table->dropForeign('ventes_client_id_fkey');
            $table->dropForeign('ventes_point_vente_id_fkey');
            $table->dropForeign('ventes_transfert_id_fkey');
        });
    }
};
