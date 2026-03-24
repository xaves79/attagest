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
        Schema::create('paiements_factures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('facture_id');
            $table->string('numero_paiement', 20)->unique('paiements_factures_numero_paiement_key');
            $table->decimal('montant_paye', 12);
            $table->date('date_paiement');
            $table->enum('mode_paiement', ['espèces', 'mobile_money', 'chèque', 'virement'])->nullable()->default('espèces');
            $table->text('description')->nullable();
            $table->enum('statut', ['paye', 'annule', 'reporte'])->nullable()->default('paye')->index('idx_paiements_statut');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));

            $table->index(['facture_id', 'date_paiement'], 'idx_paiements_facture_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements_factures');
    }
};
