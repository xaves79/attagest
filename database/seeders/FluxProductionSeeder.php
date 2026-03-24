<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * FluxProductionSeeder
 *
 * Simule 3 achats complets avec leur flux de production :
 *   lots_paddy
 *     → etuvages
 *       → lots_riz_etuve
 *         → decorticages
 *           → stocks_produits_finis
 *             → sacs_produits_finis
 *               → stocks_sacs (par point de vente)
 *
 * NB : Les triggers DB gèrent le débit de stock automatiquement.
 * On insère donc dans l'ordre naturel du flux.
 */
class FluxProductionSeeder extends Seeder
{
    // ----------------------------------------------------------------
    // IDs récupérés en début de run
    // ----------------------------------------------------------------
    private array $agents      = [];
    private array $varietes    = [];
    private array $fournisseurs= [];
    private array $entreprises = [];
    private array $localites   = [];
    private array $points      = [];

    public function run(): void
    {
        // Désactiver les triggers temporairement pour insérer sans débit
        // (les seeders insèrent des données déjà "calculées")
        DB::statement('SET session_replication_role = replica;');

        try {
            $this->truncateTables();
            $this->loadIds();
            $this->lot1_IR64_500kg();
            $this->lot2_IR841_800kg();
            $this->lot3_NERICA4_300kg();
        } finally {
            DB::statement('SET session_replication_role = DEFAULT;');
        }

        $this->command->info('  Flux de production : 3 lots complets insérés');
    }

    // ----------------------------------------------------------------
    // Truncate dans l'ordre inverse des FK
    // ----------------------------------------------------------------
    private function truncateTables(): void
    {
        DB::statement('TRUNCATE TABLE
            stocks_sacs,
            sacs_produits_finis,
            stocks_produits_finis,
            decorticages,
            lots_riz_etuve,
            etuvages,
            stocks_paddy,
            lots_paddy
            RESTART IDENTITY CASCADE'
        );
    }

    private function loadIds(): void
    {
        $this->agents       = DB::table('agents')->pluck('id', 'matricule')->toArray();
        $this->varietes     = DB::table('varietes_rice')->pluck('id', 'code_variete')->toArray();
        $this->fournisseurs = DB::table('fournisseurs')->pluck('id', 'code_fournisseur')->toArray();
        $this->entreprises  = DB::table('entreprises')->pluck('id', 'sigle')->toArray();
        $this->localites    = DB::table('localites')->pluck('id', 'nom')->toArray();
        $this->points       = DB::table('points_vente')->pluck('id', 'code_point')->toArray();
    }

    // ================================================================
    // LOT 1 — IR64 — 500 kg — Flux complet avec 2 décorticages
    // ================================================================
    private function lot1_IR64_500kg(): void
    {
        // 1. Achat paddy
        $lotPaddyId = DB::table('lots_paddy')->insertGetId([
            'code_lot'                => 'LP-2025-001',
            'agent_id'                => $this->agents['AGT-002'],
            'fournisseur_id'          => $this->fournisseurs['FRN-001'],
            'variete_id'              => $this->varietes['IR64'],
            'localite_id'             => $this->localites['Katiola'],
            'entreprise_id'           => $this->entreprises['ATTAGEST'],
            'date_achat'              => '2025-10-05',
            'quantite_achat_kg'       => 500,
            'quantite_restante_kg'    => 0,   // tout étuvé
            'prix_achat_unitaire_fcfa'=> 200,
            'montant_achat_total_fcfa'=> 100000,
            'statut'                  => 'complet',
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        // 2. Stock paddy
        DB::table('stocks_paddy')->insertGetId([
            'code_stock'          => 'SPD-2025-001',
            'lot_paddy_id'        => $lotPaddyId,
            'agent_id'            => $this->agents['AGT-005'],
            'quantite_stock_kg'   => 500,
            'quantite_restante_kg'=> 0,
            'emplacement'         => 'Hangar A — Bouaké',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        // 3. Étuvage (500 kg paddy → 480 kg riz étuvé)
        $etuvageId = DB::table('etuvages')->insertGetId([
            'code_etuvage'             => 'ETV-2025-001',
            'lot_paddy_id'             => $lotPaddyId,
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 500,
            'date_debut_etuvage'       => '2025-10-06 07:00:00',
            'temperature_etuvage'      => 85.0,
            'duree_etuvage_minutes'    => 240,
            'date_fin_etuvage'         => '2025-10-06 11:00:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        // 4. Lot riz étuvé (fractionné en 2 : 300 + 200 kg)
        $lotEtuve1 = DB::table('lots_riz_etuve')->insertGetId([
            'code_lot'             => 'LRE-2025-001',
            'provenance_etuvage_id'=> $etuvageId,
            'variete_rice_id'      => $this->varietes['IR64'],
            'quantite_entree_kg'   => 300,
            'quantite_restante_kg' => 0,
            'masse_apres_kg'       => 288,
            'date_production'      => '2025-10-06 12:00:00',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        $lotEtuve2 = DB::table('lots_riz_etuve')->insertGetId([
            'code_lot'             => 'LRE-2025-002',
            'provenance_etuvage_id'=> $etuvageId,
            'variete_rice_id'      => $this->varietes['IR64'],
            'quantite_entree_kg'   => 200,
            'quantite_restante_kg' => 0,
            'masse_apres_kg'       => 192,
            'date_production'      => '2025-10-06 12:00:00',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        // 5a. Décorticage lot 1 (300 kg) → rendement 65%
        $dec1Id = DB::table('decorticages')->insertGetId([
            'code_decorticage'         => 'DEC-2025-001',
            'lot_riz_etuve_id'         => $lotEtuve1,
            'lot_paddy_id'             => $lotPaddyId,
            'variete_rice_id'          => $this->varietes['IR64'],
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 300,
            'quantite_riz_blanc_kg'    => 195,
            'quantite_son_kg'          => 60,
            'quantite_brise_kg'        => 30,
            'quantite_rejet_kg'        => 15,
            'taux_rendement'           => 65.00,
            'date_debut_decorticage'   => '2025-10-07 07:00:00',
            'date_fin_decorticage'     => '2025-10-07 10:00:00',
            'date_terminaison'         => '2025-10-07 10:00:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        // 5b. Décorticage lot 2 (200 kg) → rendement 67%
        $dec2Id = DB::table('decorticages')->insertGetId([
            'code_decorticage'         => 'DEC-2025-002',
            'lot_riz_etuve_id'         => $lotEtuve2,
            'lot_paddy_id'             => $lotPaddyId,
            'variete_rice_id'          => $this->varietes['IR64'],
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 200,
            'quantite_riz_blanc_kg'    => 134,
            'quantite_son_kg'          => 36,
            'quantite_brise_kg'        => 20,
            'quantite_rejet_kg'        => 10,
            'taux_rendement'           => 67.00,
            'date_debut_decorticage'   => '2025-10-08 07:00:00',
            'date_fin_decorticage'     => '2025-10-08 09:00:00',
            'date_terminaison'         => '2025-10-08 09:00:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        // 6. Stocks produits finis
        $this->insererStocksProduitsFinis($dec1Id, $lotPaddyId, $etuvageId, $this->varietes['IR64'], [
            ['type' => 'riz_blanc', 'qte' => 195, 'code' => 'SPF-2025-001'],
            ['type' => 'son',       'qte' => 60,  'code' => 'SPF-2025-002'],
            ['type' => 'brisures',  'qte' => 30,  'code' => 'SPF-2025-003'],
        ]);

        $this->insererStocksProduitsFinis($dec2Id, $lotPaddyId, $etuvageId, $this->varietes['IR64'], [
            ['type' => 'riz_blanc', 'qte' => 134, 'code' => 'SPF-2025-004'],
            ['type' => 'son',       'qte' => 36,  'code' => 'SPF-2025-005'],
            ['type' => 'brisures',  'qte' => 20,  'code' => 'SPF-2025-006'],
        ]);

        // 7. Mise en sacs (riz blanc uniquement, sacs de 25 kg)
        $spfRizId = DB::table('stocks_produits_finis')
            ->where('code_stock', 'SPF-2025-001')->value('id');

        $sacId = DB::table('sacs_produits_finis')->insertGetId([
            'code_sac'               => 'SAC-2025-001',
            'stock_produit_fini_id'  => $spfRizId,
            'type_sac'               => 'riz_blanc',
            'poids_sac_kg'           => 25,
            'nombre_sacs'            => 7,   // 175 kg / 25
            'variete_code'           => 'IR64',
            'provenance_decorticage' => 'DEC-2025-001',
            'agent_id'               => $this->agents['AGT-005'],
            'statut'                 => 'disponible',
            'date_emballage'         => '2025-10-07 14:00:00',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        // 8. Stock sac au point de vente Bouaké
        DB::table('stocks_sacs')->insert([
            'point_vente_id' => $this->points['PTV-BOU-001'],
            'sac_id'         => $sacId,
            'quantite'       => 7,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // ================================================================
    // LOT 2 — IR841 — 800 kg — Flux complet, 1 décorticage
    // ================================================================
    private function lot2_IR841_800kg(): void
    {
        $lotPaddyId = DB::table('lots_paddy')->insertGetId([
            'code_lot'                => 'LP-2025-002',
            'agent_id'                => $this->agents['AGT-006'],
            'fournisseur_id'          => $this->fournisseurs['FRN-004'],
            'variete_id'              => $this->varietes['IR841'],
            'localite_id'             => $this->localites['Sakassou'],
            'entreprise_id'           => $this->entreprises['ATTAGEST'],
            'date_achat'              => '2025-10-12',
            'quantite_achat_kg'       => 800,
            'quantite_restante_kg'    => 0,
            'prix_achat_unitaire_fcfa'=> 195,
            'montant_achat_total_fcfa'=> 156000,
            'statut'                  => 'complet',
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        DB::table('stocks_paddy')->insert([
            'code_stock'          => 'SPD-2025-002',
            'lot_paddy_id'        => $lotPaddyId,
            'agent_id'            => $this->agents['AGT-005'],
            'quantite_stock_kg'   => 800,
            'quantite_restante_kg'=> 0,
            'emplacement'         => 'Hangar A — Bouaké',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $etuvageId = DB::table('etuvages')->insertGetId([
            'code_etuvage'             => 'ETV-2025-002',
            'lot_paddy_id'             => $lotPaddyId,
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 800,
            'date_debut_etuvage'       => '2025-10-13 07:00:00',
            'temperature_etuvage'      => 88.0,
            'duree_etuvage_minutes'    => 300,
            'date_fin_etuvage'         => '2025-10-13 12:00:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $lotEtuveId = DB::table('lots_riz_etuve')->insertGetId([
            'code_lot'             => 'LRE-2025-003',
            'provenance_etuvage_id'=> $etuvageId,
            'variete_rice_id'      => $this->varietes['IR841'],
            'quantite_entree_kg'   => 800,
            'quantite_restante_kg' => 0,
            'masse_apres_kg'       => 768,
            'date_production'      => '2025-10-13 13:00:00',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        $decId = DB::table('decorticages')->insertGetId([
            'code_decorticage'         => 'DEC-2025-003',
            'lot_riz_etuve_id'         => $lotEtuveId,
            'lot_paddy_id'             => $lotPaddyId,
            'variete_rice_id'          => $this->varietes['IR841'],
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 800,
            'quantite_riz_blanc_kg'    => 536,
            'quantite_son_kg'          => 144,
            'quantite_brise_kg'        => 80,
            'quantite_rejet_kg'        => 40,
            'taux_rendement'           => 67.00,
            'date_debut_decorticage'   => '2025-10-14 07:00:00',
            'date_fin_decorticage'     => '2025-10-14 13:00:00',
            'date_terminaison'         => '2025-10-14 13:00:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $this->insererStocksProduitsFinis($decId, $lotPaddyId, $etuvageId, $this->varietes['IR841'], [
            ['type' => 'riz_blanc', 'qte' => 536, 'code' => 'SPF-2025-007'],
            ['type' => 'son',       'qte' => 144, 'code' => 'SPF-2025-008'],
            ['type' => 'brisures',  'qte' => 80,  'code' => 'SPF-2025-009'],
        ]);

        // Sacs 25 kg → 20 sacs (500 kg), reste 36 kg en vrac
        $spfId = DB::table('stocks_produits_finis')
            ->where('code_stock', 'SPF-2025-007')->value('id');

        $sacId1 = DB::table('sacs_produits_finis')->insertGetId([
            'code_sac'               => 'SAC-2025-002',
            'stock_produit_fini_id'  => $spfId,
            'type_sac'               => 'riz_blanc',
            'poids_sac_kg'           => 25,
            'nombre_sacs'            => 20,
            'variete_code'           => 'IR841',
            'provenance_decorticage' => 'DEC-2025-003',
            'agent_id'               => $this->agents['AGT-005'],
            'statut'                 => 'disponible',
            'date_emballage'         => '2025-10-14 16:00:00',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        // Sacs 50 kg → 1 sac
        $sacId2 = DB::table('sacs_produits_finis')->insertGetId([
            'code_sac'               => 'SAC-2025-003',
            'stock_produit_fini_id'  => $spfId,
            'type_sac'               => 'riz_blanc',
            'poids_sac_kg'           => 50,
            'nombre_sacs'            => 1,
            'variete_code'           => 'IR841',
            'provenance_decorticage' => 'DEC-2025-003',
            'agent_id'               => $this->agents['AGT-005'],
            'statut'                 => 'disponible',
            'date_emballage'         => '2025-10-14 16:00:00',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        // Stocks sacs — répartis sur 2 points de vente
        DB::table('stocks_sacs')->insert([
            [
                'point_vente_id' => $this->points['PTV-BOU-001'],
                'sac_id'         => $sacId1,
                'quantite'       => 12,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'point_vente_id' => $this->points['PTV-ABI-001'],
                'sac_id'         => $sacId1,
                'quantite'       => 8,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'point_vente_id' => $this->points['PTV-BOU-001'],
                'sac_id'         => $sacId2,
                'quantite'       => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }

    // ================================================================
    // LOT 3 — NERICA 4 — 300 kg — En cours (décorticage non terminé)
    // ================================================================
    private function lot3_NERICA4_300kg(): void
    {
        $lotPaddyId = DB::table('lots_paddy')->insertGetId([
            'code_lot'                => 'LP-2025-003',
            'agent_id'                => $this->agents['AGT-002'],
            'fournisseur_id'          => $this->fournisseurs['FRN-003'],
            'variete_id'              => $this->varietes['NER4'],
            'localite_id'             => $this->localites['Béoumi'],
            'entreprise_id'           => $this->entreprises['ATTAGEST'],
            'date_achat'              => '2025-11-02',
            'quantite_achat_kg'       => 300,
            'quantite_restante_kg'    => 0,
            'prix_achat_unitaire_fcfa'=> 210,
            'montant_achat_total_fcfa'=> 63000,
            'statut'                  => 'en_cours',
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        DB::table('stocks_paddy')->insert([
            'code_stock'          => 'SPD-2025-003',
            'lot_paddy_id'        => $lotPaddyId,
            'agent_id'            => $this->agents['AGT-005'],
            'quantite_stock_kg'   => 300,
            'quantite_restante_kg'=> 0,
            'emplacement'         => 'Hangar B — Bouaké',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $etuvageId = DB::table('etuvages')->insertGetId([
            'code_etuvage'             => 'ETV-2025-003',
            'lot_paddy_id'             => $lotPaddyId,
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 300,
            'date_debut_etuvage'       => '2025-11-03 07:00:00',
            'temperature_etuvage'      => 86.0,
            'duree_etuvage_minutes'    => 210,
            'date_fin_etuvage'         => '2025-11-03 10:30:00',
            'statut'                   => 'termine',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $lotEtuveId = DB::table('lots_riz_etuve')->insertGetId([
            'code_lot'             => 'LRE-2025-004',
            'provenance_etuvage_id'=> $etuvageId,
            'variete_rice_id'      => $this->varietes['NER4'],
            'quantite_entree_kg'   => 300,
            'quantite_restante_kg' => 150, // seulement 150 kg décortiqués
            'masse_apres_kg'       => 288,
            'date_production'      => '2025-11-03 11:00:00',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        // Décorticage partiel (150 kg sur 300) — en cours
        $decId = DB::table('decorticages')->insertGetId([
            'code_decorticage'         => 'DEC-2025-004',
            'lot_riz_etuve_id'         => $lotEtuveId,
            'lot_paddy_id'             => $lotPaddyId,
            'variete_rice_id'          => $this->varietes['NER4'],
            'agent_id'                 => $this->agents['AGT-003'],
            'quantite_paddy_entree_kg' => 150,
            'quantite_riz_blanc_kg'    => 97,
            'quantite_son_kg'          => 27,
            'quantite_brise_kg'        => 18,
            'quantite_rejet_kg'        => 8,
            'taux_rendement'           => 64.67,
            'date_debut_decorticage'   => '2025-11-04 07:00:00',
            'date_fin_decorticage'     => null,
            'date_terminaison'         => null,
            'statut'                   => 'en_cours',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $this->insererStocksProduitsFinis($decId, $lotPaddyId, $etuvageId, $this->varietes['NER4'], [
            ['type' => 'riz_blanc', 'qte' => 97, 'code' => 'SPF-2025-010'],
            ['type' => 'son',       'qte' => 27, 'code' => 'SPF-2025-011'],
            ['type' => 'brisures',  'qte' => 18, 'code' => 'SPF-2025-012'],
        ]);

        // Sacs en cours (3 sacs de 25 kg = 75 kg, reste 22 kg en vrac)
        $spfId = DB::table('stocks_produits_finis')
            ->where('code_stock', 'SPF-2025-010')->value('id');

        $sacId = DB::table('sacs_produits_finis')->insertGetId([
            'code_sac'               => 'SAC-2025-004',
            'stock_produit_fini_id'  => $spfId,
            'type_sac'               => 'riz_blanc',
            'poids_sac_kg'           => 25,
            'nombre_sacs'            => 3,
            'variete_code'           => 'NER4',
            'provenance_decorticage' => 'DEC-2025-004',
            'agent_id'               => $this->agents['AGT-005'],
            'statut'                 => 'disponible',
            'date_emballage'         => '2025-11-04 12:00:00',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        DB::table('stocks_sacs')->insert([
            'point_vente_id' => $this->points['PTV-BOU-001'],
            'sac_id'         => $sacId,
            'quantite'       => 3,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // ----------------------------------------------------------------
    // Helper — insertion stocks produits finis
    // ----------------------------------------------------------------
    private function insererStocksProduitsFinis(
        int $decId, int $lotPaddyId, int $etuvageId, int $varieteId, array $produits
    ): void {
        $agentId = $this->agents['AGT-005'];

        foreach ($produits as $p) {
            DB::table('stocks_produits_finis')->insert([
                'code_stock'      => $p['code'],
                'type_produit'    => $p['type'],
                'quantite_kg'     => $p['qte'],
                'decorticage_id'  => $decId,
                'agent_id'        => $agentId,
                'variete_rice_id' => $varieteId,
                'etuvage_id'      => $etuvageId,
                'lot_paddy_id'    => $lotPaddyId,
                'statut'          => 'actif',
                'categorie'       => 'production',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}