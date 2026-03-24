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
        Schema::create('recus_fournisseurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_recu', 20)->unique('recus_fournisseurs_numero_recu_key');
            $table->bigInteger('fournisseur_id')->index('idx_recus_fournisseurs_fournisseur');
            $table->date('date_recu');
            $table->decimal('montant_total', 12);
            $table->boolean('paye')->nullable()->default(false)->index('idx_recus_fournisseurs_paye');
            $table->date('date_limite_paiement')->nullable()->index('idx_recus_fournisseurs_limite');
            $table->integer('jours_credit')->nullable()->default(60);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->enum('mode_paiement', ['espece', 'cheque', 'mobile_money', 'credit', 'virement'])->nullable()->default('espece')->index('idx_recus_fournisseurs_mode_paiement');
            $table->decimal('acompte', 12)->nullable()->default(0)->index('idx_recus_fournisseurs_acompte');
            $table->decimal('solde_du', 12)->nullable()->default(0);
            $table->text('reference_entreprise')->nullable();
            $table->bigInteger('variete_rice_id')->nullable();
            $table->bigInteger('entreprise_id')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recus_fournisseurs');
    }
};
