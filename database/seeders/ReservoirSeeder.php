<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservoirSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reservoirs')->truncate();

        $points = DB::table('points_vente')->pluck('id', 'code_point');

        // Valeurs autorisées : riz_blanc, son, brisures
        $reservoirs = [
            // Bouaké
            [
                'nom_reservoir'       => 'Silo Riz Blanc — Bouaké',
                'type_produit'        => 'riz_blanc',
                'capacite_max_kg'     => 10000,
                'point_vente_id'      => $points['PTV-BOU-001'],
                'quantite_actuelle_kg'=> 0,
            ],
            [
                'nom_reservoir'       => 'Bac Son — Bouaké',
                'type_produit'        => 'son',
                'capacite_max_kg'     => 3000,
                'point_vente_id'      => $points['PTV-BOU-001'],
                'quantite_actuelle_kg'=> 0,
            ],
            [
                'nom_reservoir'       => 'Bac Brisures — Bouaké',
                'type_produit'        => 'brisures',
                'capacite_max_kg'     => 2000,
                'point_vente_id'      => $points['PTV-BOU-001'],
                'quantite_actuelle_kg'=> 0,
            ],
            // Abidjan
            [
                'nom_reservoir'       => 'Silo Riz Blanc — Abidjan',
                'type_produit'        => 'riz_blanc',
                'capacite_max_kg'     => 15000,
                'point_vente_id'      => $points['PTV-ABI-001'],
                'quantite_actuelle_kg'=> 0,
            ],
            // Yamoussoukro
            [
                'nom_reservoir'       => 'Silo Riz Blanc — Yamoussoukro',
                'type_produit'        => 'riz_blanc',
                'capacite_max_kg'     => 5000,
                'point_vente_id'      => $points['PTV-YAM-001'],
                'quantite_actuelle_kg'=> 0,
            ],
        ];

        foreach ($reservoirs as $r) {
            DB::table('reservoirs')->insert([
                ...$r,
                'created_at' => now(),
            ]);
        }

        $this->command->info('  Réservoirs : ' . count($reservoirs) . ' insérés');
    }
}