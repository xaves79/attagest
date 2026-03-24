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
        Schema::table('points_vente', function (Blueprint $table) {
            $table->foreign(['agent_id'], 'points_vente_agent_id_fkey')->references(['id'])->on('agents')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['localite_id'], 'points_vente_localite_id_fkey')->references(['id'])->on('localites')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points_vente', function (Blueprint $table) {
            $table->dropForeign('points_vente_agent_id_fkey');
            $table->dropForeign('points_vente_localite_id_fkey');
        });
    }
};
