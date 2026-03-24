<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compte;

class ComptesSeeder extends Seeder
{
    public function run()
    {
        Compte::insert([
            // Comptes d'ACTIF (Classe 5)
            ['code_compte' => '512', 'libelle' => 'Banque', 'type_compte' => 'ACTIF', 'solde_debit' => 0, 'solde_credit' => 0],
            ['code_compte' => '531', 'libelle' => 'Caisse', 'type_compte' => 'ACTIF', 'solde_debit' => 0, 'solde_credit' => 0],

            // Comptes de CLIENTS (Classe 4)
            ['code_compte' => '411', 'libelle' => 'Clients', 'type_compte' => 'ACTIF', 'solde_debit' => 0, 'solde_credit' => 0],

            // Comptes de FOURNISSEURS (Classe 4)
            ['code_compte' => '401', 'libelle' => 'Fournisseurs', 'type_compte' => 'PASSIF', 'solde_debit' => 0, 'solde_credit' => 0],

            // Comptes de PRODUITS (Classe 7)
            ['code_compte' => '707', 'libelle' => 'Ventes de marchandises', 'type_compte' => 'PRODUIT', 'solde_debit' => 0, 'solde_credit' => 0],

            // Comptes de CHARGES (Classe 6)
            ['code_compte' => '607', 'libelle' => 'Achats de marchandises', 'type_compte' => 'CHARGE', 'solde_debit' => 0, 'solde_credit' => 0],
            ['code_compte' => '611', 'libelle' => 'Services extérieurs', 'type_compte' => 'CHARGE', 'solde_debit' => 0, 'solde_credit' => 0],
            ['code_compte' => '641', 'libelle' => 'Salaires et traitements', 'type_compte' => 'CHARGE', 'solde_debit' => 0, 'solde_credit' => 0],
        ]);
    }
}
