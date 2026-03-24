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
        Schema::create('points_vente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 100);
            $table->string('code_point', 10)->unique('points_vente_code_point_key');
            $table->bigInteger('agent_id')->nullable()->index('idx_points_vente_agent');
            $table->bigInteger('localite_id')->nullable()->index('idx_points_vente_localite');
            $table->text('adresse')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('actif')->nullable()->default(true)->index('idx_points_vente_actif');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_vente');
    }
};
