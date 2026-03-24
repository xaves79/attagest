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
        DB::statement("CREATE VIEW \"v_factures_completes\" AS SELECT f.id,
    f.numero_facture,
    f.client_id,
    c.nom AS client_nom,
    f.date_facture,
    f.montant_total,
    f.montant_paye,
    f.solde_restant,
    f.statut,
    f.date_echeance,
    f.jours_credit,
    count(l.id) AS nb_lignes,
    sum(l.montant) AS total_lignes
   FROM ((factures_clients f
     LEFT JOIN clients c ON ((f.client_id = c.id)))
     LEFT JOIN lignes_facture l ON ((f.id = l.facture_id)))
  GROUP BY f.id, c.nom
  ORDER BY f.date_facture DESC;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS \"v_factures_completes\"");
    }
};
