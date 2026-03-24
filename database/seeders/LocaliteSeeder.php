<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaliteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('localites')->truncate();

        $localites = [
            // Vallée du Bandama
            ['nom' => 'Bouaké',     'region' => 'Vallée du Bandama'],
            ['nom' => 'Béoumi',     'region' => 'Vallée du Bandama'],
            ['nom' => 'Sakassou',   'region' => 'Vallée du Bandama'],
            ['nom' => 'Brobo',      'region' => 'Gbêkê'],
            // Hambol
            ['nom' => 'Katiola',    'region' => 'Hambol'],
            ['nom' => 'Niakara',    'region' => 'Hambol'],
            ['nom' => 'Dabakala',   'region' => 'Hambol'],
            // Autres
            ['nom' => 'Yamoussoukro', 'region' => 'Lacs'],
            ['nom' => 'Abidjan',    'region' => 'Lagunes'],
            ['nom' => "M'Bahiakro", 'region' => 'Ifou'],
            ['nom' => 'Daoukro',    'region' => 'Ifou'],
        ];

        foreach ($localites as $localite) {
            DB::table('localites')->insert([
                ...$localite,
                'created_at' => now(),
            ]);
        }

        $this->command->info('  Localités : ' . count($localites) . ' insérées');
    }
}