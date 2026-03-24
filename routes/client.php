<?php

/**
 * Routes client : authentification et routes protégées.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\Auth\ClientForgotPasswordController;
use App\Http\Controllers\Auth\ClientResetPasswordController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\ChatbotController;
use App\Http\Controllers\Client\NotificationController;

/*
|--------------------------------------------------------------------------
| AUTH CLIENT
|--------------------------------------------------------------------------
| Routes d'authentification pour les clients.
| Utilise le guard "clients".
| Inclut : connexion, inscription, déconnexion et réinitialisation du mot de passe.
*/
Route::prefix('client')->name('client.')->group(function () {

    Route::middleware('guest:clients')->group(function () {
        // Formulaire et traitement de connexion client
        Route::get('/login', [ClientLoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [ClientLoginController::class, 'login'])
            ->middleware('throttle:5,1'); // Max 5 tentatives par minute

        // Formulaire et traitement d'inscription client
        Route::get('/register', [ClientRegisterController::class, 'showRegistrationForm'])
            ->name('register');

        Route::post('/register', [ClientRegisterController::class, 'register']);

        // Réinitialisation du mot de passe client
        Route::get('/password/reset', [ClientForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('/password/email', [ClientForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        Route::get('/password/reset/{token}', [ClientResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/password/reset', [ClientResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    // Déconnexion client (nécessite d'être authentifié)
    Route::post('/logout', [ClientLoginController::class, 'logout'])
        ->middleware('auth:clients')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTES CLIENT PROTÉGÉES
|--------------------------------------------------------------------------
| Routes accessibles uniquement aux clients authentifiés et actifs.
| Protégées par les middlewares "auth:clients" et "client.active".
| Inclut : tableau de bord, profil, rendez-vous, paiements, services,
|          chatbot et notifications.
*/
Route::middleware(['auth:clients', 'client.active'])->prefix('client')->name('client.')->group(function () {

    // Tableau de bord client
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])
        ->name('dashboard');

    // Gestion du profil client
    Route::get('/profile', [ProfileController::class, 'profile'])
        ->name('profile');

    Route::put('/profile', [ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::post('/profile/deactivate', [ProfileController::class, 'deactivate'])
        ->name('profile.deactivate');

    // Gestion de la photo de profil client
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

    // Gestion des rendez-vous client
    Route::prefix('appointments')->name('appointments.')->group(function () {

        // Calendrier interactif des rendez-vous
        Route::get('/calendar', [AppointmentController::class, 'calendar'])
            ->name('calendar');

        // Historique des rendez-vous passés
        Route::get('/history', [AppointmentController::class, 'history'])
            ->name('history');

        // Récupération des créneaux horaires disponibles (API JSON)
        Route::get('/available-slots', [AppointmentController::class, 'getAvailableSlots'])
            ->name('available-slots');

        // Récupération des employés disponibles pour un service donné (API JSON)
        Route::get('/employees', [AppointmentController::class, 'getEmployeesForService'])
            ->name('employees');

        // Annulation d'un rendez-vous par le client
        Route::patch('/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('cancel');

        // CRUD des rendez-vous (index, create, store, show, edit, update)
        Route::resource('/', AppointmentController::class)
            ->except(['destroy'])
            ->parameters(['' => 'appointment']);
    });

    // Endpoint JSON pour le calendrier FullCalendar
    Route::get('/appointments/calendar/events', [AppointmentController::class, 'calendarEvents'])
        ->name('appointments.calendar.events');

    // Gestion des paiements client (liste, création, détail)
    Route::resource('payments', PaymentController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Simulation de paiement (enregistrement direct en base sans passerelle réelle)
    Route::post('/payments/simulate/{appointment}', [PaymentController::class, 'simulate'])
        ->name('payments.simulate');

    // Factures : affichage et téléchargement PDF
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'showInvoice'])
        ->name('payments.invoice');

    Route::get('/payments/{payment}/invoice/download', [PaymentController::class, 'downloadInvoice'])
        ->name('payments.invoice.download');

    // Paiements mobiles (Orange Money, Wave)
    Route::get('/payments/{payment}/mobile/{method}', [PaymentController::class, 'showMobilePayment'])
        ->name('payments.mobile');

    Route::get('/payments/{payment}/check-status', [PaymentController::class, 'checkMobilePaymentStatus'])
        ->name('payments.check-status');

    // Traitement des paiements en ligne (Stripe/PayPal) - redirection/initiation
    Route::get('/payments/process', [PaymentController::class, 'process'])
        ->name('payments.process');

    // Consultation des services par le client
    Route::get('/services-list', [ServiceController::class, 'index'])
        ->name('services');

    Route::get('/services-list/{service}', [ServiceController::class, 'show'])
        ->name('services.show');

    // Chatbot assistant pour les clients
    Route::get('/chatbot', [ChatbotController::class, 'index'])
        ->name('chatbot.index');

    Route::get('/chatbot/history', [ChatbotController::class, 'history'])
        ->name('chatbot.history');

    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])
        ->name('chatbot.send');

    // Gestion des notifications client
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])
            ->name('index');
        Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])
            ->name('markRead');
        Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead'])
            ->name('markAllRead');
    });
});
