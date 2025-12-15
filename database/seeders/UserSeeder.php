<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilise `firstOrCreate` pour éviter les doublons si le seeder est lancé plusieurs fois.

        // Créer un administrateur
        User::firstOrCreate(
            ['email' => 'admin@salon.com'],
            [
                'name' => 'Admin Salon',
                'password' => Hash::make('password'), // Mot de passe : password
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Créer un employé
        User::firstOrCreate(
            ['email' => 'employee@salon.com'],
            [
                'name' => 'Employe Salon',
                'password' => Hash::make('password'), // Mot de passe : password
                'role' => User::ROLE_EMPLOYEE,
            ]
        );

        // Créer un client
        User::firstOrCreate(
            ['email' => 'client@salon.com'],
            [
                'name' => 'Client Salon',
                'password' => Hash::make('password'), // Mot de passe : password
                'role' => User::ROLE_CLIENT,
            ]
        );
    }
}
