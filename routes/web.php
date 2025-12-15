<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Employee\EmployeeLoginController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\EmployeeAppointmentController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Employee\EmployeeServiceController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Employee\LeaveRequestController;
use App\Http\Controllers\Client\ChatbotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Page accueil
Route::get('/', function () {
    // Vérifie si un client est connecté
    if (auth()->guard('clients')->check()) {
        return redirect()->route('client.dashboard');
    }

    // Vérifie si un employé est connecté
    if (auth()->guard('employees')->check()) {
        return redirect()->route('employee.dashboard');
    }

    // Vérifie si un admin est connecté
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    // Pour les invités, afficher la page des services publics
    return redirect()->route('services.public');
});

// Services publics
Route::get('/services', [ServiceController::class, 'publicIndex'])->name('services.public');

// ---------------------------
// AUTH EMPLOYÉ
// ---------------------------
Route::prefix('employee')->name('employee.')->group(function () {
    // Routes publiques pour l'authentification de l'employé
    Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])->name('login')->middleware('guest:employees');
    Route::post('/login', [EmployeeLoginController::class, 'login'])->name('login.post')->middleware('guest:employees');
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout')->middleware('auth:employees');

    // Routes protégées pour l'employé authentifié
    Route::middleware('auth:employees')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        // Gestion des rendez-vous de l'employé
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [EmployeeAppointmentController::class, 'index'])->name('index');
            Route::get('/{appointment}', [EmployeeAppointmentController::class, 'show'])->name('show');
            Route::patch('/{appointment}/status', [EmployeeAppointmentController::class, 'updateStatus'])->name('updateStatus');
            Route::patch('/{appointment}/notes', [EmployeeAppointmentController::class, 'addNotes'])->name('addNotes');
        });

        Route::get('/services', [EmployeeServiceController::class, 'index'])->name('services.index');
        Route::resource('leaves', LeaveRequestController::class)->only(['index', 'create', 'store'])->names('leaves');
    });
});

// ---------------------------
// AUTH CLIENT
// ---------------------------
Route::prefix('client')->name('client.')->group(function () {
    // Routes publiques pour l'authentification du client
    Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('login')->middleware('guest:clients');
    Route::post('/login', [ClientLoginController::class, 'login'])->name('login.post')->middleware('guest:clients');

    // Routes pour l'inscription du client
    Route::get('/register', [ClientRegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest:clients');
    Route::post('/register', [ClientRegisterController::class, 'register'])->name('register.post')->middleware('guest:clients');

    Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout')->middleware('auth:clients');
});

// ---------------------------
// ROUTES PROTÉGÉES CLIENT
// ---------------------------
Route::middleware('auth:clients')->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Regrouper les routes de rendez-vous sous le préfixe 'client'
    Route::prefix('appointments')->name('appointments.')->group(function() {
        Route::get('/history', [AppointmentController::class, 'history'])->name('history');
        Route::get('/slots', [AppointmentController::class, 'getAvailableSlots'])->name('slots');
        Route::patch('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::resource('/', AppointmentController::class)->except(['destroy'])->parameters(['' => 'appointment']);
    });
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'downloadInvoice'])->name('payments.invoice');

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    Route::get('/services-list', [ServiceController::class, 'index'])->name('services');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Client\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\Client\NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});


// ---------------------------
// ROUTES ADMIN (auth normal)
// ---------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Tableau de bord admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestion des modules par l'admin
    Route::resource('employees', AdminEmployeeController::class);
    Route::resource('clients', AdminClientController::class);
    Route::resource('services', AdminServiceController::class);
    Route::resource('appointments', AdminAppointmentController::class);
    Route::resource('payments', AdminPaymentController::class);
    // Pour le stock, un CRUD complet est aussi une bonne pratique
    // Route::resource('stocks', StockController::class);

    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');

    // La route de déconnexion pour l'admin doit être dans un groupe authentifié
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


// ---------------------------
// ROUTES EMPLOYÉ PROTÉGÉES
// ---------------------------
// Le bloc dupliqué a été fusionné avec le bloc d'authentification ci-dessus pour une meilleure organisation.


// Webhook Stripe
Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook'])->name('stripe.webhook');

// Routes d'authentification pour l'admin (non authentifié)
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
});
