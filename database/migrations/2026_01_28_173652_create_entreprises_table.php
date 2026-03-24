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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 100);
            $table->string('sigle', 10)->unique('entreprises_sigle_key');
            $table->string('code_entreprise', 10)->unique('entreprises_code_entreprise_key');
            $table->string('whatsapp', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('logo')->nullable();
            $table->text('adresse')->nullable();
            $table->string('gerant_nom', 100)->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));

            $table->index(['code_entreprise'], 'idx_entreprises_code');
            $table->index(['sigle'], 'idx_entreprises_sigle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
