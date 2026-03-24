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
        Schema::create('lots_paddy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_lot', 12)->index('idx_lots_code');
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('fournisseur_id')->nullable();
            $table->bigInteger('variete_id')->nullable();
            $table->bigInteger('localite_id')->nullable();
            $table->bigInteger('entreprise_id')->nullable();
            $table->date('date_achat');
            $table->decimal('quantite_achat_kg', 10, 1);
            $table->decimal('prix_achat_unitaire_fcfa', 10, 0);
            $table->decimal('montant_achat_total_fcfa', 12);
            $table->string('statut', 20)->nullable()->default('stock_paddy')->index('idx_lots_statut');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->decimal('quantite_restante_kg', 10)->nullable()->default(0);

            $table->unique(['code_lot'], 'lots_paddy_code_lot_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots_paddy');
    }
};
