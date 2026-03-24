<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ecritures_comptables', function (Blueprint $table) {
            $table->foreign(['compte_credit'], 'ecritures_comptables_compte_credit_fkey')->references(['code_compte'])->on('comptes')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['compte_debit'], 'ecritures_comptables_compte_debit_fkey')->references(['code_compte'])->on('comptes')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecritures_comptables', function (Blueprint $table) {
            $table->dropForeign('ecritures_comptables_compte_credit_fkey');
            $table->dropForeign('ecritures_comptables_compte_debit_fkey');
        });
    }
};
