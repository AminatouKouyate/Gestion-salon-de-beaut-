<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\BlockedSlot;
use App\Models\LeaveRequest;
use App\Models\Service;
use Carbon\Carbon;

echo "=== DIAGNOSTIC CRENEAUX ===\n\n";

echo "Employés actifs: " . Employee::where('is_active', true)->count() . "\n";
echo "Horaires configurés (EmployeeSchedule): " . EmployeeSchedule::count() . "\n";
echo "Créneaux bloqués (BlockedSlot): " . BlockedSlot::count() . "\n";
echo "Congés approuvés: " . LeaveRequest::where('status', 'approved')->count() . "\n";
echo "Services actifs: " . Service::where('active', true)->count() . "\n\n";

$employees = Employee::where('is_active', true)->get();
foreach ($employees as $emp) {
    $schedCount = $emp->schedules()->count();
    $svcCount = $emp->services()->count();
    echo "Employé #{$emp->id} {$emp->name} - horaires:{$schedCount}, services:{$svcCount}\n";
    
    if ($schedCount > 0) {
        $schedules = $emp->schedules()->get();
        foreach ($schedules as $s) {
            $working = $s->is_working ? 'OUI' : 'NON';
            echo "  Jour {$s->day_of_week}: travaille={$working}, {$s->start_time}-{$s->end_time}";
            if ($s->break_start) echo ", pause {$s->break_start}-{$s->break_end}";
            echo "\n";
        }
    } else {
        echo "  ⚠️ AUCUN HORAIRE CONFIGURÉ - tous les créneaux seront vides!\n";
    }
    
    // Test pour demain
    $tomorrow = Carbon::tomorrow();
    $service = Service::where('active', true)->first();
    if ($service) {
        $slots = $emp->getAvailableSlotsForDate($tomorrow, $service->duration ?? 60);
        echo "  Créneaux disponibles demain ({$tomorrow->format('Y-m-d')}, service {$service->duration}min): " . count($slots) . "\n";
    }
    echo "\n";
}

// Vérifier les blocked slots
$blocked = BlockedSlot::all();
if ($blocked->count() > 0) {
    echo "\n=== CRÉNEAUX BLOQUÉS ===\n";
    foreach ($blocked as $b) {
        $empName = $b->employee_id ? "Employé #{$b->employee_id}" : "GLOBAL";
        echo "{$empName}: {$b->start_datetime} → {$b->end_datetime} ({$b->reason})\n";
    }
}
