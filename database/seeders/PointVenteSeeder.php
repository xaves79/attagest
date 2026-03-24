<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointVenteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('points_vente')->truncate();

        $localites   = DB::table('localites')->pluck('id', 'nom');
        $agents      = DB::table('agents')->pluck('id', 'matricule');

        $points = [
            [
                'nom'        => 'ATTAGEST Bouaké (Siège)',
                'code_point' => 'PTV-BOU-001',
                'agent_id'   => $agents['AGT-001'],
                'localite_id'=> $localites['Bouaké'],
                'adresse'    => 'Quartier Commerce, Bouaké',
                'telephone'  => '+22527474160',
                'whatsapp'   => '+22507474160',
                'email'      => 'bouake@attagest.ci',
            ],
            [
                'nom'        => 'ATTAGEST Abidjan',
                'code_point' => 'PTV-ABI-001',
                'agent_id'   => $agents['AGT-004'],
                'localite_id'=> $localites['Abidjan'],
                'adresse'    => 'Zone Industrielle de Yopougon, Abidjan',
                'telephone'  => '+22527000100',
                'whatsapp'   => '+22507000100',
                'email'      => 'abidjan@attagest.ci',
            ],
            [
                'nom'        => 'ATTAGEST Yamoussoukro',
                'code_point' => 'PTV-YAM-001',
                'agent_id'   => $agents['AGT-005'],
                'localite_id'=> $localites['Yamoussoukro'],
                'adresse'    => 'Avenue des Présidents, Yamoussoukro',
                'telephone'  => '+22527000200',
                'whatsapp'   => '+22507000200',
                'email'      => 'yamoussoukro@attagest.ci',
            ],
        ];

        foreach ($points as $p) {
            DB::table('points_vente')->insert([
                ...$p,
                'actif'      => true,
                'created_at' => now(),
            ]);
        }

        $this->command->info('  Points de vente : ' . count($points) . ' insérés');
    }
}