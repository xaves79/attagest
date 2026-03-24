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
        Schema::create('varietes_rice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 50)->unique('varietes_rice_nom_key');
            $table->string('code_variete', 10)->index('idx_varietes_code');
            $table->string('type_riz', 30)->nullable()->default('Paddy');
            $table->decimal('rendement_estime', 6)->nullable();
            $table->integer('duree_cycle')->nullable();
            $table->string('origine', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));

            $table->unique(['code_variete'], 'varietes_rice_code_variete_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varietes_rice');
    }
};
