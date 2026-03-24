<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyLignesFactureRemoveArticleIdAddProductFields extends Migration
{
    public function up()
    {
        // Vérifier si la contrainte existe avant de la supprimer
        $foreignKeyName = 'lignes_facture_article_id_foreign';
        $check = DB::select("
            SELECT 1 FROM information_schema.table_constraints
            WHERE constraint_name = ? AND table_name = 'lignes_facture'
        ", [$foreignKeyName]);

        if (!empty($check)) {
            DB::statement("ALTER TABLE lignes_facture DROP CONSTRAINT {$foreignKeyName}");
        }

        // Supprimer la colonne article_id
        Schema::table('lignes_facture', function (Blueprint $table) {
            $table->dropColumn('article_id');
        });

        // Ajouter les nouvelles colonnes
        Schema::table('lignes_facture', function (Blueprint $table) {
            $table->string('type_produit')->after('facture_id');
            $table->decimal('poids_sac_kg', 8, 2)->nullable()->after('type_produit');
            $table->string('unite')->default('sac')->after('poids_sac_kg');
            $table->text('description')->nullable()->after('unite');
        });
    }

    public function down()
    {
        Schema::table('lignes_facture', function (Blueprint $table) {
            $table->dropColumn(['type_produit', 'poids_sac_kg', 'unite', 'description']);
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('restrict');
        });
    }
}