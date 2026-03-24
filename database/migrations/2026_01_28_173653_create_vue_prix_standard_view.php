<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer la vue si elle existe déjà
        DB::statement('DROP VIEW IF EXISTS vue_prix_standard');

        // Créer la vue avec la requête d'origine
        DB::statement("
            CREATE VIEW vue_prix_standard AS
            SELECT 'Paddy'::text AS produit,
                   round_ci((350)::numeric) AS prix_achat_paddy,
                   round_ci((900)::numeric) AS prix_vente_riz_blanc,
                   round_ci((150)::numeric) AS prix_son,
                   round_ci((400)::numeric) AS prix_brise
            UNION ALL
            SELECT 'Traitement'::text AS produit,
                   round_ci((75)::numeric) AS prix_achat_paddy,
                   NULL::numeric AS prix_vente_riz_blanc,
                   NULL::numeric AS prix_son,
                   NULL::numeric AS prix_brise
        ");
    }

    public function down(): void
    {
        // Supprimer la vue lors du rollback
        DB::statement('DROP VIEW IF EXISTS vue_prix_standard');
    }
};