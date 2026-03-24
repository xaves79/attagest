<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW \"vue_traitements_actifs\" AS SELECT tc.id,
    tc.code_traitement,
    tc.statut,
    (((c.nom)::text || ' '::text) || (COALESCE(c.prenom, ''::character varying))::text) AS client_complet,
    tc.quantite_paddy_kg,
    tc.montant_traitement_fcfa,
        CASE
            WHEN ((tc.statut)::text = 'en_attente'::text) THEN '?? À traiter'::text
            WHEN ((tc.statut)::text = 'traite'::text) THEN '? Traité - À livrer'::text
            WHEN ((tc.statut)::text = 'pret_livraison'::text) THEN '?? Prêt'::text
            ELSE '? Terminé'::text
        END AS statut_visual
   FROM (traitements_client tc
     JOIN clients c ON ((tc.client_id = c.id)))
  WHERE ((tc.statut)::text <> 'livre'::text);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS \"vue_traitements_actifs\"");
    }
};
