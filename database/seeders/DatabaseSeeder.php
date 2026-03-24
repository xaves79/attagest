<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Référentiels de base
            LocaliteSeeder::class,
            VarieteRiceSeeder::class,
            PosteSeeder::class,

            // 2. Entités
            EntrepriseSeeder::class,
            AgentSeeder::class,
            PointVenteSeeder::class,
            ReservoirSeeder::class,
            FournisseurSeeder::class,
            ClientSeeder::class,

            // 3. Flux de production
            FluxProductionSeeder::class,
        ]);

        $this->command->info('✅ Base de données peuplée avec succès.');
		$this->call(ParametresPrixSeeder::class);
    }
}