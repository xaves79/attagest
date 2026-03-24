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
        Schema::create('factures_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_facture', 20)->unique('factures_clients_numero_facture_key');
            $table->bigInteger('client_id')->index('idx_factures_clients_client');
            $table->date('date_facture');
            $table->decimal('montant_total', 12);
            $table->decimal('montant_paye', 12)->nullable()->default(0);
            $table->decimal('solde_restant', 12);
            $table->enum('statut', ['payee', 'credit', 'partiel', 'annulee'])->nullable()->default('credit')->index('idx_factures_clients_statut');
            $table->date('date_echeance')->nullable()->index('idx_factures_clients_echeance');
            $table->integer('jours_credit')->nullable()->default(30);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->integer('auto_numero')->nullable()->unique('factures_clients_auto_numero_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures_clients');
    }
};
