<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fournisseurs')->truncate();

        $localites = DB::table('localites')->pluck('id', 'nom');

        $fournisseurs = [
            [
                'type_personne'    => 'PHYSIQUE',
                'nom'              => 'Coulibaly',
                'prenom'           => 'Souleymane',
                'raison_sociale'   => null,
                'sigle'            => null,
                'code_fournisseur' => 'FRN-001',
                'whatsapp'         => '+22507100001',
                'telephone'        => '+22527100001',
                'localite_id'      => $localites['Katiola'],
                'type_fournisseur' => 'producteur',
                'email'            => null,
            ],
            [
                'type_personne'    => 'PHYSIQUE',
                'nom'              => 'Dembélé',
                'prenom'           => 'Oumar',
                'raison_sociale'   => null,
                'sigle'            => null,
                'code_fournisseur' => 'FRN-002',
                'whatsapp'         => '+22507100002',
                'telephone'        => '+22527100002',
                'localite_id'      => $localites['Niakara'],
                'type_fournisseur' => 'producteur',
                'email'            => null,
            ],
            [
                'type_personne'    => 'PHYSIQUE',
                'nom'              => 'Konaté',
                'prenom'           => 'Fatoumata',
                'raison_sociale'   => null,
                'sigle'            => null,
                'code_fournisseur' => 'FRN-003',
                'whatsapp'         => '+22507100003',
                'telephone'        => '+22527100003',
                'localite_id'      => $localites['Béoumi'],
                'type_fournisseur' => 'producteur',
                'email'            => null,
            ],
            [
				'type_personne'     => 'MORALE',
				'nom'               => null,
				'prenom'            => null,
				'raison_sociale'    => 'Coopérative Agricole du Bandama',
				'sigle'             => 'COOP-AGRI',
				'code_fournisseur'  => 'FRN-004',
				'whatsapp'          => '+22507100004',
				'telephone'         => '+22527100004',
				'localite_id'       => 3,
				'type_fournisseur'  => 'cooperative',
				'email'             => 'coop.bandama@mail.ci',
			],
            [
                'type_personne'    => 'PHYSIQUE',
                'nom'              => 'Touré',
                'prenom'           => 'Aboubakar',
                'raison_sociale'   => null,
                'sigle'            => null,
                'code_fournisseur' => 'FRN-005',
                'whatsapp'         => '+22507100005',
                'telephone'        => '+22527100005',
                'localite_id'      => $localites['Dabakala'],
                'type_fournisseur' => 'producteur',
                'email'            => null,
            ],
        ];

        foreach ($fournisseurs as $f) {
            DB::table('fournisseurs')->insert([
                ...$f,
                'created_at' => now(),
            ]);
        }

        $this->command->info('  Fournisseurs : ' . count($fournisseurs) . ' insérés');
    }
}