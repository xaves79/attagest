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
        Schema::table('paiements_factures', function (Blueprint $table) {
            $table->foreign(['facture_id'], 'paiements_factures_facture_id_fkey')->references(['id'])->on('factures_clients')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements_factures', function (Blueprint $table) {
            $table->dropForeign('paiements_factures_facture_id_fkey');
        });
    }
};
