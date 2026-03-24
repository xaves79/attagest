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
        Schema::create('ecritures_comptables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_ecriture', 20)->unique('ecritures_comptables_code_ecriture_key');
            $table->date('date_ecriture');
            $table->string('libelle', 200);
            $table->string('compte_debit', 10)->nullable();
            $table->decimal('montant_debit', 12);
            $table->string('compte_credit', 10)->nullable();
            $table->decimal('montant_credit', 12);
            $table->string('piece_comptable', 50)->nullable();
            $table->boolean('valide')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecritures_comptables');
    }
};
