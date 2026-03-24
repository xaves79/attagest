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
        Schema::create('ventes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_vente', 25)->unique('ventes_code_vente_key');
            $table->bigInteger('transfert_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('point_vente_id')->nullable();
            $table->decimal('quantite_vendue_kg', 10, 0);
            $table->decimal('prix_vente_unitaire_fcfa', 10, 0);
            $table->decimal('montant_vente_total_fcfa', 12, 0);
            $table->string('type_produit', 20)->nullable();
            $table->date('date_vente');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
