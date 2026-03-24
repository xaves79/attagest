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
        Schema::create('etuvages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_etuvage', 25)->unique('etuvages_code_etuvage_key');
            $table->bigInteger('lot_paddy_id');
            $table->bigInteger('agent_id')->index('idx_etuvages_agent_id');
            $table->decimal('quantite_paddy_entree_kg', 10);
            $table->timestamp('date_debut_etuvage')->index('idx_etuvages_date');
            $table->decimal('temperature_etuvage', 5)->nullable();
            $table->integer('duree_etuvage_minutes')->nullable();
            $table->timestamp('date_fin_etuvage')->nullable();
            $table->string('statut', 15)->nullable()->default('en_cours')->index('idx_etuvages_statut');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->string('code_stock_paddy', 25)->nullable()->index('idx_etuvages_code_stock_paddy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etuvages');
    }
};
