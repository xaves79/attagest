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
        Schema::table('lignes_facture', function (Blueprint $table) {
            $table->foreign(['article_id'], 'lignes_facture_article_id_fkey')->references(['id'])->on('articles')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['facture_id'], 'lignes_facture_facture_id_fkey')->references(['id'])->on('factures_clients')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lignes_facture', function (Blueprint $table) {
            $table->dropForeign('lignes_facture_article_id_fkey');
            $table->dropForeign('lignes_facture_facture_id_fkey');
        });
    }
};
