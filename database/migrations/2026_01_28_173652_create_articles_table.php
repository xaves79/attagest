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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom')->index('idx_articles_nom');
            $table->text('description')->nullable();
            $table->decimal('prix_unitaire', 10, 0)->default(0);
            $table->integer('stock')->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->bigInteger('variete_id')->nullable()->index('idx_articles_variete_id');
            $table->string('type_produit', 50)->default('riz_blanchi')->index('idx_articles_type');
            $table->string('taille_sac', 10)->default('1kg')->index('idx_articles_sac');
            $table->decimal('prix_kg', 10)->default(0);
            $table->string('unite_vente', 20)->default('sac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
