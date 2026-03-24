<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('postes')->truncate();

        $postes = [
            ['libelle' => 'Directeur Général',      'description' => 'Direction générale'],
            ['libelle' => 'Responsable Achats',      'description' => 'Gestion paddy / approvisionnement'],
            ['libelle' => 'Responsable Production',  'description' => 'Étuvage et décorticage'],
            ['libelle' => 'Responsable Commercial',  'description' => 'Ventes et clients'],
            ['libelle' => 'Responsable Comptable',   'description' => 'Comptabilité et finances'],
            ['libelle' => 'Magasinier',              'description' => 'Gestion des stocks'],
            ['libelle' => 'Agent de terrain',        'description' => 'Collecte et achat sur site'],
        ];

        foreach ($postes as $p) {
            DB::table('postes')->insert([
                ...$p,
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('  Postes : ' . count($postes) . ' insérés');
    }
}