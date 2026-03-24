<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sacs_produits_finis', function (Blueprint $table) {
            $table->comment('Sacs Riz Blanc/Brisures - Traçabilité complète');
            $table->increments('id');
            $table->string('code_sac', 25)->unique('sacs_produits_finis_code_sac_key');
            $table->integer('stock_produit_fini_id')->nullable()->index('idx_sacs_stock');
            $table->enum('type_sac', ['riz_blanc', 'brisures']);
            $table->decimal('poids_sac_kg');
            $table->integer('nombre_sacs')->default(1);
            $table->decimal('poids_total_kg')->nullable()->storedAs('(poids_sac_kg * (nombre_sacs)::numeric)');
            $table->string('variete_code', 50)->nullable();
            $table->string('provenance_decorticage', 25);
            $table->string('provenance_etuvage', 25)->nullable();
            $table->string('provenance_paddy', 25)->nullable();
            $table->decimal('rendement_pourcent', 5)->nullable();
            $table->timestamp('date_emballage')->useCurrent();
            $table->integer('agent_id')->nullable()->index('idx_sacs_agent');
            $table->enum('statut', ['disponible', 'en_transfert', 'transfere'])->nullable()->default('disponible');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->index(['type_sac', 'statut'], 'idx_sacs_type_statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sacs_produits_finis');
    }
};
