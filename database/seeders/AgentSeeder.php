<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('agents')->truncate();
        DB::table('users')->truncate();

        $entrepriseId = DB::table('entreprises')->first()->id;

        $postes = DB::table('postes')->pluck('id', 'libelle');

        $agents = [
            [
                'nom'          => 'Kouamé',
                'prenom'       => 'Attah',
                'matricule'    => 'AGT-001',
                'poste'        => 'Directeur Général',
                'whatsapp'     => '+22507000001',
                'telephone'    => '+22527000001',
                'email'        => 'dg@attagest.ci',
                'date_embauche'=> '2024-01-01',
            ],
            [
                'nom'          => 'Bamba',
                'prenom'       => 'Saliou',
                'matricule'    => 'AGT-002',
                'poste'        => 'Responsable Achats',
                'whatsapp'     => '+22507000002',
                'telephone'    => '+22527000002',
                'email'        => 'achats@attagest.ci',
                'date_embauche'=> '2024-02-01',
            ],
            [
                'nom'          => 'Traoré',
                'prenom'       => 'Mamadou',
                'matricule'    => 'AGT-003',
                'poste'        => 'Responsable Production',
                'whatsapp'     => '+22507000003',
                'telephone'    => '+22527000003',
                'email'        => 'production@attagest.ci',
                'date_embauche'=> '2024-02-15',
            ],
            [
                'nom'          => 'Koné',
                'prenom'       => 'Aminata',
                'matricule'    => 'AGT-004',
                'poste'        => 'Responsable Commercial',
                'whatsapp'     => '+22507000004',
                'telephone'    => '+22527000004',
                'email'        => 'commercial@attagest.ci',
                'date_embauche'=> '2024-03-01',
            ],
            [
                'nom'          => 'Yao',
                'prenom'       => 'Kouassi',
                'matricule'    => 'AGT-005',
                'poste'        => 'Magasinier',
                'whatsapp'     => '+22507000005',
                'telephone'    => '+22527000005',
                'email'        => 'stock@attagest.ci',
                'date_embauche'=> '2024-03-15',
            ],
            [
                'nom'          => 'Diallo',
                'prenom'       => 'Ibrahim',
                'matricule'    => 'AGT-006',
                'poste'        => 'Agent de terrain',
                'whatsapp'     => '+22507000006',
                'telephone'    => '+22527000006',
                'email'        => 'terrain1@attagest.ci',
                'date_embauche'=> '2024-04-01',
            ],
        ];

        foreach ($agents as $a) {
            $posteId = $postes[$a['poste']] ?? null;

            $agentId = DB::table('agents')->insertGetId([
                'nom'          => $a['nom'],
                'prenom'       => $a['prenom'],
                'matricule'    => $a['matricule'],
                'poste_id'     => $posteId,
                'entreprise_id'=> $entrepriseId,
                'whatsapp'     => $a['whatsapp'],
                'telephone'    => $a['telephone'],
                'email'        => $a['email'],
                'date_embauche'=> $a['date_embauche'],
                'actif'        => true,
                'nom_complet'  => $a['prenom'] . ' ' . $a['nom'],
                'created_at'   => now(),
            ]);

            // Créer un compte utilisateur pour chaque agent
            DB::table('users')->insert([
                'name'       => $a['prenom'] . ' ' . $a['nom'],
                'email'      => $a['email'],
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('  Agents : ' . count($agents) . ' insérés avec comptes utilisateurs');
    }
}