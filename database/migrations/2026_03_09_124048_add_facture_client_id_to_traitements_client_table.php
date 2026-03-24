<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('traitements_client', function (Blueprint $table) {
        $table->foreignId('facture_client_id')->nullable()->constrained('factures_clients')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('traitements_client', function (Blueprint $table) {
        $table->dropForeign(['facture_client_id']);
        $table->dropColumn('facture_client_id');
    });
}
};
