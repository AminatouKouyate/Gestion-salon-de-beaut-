<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder principal de l'application.
 *
 * Orchestre l'exécution de tous les seeders dans l'ordre requis :
 * d'abord les utilisateurs (admin, employé, client), puis les services.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Initialise la base de données avec les données de démarrage.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
