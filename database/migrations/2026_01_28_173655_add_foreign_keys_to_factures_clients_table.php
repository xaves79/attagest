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
        Schema::table('factures_clients', function (Blueprint $table) {
            $table->foreign(['client_id'], 'fk_factures_clients_client')->references(['id'])->on('clients')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures_clients', function (Blueprint $table) {
            $table->dropForeign('fk_factures_clients_client');
        });
    }
};
