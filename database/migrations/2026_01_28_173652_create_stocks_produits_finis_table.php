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
        Schema::create('stocks_produits_finis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_stock', 25)->unique('stocks_produits_finis_code_stock_key');
            $table->bigInteger('decorticage_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('variete_rice_id')->nullable()->index('idx_stocks_variete');
            $table->enum('type_produit', ['riz_blanc', 'son', 'brisures'])->index('idx_stocks_type');
            $table->decimal('quantite_kg', 10);
            $table->timestampTz('created_at')->nullable()->default(DB::raw("now()"));
            $table->bigInteger('lot_paddy_id')->nullable()->index('idx_stocks_lot_paddy');
            $table->bigInteger('etuvage_id')->nullable()->index('idx_stocks_etuvage');
            $table->timestampTz('updated_at')->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_produits_finis');
    }
};
