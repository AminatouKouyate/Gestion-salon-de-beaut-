<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Vous pouvez ajouter d'autres classes de seeder ici
            // ClientSeeder::class,
            // ServiceSeeder::class,
        ]);
    }
}
