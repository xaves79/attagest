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
        Schema::create('decorticages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_decorticage', 25)->unique('decorticages_code_decorticage_key');
            $table->bigInteger('etuvage_id')->nullable();
            $table->bigInteger('agent_id')->index('idx_decorticages_agent_id');
            $table->bigInteger('lot_paddy_id')->nullable();
            $table->decimal('quantite_paddy_entree_kg', 10);
            $table->timestamp('date_debut_decorticage')->index('idx_decorticages_date');
            $table->decimal('quantite_riz_blanc_kg', 10)->nullable();
            $table->decimal('quantite_son_kg', 10)->nullable();
            $table->decimal('quantite_brise_kg', 10)->nullable();
            $table->decimal('taux_rendement', 5)->nullable();
            $table->timestamp('date_fin_decorticage')->nullable();
            $table->string('statut', 15)->nullable()->default('en_cours')->index('idx_decorticages_statut');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->bigInteger('lot_riz_etuve_id')->nullable();
            $table->timestampTz('date_terminaison')->nullable();
            $table->bigInteger('variete_rice_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorticages');
    }
};
