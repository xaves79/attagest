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
        Schema::table('traitements_client', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'traitements_client_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['client_id'], 'traitements_client_client_id_fkey')->references(['id'])->on('clients')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['localite_id'], 'traitements_client_localite_id_fkey')->references(['id'])->on('localites')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['point_vente_id'], 'traitements_client_point_vente_id_fkey')->references(['id'])->on('points_vente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['variete_id'], 'traitements_client_variete_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traitements_client', function (Blueprint $table) {
            $table->dropForeign('traitements_client_agent_id_fkey');
            $table->dropForeign('traitements_client_client_id_fkey');
            $table->dropForeign('traitements_client_localite_id_fkey');
            $table->dropForeign('traitements_client_point_vente_id_fkey');
            $table->dropForeign('traitements_client_variete_id_fkey');
        });
    }
};
