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
        Schema::create('lots_riz_etuve', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_lot', 25)->unique('lots_riz_etuve_code_lot_key');
            $table->decimal('quantite_entree_kg', 10);
            $table->decimal('quantite_restante_kg', 10)->default(0)->index('idx_lots_riz_etuve_restante');
            $table->bigInteger('provenance_etuvage_id')->nullable()->index('idx_lots_riz_etuve_provenance');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->bigInteger('variete_rice_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots_riz_etuve');
    }
};
