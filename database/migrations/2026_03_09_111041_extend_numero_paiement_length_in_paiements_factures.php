<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements_factures', function (Blueprint $table) {
            $table->string('numero_paiement', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('paiements_factures', function (Blueprint $table) {
            $table->string('numero_paiement', 20)->change();
        });
    }
};