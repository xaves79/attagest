<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDataCommand extends Command
{
    protected $signature   = 'attagest:reset-data {--force : Confirmer sans prompt}';
    protected $description = 'Vider toutes les données de test (garde les référentiels et utilisateurs)';

    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Cette action supprime TOUTES les données transactionnelles. Continuer ?', false)) {
                $this->info('Annulé.');
                return 0;
            }
        }

        $this->info('🗑️  Vidage des données en cours...');

        DB::statement('SET session_replication_role = replica'); // désactiver FK temporairement

        $tables = [
            // Comptabilité
            'ecritures_comptables',
            // Journal
            'journal_activites',
            // Ventes
            'paiements_factures',
            'lignes_livraison',
            'livraisons_vente',
            'lignes_commande_vente',
            'commandes_vente',
            'factures_clients',
            'ventes',
            // Stocks / sacs
            'stocks_sacs',
            'sacs_produits_finis',
            // Production
            'stocks_produits_finis',
            'decorticages',
            'lots_riz_etuve',
            'etuvages',
            // Traitements clients
            'paiements_traitements',
            'traitements_client',
            // Achats
            'paiements_fournisseurs',
            'stocks_paddy',
            'recus_fournisseurs',
            'lots_paddy',
        ];

        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
                $this->line("  ✅ {$table}");
            } catch (\Exception $e) {
                $this->warn("  ⚠️  {$table} : " . $e->getMessage());
            }
        }

        // Remettre les soldes des comptes à zéro
        DB::table('comptes')->update(['solde_debit' => 0, 'solde_credit' => 0]);
        $this->line("  ✅ comptes (soldes remis à 0)");

        DB::statement('SET session_replication_role = DEFAULT');

        $this->newLine();
        $this->info('✅ Données vidées avec succès.');
        $this->info('ℹ️  Référentiels conservés : clients, fournisseurs, agents, variétés, articles, points de vente.');
        $this->info('ℹ️  Utilisateurs conservés.');

        return 0;
    }
}