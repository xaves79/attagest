<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VarieteRiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('varietes_rice')->truncate();

        $varietes = [
            [
                'nom'              => 'IR64',
                'code_variete'     => 'IR64',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 7.50,
                'duree_cycle'      => 110,
                'origine'          => 'IRRI',
                'description'      => 'Variété haute performance, très populaire en Côte d\'Ivoire',
            ],
            [
                'nom'              => 'IR841',
                'code_variete'     => 'IR841',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 8.20,
                'duree_cycle'      => 115,
                'origine'          => 'IRRI/CNRA',
                'description'      => 'Très bon rendement, résistance aux maladies',
            ],
            [
                'nom'              => 'IR05N',
                'code_variete'     => 'IR05N',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 6.80,
                'duree_cycle'      => 105,
                'origine'          => 'IRRI',
                'description'      => 'Cycle court, adapté à la double culture',
            ],
            [
                'nom'              => 'NERICA 4',
                'code_variete'     => 'NER4',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 6.50,
                'duree_cycle'      => 100,
                'origine'          => 'AfricaRice',
                'description'      => 'Résistante à la sécheresse, excellent goût',
            ],
            [
                'nom'              => 'NERICA 8',
                'code_variete'     => 'NER8',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 7.00,
                'duree_cycle'      => 105,
                'origine'          => 'AfricaRice',
                'description'      => 'Rendement élevé, bonne qualité du grain',
            ],
            [
                'nom'              => 'FKR 60',
                'code_variete'     => 'FKR60',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 5.80,
                'duree_cycle'      => 120,
                'origine'          => 'CNRA',
                'description'      => 'Variété ivoirienne traditionnelle améliorée',
            ],
            [
                'nom'              => 'WITA 9',
                'code_variete'     => 'WITA9',
                'type_riz'         => 'Paddy',
                'rendement_estime' => 6.00,
                'duree_cycle'      => 115,
                'origine'          => 'CNRA/AfricaRice',
                'description'      => 'Bonne adaptation aux sols ivoiriens',
            ],
        ];

        foreach ($varietes as $v) {
            DB::table('varietes_rice')->insert([
                ...$v,
                'created_at' => now(),
            ]);
        }

        $this->command->info('  Variétés : ' . count($varietes) . ' insérées');
    }
}