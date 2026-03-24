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
        Schema::table('reservoirs', function (Blueprint $table) {
            $table->foreign(['point_vente_id'], 'reservoirs_point_vente_id_fkey')->references(['id'])->on('points_vente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservoirs', function (Blueprint $table) {
            $table->dropForeign('reservoirs_point_vente_id_fkey');
        });
    }
};
