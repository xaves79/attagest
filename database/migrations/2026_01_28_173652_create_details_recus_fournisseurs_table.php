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
        Schema::create('details_recus_fournisseurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('recu_id')->nullable()->index('idx_details_recus_recu');
            $table->text('description')->nullable();
            $table->bigInteger('article_id')->nullable()->index('idx_details_recus_article');
            $table->bigInteger('variete_rice_id')->nullable()->index('idx_details_recus_variete');
            $table->decimal('quantite', 12);
            $table->decimal('prix_unitaire', 12);
            $table->decimal('sous_total', 12, 0)->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_recus_fournisseurs');
    }
};
