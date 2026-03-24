<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stocks_paddy', function (Blueprint $table) {
            $table->unique('lot_paddy_id', 'stocks_paddy_lot_paddy_id_unique');
        });
    }

    public function down()
    {
        Schema::table('stocks_paddy', function (Blueprint $table) {
            $table->dropUnique('stocks_paddy_lot_paddy_id_unique');
        });
    }
};