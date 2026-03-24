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
        Schema::create('stocks_paddy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_stock', 25)->unique('stocks_paddy_code_stock_key');
            $table->bigInteger('lot_paddy_id')->nullable()->index('idx_stocks_paddy_lot');
            $table->bigInteger('agent_id')->nullable();
            $table->decimal('quantite_stock_kg', 10, 1);
            $table->string('emplacement', 50)->nullable()->default('Entrepôt Central');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->decimal('quantite_restante_kg', 10)->default(0)->index('idx_stocks_paddy_quantite_restante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_paddy');
    }
};
