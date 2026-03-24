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
        Schema::create('reservoirs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom_reservoir', 50)->unique('reservoirs_nom_reservoir_key');
            $table->enum('type_produit', ['riz_blanc', 'son', 'brisures']);
            $table->decimal('capacite_max_kg', 10);
            $table->bigInteger('point_vente_id')->nullable();
            $table->decimal('quantite_actuelle_kg', 10)->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservoirs');
    }
};
