<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('recus_fournisseurs', function (Blueprint $table) {
            $table->foreignId('achat_paddy_id')
                ->nullable()
                ->constrained('lots_paddy')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('recus_fournisseurs', function (Blueprint $table) {
            $table->dropForeign(['achat_paddy_id']);
            $table->dropColumn('achat_paddy_id');
        });
    }
};