<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeSchedule;

$employees = Employee::all();

foreach ($employees as $emp) {
    // Lundi (1) à Samedi (6) : travail 9h-18h avec pause 13h-14h
    foreach ([1, 2, 3, 4, 5, 6] as $day) {
        EmployeeSchedule::updateOrCreate(
            ['employee_id' => $emp->id, 'day_of_week' => $day],
            [
                'is_working' => true,
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_start' => '13:00',
                'break_end' => '14:00',
            ]
        );
    }
    // Dimanche (0) : repos
    EmployeeSchedule::updateOrCreate(
        ['employee_id' => $emp->id, 'day_of_week' => 0],
        [
            'is_working' => false,
            'start_time' => '00:00',
            'end_time' => '00:00',
            'break_start' => '00:00',
            'break_end' => '00:00',
        ]
    );
    echo "Horaires créés pour: {$emp->name}\n";
}

echo "\nTotal: " . EmployeeSchedule::count() . " horaires en base.\n";
