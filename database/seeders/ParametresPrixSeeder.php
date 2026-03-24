<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametresPrixSeeder extends Seeder
{
    public function run()
    {
        DB::table('parametres_prix')->insert([
            [
                'type_produit'       => 'riz_blanc',
                'unite'              => 'sac',
                'poids_sac_kg'       => 5.00,
                'prix_unitaire_fcfa' => 3500,
                'actif'              => true,
                'date_application'   => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'type_produit'       => 'riz_blanc',
                'unite'              => 'sac',
                'poids_sac_kg'       => 25.00,
                'prix_unitaire_fcfa' => 12500,
                'actif'              => true,
                'date_application'   => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'type_produit'       => 'riz_blanc',
                'unite'              => 'sac',
                'poids_sac_kg'       => 50.00,
                'prix_unitaire_fcfa' => 24000,
                'actif'              => true,
                'date_application'   => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}