<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeSchedule;

$employees = Employee::where('is_active', true)->get();

foreach ($employees as $emp) {
    echo "Configuration des horaires pour {$emp->name}...\n";
    
    // Créer les horaires pour chaque jour de la semaine
    // 0=Dimanche, 1=Lundi, ..., 6=Samedi
    for ($day = 0; $day <= 6; $day++) {
        $isWorking = ($day >= 1 && $day <= 6); // Lundi-Samedi = travail, Dimanche = repos
        
        EmployeeSchedule::updateOrCreate(
            [
                'employee_id' => $emp->id,
                'day_of_week' => $day,
            ],
            [
                'is_working' => $isWorking,
                'start_time' => $isWorking ? '08:00' : '00:00',
                'end_time' => $isWorking ? '18:00' : '00:00',
                'break_start' => $isWorking ? '12:00' : null,
                'break_end' => $isWorking ? '13:00' : null,
            ]
        );
        
        $dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $status = $isWorking ? '08:00-18:00 (pause 12:00-13:00)' : 'Repos';
        echo "  {$dayNames[$day]}: {$status}\n";
    }
    echo "\n";
}

echo "✅ Horaires configurés!\n\n";

// Vérifier les créneaux disponibles
$service = \App\Models\Service::where('active', true)->first();
if ($service && $employees->count() > 0) {
    $emp = $employees->first();
    $tomorrow = \Carbon\Carbon::tomorrow();
    $slots = $emp->getAvailableSlotsForDate($tomorrow, $service->duration ?? 60);
    echo "Test créneaux pour demain ({$tomorrow->format('Y-m-d l')}): " . count($slots) . " créneaux\n";
    foreach (array_slice($slots, 0, 5) as $s) {
        echo "  {$s['formatted']}\n";
    }
    if (count($slots) > 5) echo "  ... et " . (count($slots) - 5) . " de plus\n";
}
