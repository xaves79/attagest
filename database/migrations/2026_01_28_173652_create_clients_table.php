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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type_client', ['GROSSISTE', 'PARTICULIER', 'RESTAURANT', 'HOTEL', 'MARCHE', 'DETAILLANT'])->index('idx_clients_type');
            $table->string('nom', 100);
            $table->string('prenom', 50)->nullable();
            $table->string('raison_sociale', 150)->nullable();
            $table->string('sigle', 10)->nullable()->unique('clients_sigle_key');
            $table->string('code_client', 10)->unique('clients_code_client_key');
            $table->string('whatsapp', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->bigInteger('localite_id')->nullable();
            $table->bigInteger('point_vente_id')->nullable()->index('idx_clients_point_vente');
            $table->string('type_achat', 30)->nullable()->default('Riz Blanc');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));

            $table->index(['code_client'], 'idx_clients_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
