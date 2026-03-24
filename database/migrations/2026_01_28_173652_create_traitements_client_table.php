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
        Schema::create('traitements_client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_traitement', 20)->unique('traitements_client_code_traitement_key');
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('variete_id')->nullable();
            $table->bigInteger('localite_id')->nullable();
            $table->bigInteger('point_vente_id')->nullable();
            $table->decimal('quantite_paddy_kg', 10);
            $table->date('date_reception')->index('idx_traitements_date');
            $table->decimal('quantite_riz_blanc_kg', 10)->nullable();
            $table->decimal('quantite_son_kg', 10)->nullable();
            $table->decimal('taux_rendement', 5)->nullable();
            $table->decimal('prix_traitement_par_kg')->nullable();
            $table->decimal('montant_traitement_fcfa', 12)->nullable();
            $table->string('statut', 20)->nullable()->default('en_attente')->index('idx_traitements_client');
            $table->text('observations')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traitements_client');
    }
};
