<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('entreprises')->truncate();

        DB::table('entreprises')->insert([
            'nom'             => 'ATTAGEST SARL',
            'sigle'           => 'ATTAGEST',
            'code_entreprise' => 'ENT-001',
            'whatsapp'        => '+22507474160',
            'telephone'       => '+22527474160',
            'email'           => 'contact@attagest.ci',
            'adresse'         => 'Bouaké, Vallée du Bandama, Côte d\'Ivoire',
            'gerant_nom'      => 'Kouamé Attah',
            'created_at'      => now(),
        ]);

        $this->command->info('  Entreprise : ATTAGEST insérée');
    }
}