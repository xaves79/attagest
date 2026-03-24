<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transferts_points_vente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_transfert', 25)->unique('transferts_points_vente_code_transfert_key');
            $table->bigInteger('stock_riz_id')->nullable();
            $table->bigInteger('point_vente_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->decimal('quantite_transferee_kg', 10);
            $table->date('date_transfert');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferts_points_vente');
    }
};
