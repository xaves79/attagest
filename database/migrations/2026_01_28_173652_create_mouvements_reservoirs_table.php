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
        Schema::create('mouvements_reservoirs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reservoir_id')->nullable();
            $table->bigInteger('stock_id')->nullable();
            $table->string('type_stock', 20)->nullable();
            $table->decimal('quantite_kg', 10);
            $table->string('type_mouvement', 20)->nullable()->default('entree');
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('decorticage_id')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_reservoirs');
    }
};
