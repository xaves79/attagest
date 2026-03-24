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
        Schema::table('lots_paddy', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'lots_paddy_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['entreprise_id'], 'lots_paddy_entreprise_id_fkey')->references(['id'])->on('entreprises')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['fournisseur_id'], 'lots_paddy_fournisseur_id_fkey')->references(['id'])->on('fournisseurs')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['localite_id'], 'lots_paddy_localite_id_fkey')->references(['id'])->on('localites')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['variete_id'], 'lots_paddy_variete_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lots_paddy', function (Blueprint $table) {
            $table->dropForeign('lots_paddy_agent_id_fkey');
            $table->dropForeign('lots_paddy_entreprise_id_fkey');
            $table->dropForeign('lots_paddy_fournisseur_id_fkey');
            $table->dropForeign('lots_paddy_localite_id_fkey');
            $table->dropForeign('lots_paddy_variete_id_fkey');
        });
    }
};
