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
        Schema::table('details_recus_fournisseurs', function (Blueprint $table) {
            $table->foreign(['article_id'], 'details_recus_fournisseurs_article_id_fkey')->references(['id'])->on('articles')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['recu_id'], 'details_recus_fournisseurs_recu_id_fkey')->references(['id'])->on('recus_fournisseurs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['variete_rice_id'], 'details_recus_fournisseurs_variete_rice_id_fkey')->references(['id'])->on('varietes_rice')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details_recus_fournisseurs', function (Blueprint $table) {
            $table->dropForeign('details_recus_fournisseurs_article_id_fkey');
            $table->dropForeign('details_recus_fournisseurs_recu_id_fkey');
            $table->dropForeign('details_recus_fournisseurs_variete_rice_id_fkey');
        });
    }
};
