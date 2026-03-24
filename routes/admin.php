<?php

/**
 * Routes admin protégées.
 * Chargées avec middleware(['auth:web', 'admin']), prefix('admin'), name('admin.') via web.php.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\EmployeeMessageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ThemeController;

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN PROTÉGÉES
|--------------------------------------------------------------------------
| Routes accessibles uniquement aux administrateurs authentifiés.
| Protégées par les middlewares "auth:web" et "admin".
| Inclut : tableau de bord, gestion des employés, clients, services,
|          rendez-vous, paiements, stocks, congés, messages, rapports
|          et planning.
*/

// Tableau de bord administrateur avec statistiques générales
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Gestion du profil administrateur
Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.photo');
Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
    ->name('profile.photo.delete');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password');

// CRUD des employés et activation/désactivation
Route::resource('employees', EmployeeController::class);
Route::patch('employees/{employee}/toggle-active', [EmployeeController::class, 'toggleActive'])
    ->name('employees.toggle-active');

// CRUD des clients et activation/désactivation
Route::resource('clients', ClientController::class);
Route::patch('clients/{client}/reactivate', [ClientController::class, 'reactivate'])->name('clients.reactivate');
Route::patch('clients/{client}/deactivate', [ClientController::class, 'deactivate'])->name('clients.deactivate');

// CRUD des services, rendez-vous et stocks
Route::resource('services', ServiceController::class);
Route::get('/appointments/available-employees', [AppointmentController::class, 'getAvailableEmployees'])
    ->name('appointments.available-employees');
Route::resource('appointments', AppointmentController::class);
Route::resource('payments', PaymentController::class)->only(['index', 'show']);
Route::patch('payments/{payment}/status', [PaymentController::class, 'updateStatus'])
    ->name('payments.update-status');
Route::resource('stocks', StockController::class);

// Gestion des demandes de congé des employés
Route::get('/leaves', [LeaveRequestController::class, 'index'])
    ->name('leaves.index');
Route::get('/leaves/{leave}', [LeaveRequestController::class, 'show'])
    ->name('leaves.show');
Route::patch('/leaves/{leave}/approve', [LeaveRequestController::class, 'approve'])
    ->name('leaves.approve');
Route::patch('/leaves/{leave}/reject', [LeaveRequestController::class, 'reject'])
    ->name('leaves.reject');

// Messagerie : messages des employés vers l'admin
Route::get('/employee-messages', [EmployeeMessageController::class, 'index'])
    ->name('employee-messages.index');
Route::get('/employee-messages/{message}', [EmployeeMessageController::class, 'show'])
    ->name('employee-messages.show');
Route::patch('/employee-messages/{message}/reply', [EmployeeMessageController::class, 'reply'])
    ->name('employee-messages.reply');

// Rapports et statistiques avec export CSV
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'exportCsv'])
    ->name('reports.export');

// Gestion du planning des employés par l'admin
Route::prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])
        ->name('index');
    Route::get('/events', [ScheduleController::class, 'getEvents'])
        ->name('events');
    Route::get('/employee/{employee}', [ScheduleController::class, 'employeeSchedule'])
        ->name('employee');
    Route::put('/employee/{employee}', [ScheduleController::class, 'updateSchedule'])
        ->name('updateWorkingHours');
    // Blocage et déblocage de créneaux horaires
    Route::post('/block', [ScheduleController::class, 'storeBlockedSlot'])
        ->name('storeBlock');
    Route::delete('/block/{blockedSlot}', [ScheduleController::class, 'destroyBlockedSlot'])
        ->name('destroyBlock');
});

// Gestion du thème global par l'admin
Route::post('/theme', [ThemeController::class, 'update'])->name('theme.update');
