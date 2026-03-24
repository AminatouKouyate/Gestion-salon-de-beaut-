<?php

/**
 * Routes employé : authentification et routes protégées.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\EmployeeLoginController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\EmployeeAppointmentController;
use App\Http\Controllers\Employee\EmployeeServiceController;
use App\Http\Controllers\Employee\EmployeeProfileController;
use App\Http\Controllers\Employee\EmployeePasswordController;
use App\Http\Controllers\Employee\EmployeeMessageController;
use App\Http\Controllers\Employee\EmployeeNotificationController;
use App\Http\Controllers\Employee\PaymentController;
use App\Http\Controllers\Employee\LeaveRequestController;
use App\Http\Controllers\Employee\ScheduleController;
use App\Http\Controllers\Auth\EmployeeForgotPasswordController;
use App\Http\Controllers\Auth\EmployeeResetPasswordController;

/*
|--------------------------------------------------------------------------
| AUTH EMPLOYÉ
|--------------------------------------------------------------------------
| Routes d'authentification pour les employés.
| Utilise le guard "employees".
| Inclut : connexion, déconnexion et réinitialisation du mot de passe.
*/
Route::prefix('employee')->name('employee.')->group(function () {

    Route::middleware('guest:employees')->group(function () {
        // Formulaire et traitement de connexion employé
        Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [EmployeeLoginController::class, 'login'])
            ->middleware('throttle:5,1'); // Max 5 tentatives par minute

        // Réinitialisation du mot de passe employé
        Route::get('/password/reset', [EmployeeForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('/password/email', [EmployeeForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        Route::get('/password/reset/{token}', [EmployeeResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/password/reset', [EmployeeResetPasswordController::class, 'reset'])
            ->name('password.reset.update');
    });

    // Déconnexion employé (nécessite d'être authentifié)
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])
        ->middleware('auth:employees')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTES EMPLOYÉ PROTÉGÉES
|--------------------------------------------------------------------------
| Routes accessibles uniquement aux employés authentifiés.
| Protégées par le middleware "auth:employees".
| Inclut : tableau de bord, profil, rendez-vous, services, paiements,
|          congés, planning et notifications.
*/
Route::middleware('auth:employees')->prefix('employee')->name('employee.')->group(function () {

    // Tableau de bord employé
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
        ->name('dashboard');

    // Gestion du profil employé
    Route::get('/profile', [EmployeeProfileController::class, 'index'])
        ->name('profile');
    Route::put('/profile', [EmployeeProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [EmployeeProfileController::class, 'updatePassword'])
        ->name('profile.password');
    Route::post('/profile/photo', [EmployeeProfileController::class, 'updatePhoto'])
        ->name('profile.photo');
    Route::delete('/profile/photo', [EmployeeProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

    // Changement de mot de passe (page dédiée)
    Route::get('/password/edit', [EmployeePasswordController::class, 'edit'])
        ->name('password.edit');
    Route::put('/password', [EmployeePasswordController::class, 'update'])
        ->name('password.update');

    // Messages de l'employé vers l'administration
    Route::resource('messages', EmployeeMessageController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Gestion des rendez-vous de l'employé
    Route::prefix('appointments')->name('appointments.')->group(function () {

        // Liste des rendez-vous assignés à l'employé
        Route::get('/', [EmployeeAppointmentController::class, 'index'])
            ->name('index');

        // Calendrier interactif FullCalendar pour l'employé
        Route::get('/calendar', [EmployeeAppointmentController::class, 'calendar'])
            ->name('calendar');

        // Endpoint JSON pour charger les rendez-vous dans FullCalendar
        Route::get('/events', [EmployeeAppointmentController::class, 'events'])
            ->name('events');

        // Historique des rendez-vous passés
        Route::get('/history', [EmployeeAppointmentController::class, 'history'])
            ->name('history');

        // Détail d'un rendez-vous
        Route::get('/{appointment}', [EmployeeAppointmentController::class, 'show'])
            ->name('show');

        // Mise à jour du statut d'un rendez-vous (confirmé, terminé, etc.)
        Route::patch('/{appointment}/status', [EmployeeAppointmentController::class, 'updateStatus'])
            ->name('updateStatus');

        // Ajout de notes à un rendez-vous
        Route::post('/{appointment}/note', [EmployeeAppointmentController::class, 'addNote'])
            ->name('addNote');

        Route::patch('/{appointment}/notes', [EmployeeAppointmentController::class, 'addNotes'])
            ->name('addNotes');
    });

    // Liste des services proposés par le salon
    Route::get('/services', [EmployeeServiceController::class, 'index'])
        ->name('services.index');

    // Gestion des paiements côté employé
    Route::resource('payments', PaymentController::class)
        ->only(['index', 'show', 'create', 'store']);

    // Demandes de congé de l'employé
    Route::resource('leaves', LeaveRequestController::class)
        ->only(['index', 'create', 'store']);

    // Planning et horaires de travail de l'employé
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])
            ->name('index');
        Route::get('/events', [ScheduleController::class, 'getEvents'])
            ->name('events');
        Route::get('/working-hours', [ScheduleController::class, 'workingHours'])
            ->name('working-hours');
        Route::get('/days-off', [ScheduleController::class, 'daysOff'])
            ->name('days-off');
    });

    // Gestion des notifications employé
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [EmployeeNotificationController::class, 'index'])
            ->name('index');
        Route::patch('/{notification}/read', [EmployeeNotificationController::class, 'markAsRead'])
            ->name('markAsRead');
        Route::patch('/mark-all-read', [EmployeeNotificationController::class, 'markAllAsRead'])
            ->name('markAllAsRead');
    });

});
