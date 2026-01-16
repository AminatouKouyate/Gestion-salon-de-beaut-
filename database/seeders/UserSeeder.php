<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
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
                'password' => Hash::make('admin123'), // Mot de passe : admin123
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Créer un employé dans la table 'employees'
        Employee::firstOrCreate(
            ['email' => 'employe@salon.com'],
            [
                'name' => 'Employe Salon',
                'password' => Hash::make('employe123'), // Mot de passe : employe123
                'role' => 'employee',
            ]
        );

        // Créer un client dans la table 'clients'
        Client::firstOrCreate(
            ['email' => 'client@salon.com'],
            [
                'name' => 'Client Salon',
                'password' => Hash::make('client123'), // Mot de passe : client123
                'phone' => '0102030405',
            ]
        );
    }
}
