<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Seeder pour les services du salon de beauté.
 *
 * Crée les services prédéfinis dans les catégories : Coiffure, Coloration,
 * Soins, Coiffures Spéciales et Esthétique. Configure également une
 * promotion de bienvenue sur le Soin Kératine et lie les employés aux services.
 */
class ServiceSeeder extends Seeder
{
    /**
     * Crée les services, configure les promotions et lie les employés aux services.
     */
    public function run(): void
    {
        $services = [
            // Coiffure
            ['name' => 'Coupe Femme', 'price' => 5000, 'duration' => 45, 'category' => 'Coiffure', 'active' => true],
            ['name' => 'Coupe Homme', 'price' => 3000, 'duration' => 30, 'category' => 'Coiffure', 'active' => true],
            ['name' => 'Coupe Enfant', 'price' => 2000, 'duration' => 20, 'category' => 'Coiffure', 'active' => true],
            ['name' => 'Brushing', 'price' => 2500, 'duration' => 30, 'category' => 'Coiffure', 'active' => true],
            
            // Coloration
            ['name' => 'Coloration Complète', 'price' => 15000, 'duration' => 90, 'category' => 'Coloration', 'active' => true],
            ['name' => 'Mèches / Balayage', 'price' => 20000, 'duration' => 120, 'category' => 'Coloration', 'active' => true],
            ['name' => 'Retouche Racines', 'price' => 8000, 'duration' => 45, 'category' => 'Coloration', 'active' => true],
            
            // Soins
            ['name' => 'Soin Kératine', 'price' => 25000, 'duration' => 120, 'category' => 'Soins', 'active' => true],
            ['name' => 'Soin Hydratant', 'price' => 8000, 'duration' => 45, 'category' => 'Soins', 'active' => true],
            ['name' => 'Masque Réparateur', 'price' => 6000, 'duration' => 30, 'category' => 'Soins', 'active' => true],
            
            // Coiffures spéciales
            ['name' => 'Tresses Africaines', 'price' => 15000, 'duration' => 180, 'category' => 'Coiffures Spéciales', 'active' => true],
            ['name' => 'Chignon Mariage', 'price' => 20000, 'duration' => 90, 'category' => 'Coiffures Spéciales', 'active' => true],
            ['name' => 'Pose Extensions', 'price' => 35000, 'duration' => 180, 'category' => 'Coiffures Spéciales', 'active' => true],
            
            // Esthétique
            ['name' => 'Manucure Simple', 'price' => 3000, 'duration' => 30, 'category' => 'Esthétique', 'active' => true],
            ['name' => 'Pédicure Complète', 'price' => 5000, 'duration' => 45, 'category' => 'Esthétique', 'active' => true],
            ['name' => 'Maquillage Jour', 'price' => 5000, 'duration' => 30, 'category' => 'Esthétique', 'active' => true],
            ['name' => 'Maquillage Soirée/Mariage', 'price' => 15000, 'duration' => 60, 'category' => 'Esthétique', 'active' => true],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }

        // Ajouter une promotion sur un service
        $promoService = Service::where('name', 'Soin Kératine')->first();
        if ($promoService) {
            $promoService->update([
                'promotion_price' => 20000,
                'promotion_label' => 'Offre de bienvenue',
                'promotion_start' => now(),
                'promotion_end' => now()->addMonth(),
            ]);
        }

        // Lier tous les employés à tous les services
        $employees = \App\Models\Employee::all();
        $services = Service::all();
        
        foreach ($services as $service) {
            $service->employees()->syncWithoutDetaching($employees->pluck('id')->toArray());
        }
    }
}
