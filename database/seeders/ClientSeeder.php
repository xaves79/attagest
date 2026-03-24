<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->truncate();

        $localites = DB::table('localites')->pluck('id', 'nom');
        $points    = DB::table('points_vente')->pluck('id', 'code_point');

        $clients = [
            // Clients particuliers
            [
                'type_client'   => 'PARTICULIER',
                'nom'           => 'Aka',
                'prenom'        => 'Jean-Baptiste',
                'raison_sociale'=> null,
                'sigle'         => null,
                'code_client'   => 'CLI-001',
                'whatsapp'      => '+22507200001',
                'telephone'     => '+22527200001',
                'email'         => null,
                'localite_id'   => $localites['Bouaké'],
                'point_vente_id'=> $points['PTV-BOU-001'],
                'type_achat'    => 'comptant',
            ],
            [
                'type_client'   => 'PARTICULIER',
                'nom'           => 'Gnangui',
                'prenom'        => 'Marie',
                'raison_sociale'=> null,
                'sigle'         => null,
                'code_client'   => 'CLI-002',
                'whatsapp'      => '+22507200002',
                'telephone'     => '+22527200002',
                'email'         => null,
                'localite_id'   => $localites['Bouaké'],
                'point_vente_id'=> $points['PTV-BOU-001'],
                'type_achat'    => 'comptant',
            ],
            // Clients grossistes
            [
                'type_client'   => 'GROSSISTE',
                'nom'           => 'DISTRIRIZ',
                'prenom'        => null,
                'raison_sociale'=> 'Distribution Riz CI SARL',
                'sigle'         => 'DISTRIRIZ',
                'code_client'   => 'CLI-003',
                'whatsapp'      => '+22507200003',
                'telephone'     => '+22527200003',
                'email'         => 'contact@distririz.ci',
                'localite_id'   => $localites['Abidjan'],
                'point_vente_id'=> $points['PTV-ABI-001'],
                'type_achat'    => 'credit',
            ],
            [
                'type_client'   => 'GROSSISTE',
                'nom'           => 'SUPER MARCHÉ ÉTOILE',
                'prenom'        => null,
                'raison_sociale'=> 'Super Marché Étoile',
                'sigle'         => 'SME',
                'code_client'   => 'CLI-004',
                'whatsapp'      => '+22507200004',
                'telephone'     => '+22527200004',
                'email'         => 'achat@sme.ci',
                'localite_id'   => $localites['Yamoussoukro'],
                'point_vente_id'=> $points['PTV-YAM-001'],
                'type_achat'    => 'credit',
            ],
            // Client restauration
            [
                'type_client'   => 'RESTAURANT',
                'nom'           => 'Fadiga',
                'prenom'        => 'Mariam',
                'raison_sociale'=> null,
                'sigle'         => null,
                'code_client'   => 'CLI-005',
                'whatsapp'      => '+22507200005',
                'telephone'     => '+22527200005',
                'email'         => null,
                'localite_id'   => $localites['Bouaké'],
                'point_vente_id'=> $points['PTV-BOU-001'],
                'type_achat'    => 'comptant',
            ],
        ];

        foreach ($clients as $c) {
            DB::table('clients')->insert([
                ...$c,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('  Clients : ' . count($clients) . ' insérés');
    }
}