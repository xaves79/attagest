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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type_personne', ['PHYSIQUE', 'MORALE'])->index('idx_fournisseurs_type');
            $table->string('nom', 100);
            $table->string('prenom', 50)->nullable();
            $table->string('raison_sociale', 150)->nullable();
            $table->string('sigle', 10)->nullable()->unique('fournisseurs_sigle_key');
            $table->string('code_fournisseur', 10)->unique('fournisseurs_code_fournisseur_key');
            $table->string('whatsapp', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->bigInteger('localite_id')->nullable()->index('idx_fournisseurs_localite');
            $table->string('type_fournisseur', 30)->nullable()->default('Producteur');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->string('email', 100)->nullable();

            $table->index(['code_fournisseur'], 'idx_fournisseurs_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
