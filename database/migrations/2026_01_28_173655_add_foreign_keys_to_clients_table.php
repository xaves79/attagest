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
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign(['localite_id'], 'clients_localite_id_fkey')->references(['id'])->on('localites')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['point_vente_id'], 'clients_point_vente_id_fkey')->references(['id'])->on('points_vente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_localite_id_fkey');
            $table->dropForeign('clients_point_vente_id_fkey');
        });
    }
};
