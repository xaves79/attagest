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
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('matricule', 10)->index('idx_agents_matricule');
            $table->string('whatsapp', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('photo')->nullable();
            $table->bigInteger('entreprise_id')->nullable()->index('idx_agents_entreprise');
            $table->date('date_embauche')->default(DB::raw('CURRENT_DATE'));
            $table->boolean('actif')->nullable()->default(true)->index('idx_agents_actif');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->bigInteger('poste_id')->nullable()->index('idx_agents_poste_id');
            $table->string('nom_complet', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
