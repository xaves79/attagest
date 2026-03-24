<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'diexave79@gmail.com'], // identifiant unique
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Ngouanlessa@79'), // changez le mot de passe
                'is_super_admin' => true, // si vous avez ce champ
            ]
        );
    }
}