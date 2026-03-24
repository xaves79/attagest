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
        Schema::table('recus_fournisseurs', function (Blueprint $table) {
            $table->foreign(['entreprise_id'], 'fk_recus_fournisseurs_entreprise')->references(['id'])->on('entreprises')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['fournisseur_id'], 'fk_recus_fournisseurs_fournisseur')->references(['id'])->on('fournisseurs')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['variete_rice_id'], 'fk_recus_fournisseurs_variete')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recus_fournisseurs', function (Blueprint $table) {
            $table->dropForeign('fk_recus_fournisseurs_entreprise');
            $table->dropForeign('fk_recus_fournisseurs_fournisseur');
            $table->dropForeign('fk_recus_fournisseurs_variete');
        });
    }
};
