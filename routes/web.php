<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\Auth\ClientForgotPasswordController;
use App\Http\Controllers\Auth\ClientResetPasswordController;
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
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Employee\LeaveRequestController;
use App\Http\Controllers\Client\ChatbotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| PAGE D’ACCUEIL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Toujours afficher la page d'accueil avec les options de connexion
    return view('auth.welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ADMIN (guard web)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| SERVICES PUBLICS
|--------------------------------------------------------------------------
*/
Route::get('/services', [\App\Http\Controllers\Client\ServiceController::class, 'publicIndex'])
    ->name('services.public');

/*
|--------------------------------------------------------------------------
| AUTH CLIENT
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->group(function () {

    Route::middleware('guest:clients')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\ClientLoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [\App\Http\Controllers\Auth\ClientLoginController::class, 'login']);

        Route::get('/register', [\App\Http\Controllers\Auth\ClientRegisterController::class, 'showRegistrationForm'])
            ->name('register');

        Route::post('/register', [\App\Http\Controllers\Auth\ClientRegisterController::class, 'register']);

        Route::get('/password/reset', [ClientForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('/password/email', [ClientForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        Route::get('/password/reset/{token}', [ClientResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/password/reset', [ClientResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    Route::post('/logout', [\App\Http\Controllers\Auth\ClientLoginController::class, 'logout'])
        ->middleware('auth:clients')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTES CLIENT PROTÉGÉES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:clients', 'client.active'])->prefix('client')->name('client.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Client\ProfileController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'profile'])
        ->name('profile');

    Route::put('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::post('/profile/deactivate', [\App\Http\Controllers\Client\ProfileController::class, 'deactivate'])
        ->name('profile.deactivate');

    Route::prefix('appointments')->name('appointments.')->group(function () {

        Route::get('/history', [\App\Http\Controllers\Client\AppointmentController::class, 'history'])
            ->name('history');

        Route::get('/slots', [\App\Http\Controllers\Client\AppointmentController::class, 'getAvailableSlots'])
            ->name('slots');

        Route::get('/employees', [\App\Http\Controllers\Client\AppointmentController::class, 'getEmployeesForService'])
            ->name('employees');

        Route::patch('/{appointment}/cancel', [\App\Http\Controllers\Client\AppointmentController::class, 'cancel'])
            ->name('cancel');

        Route::resource('/', \App\Http\Controllers\Client\AppointmentController::class)
            ->except(['destroy'])
            ->parameters(['' => 'appointment']);
    });

    Route::resource('payments', \App\Http\Controllers\Client\PaymentController::class);

    Route::get('/payments/{payment}/invoice', [\App\Http\Controllers\Client\PaymentController::class, 'showInvoice'])
        ->name('payments.invoice');

    Route::get('/payments/{payment}/invoice/download', [\App\Http\Controllers\Client\PaymentController::class, 'downloadInvoice'])
        ->name('payments.invoice.download');

    Route::get('/services-list', [\App\Http\Controllers\Client\ServiceController::class, 'index'])
        ->name('services');
    
    Route::get('/services-list/{service}', [\App\Http\Controllers\Client\ServiceController::class, 'show'])
        ->name('services.show');

    Route::get('/chatbot', [\App\Http\Controllers\Client\ChatbotController::class, 'index'])
        ->name('chatbot.index');

    Route::get('/chatbot/history', [\App\Http\Controllers\Client\ChatbotController::class, 'history'])
        ->name('chatbot.history');

    Route::post('/chatbot/send', [\App\Http\Controllers\Client\ChatbotController::class, 'sendMessage'])
        ->name('chatbot.send');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\NotificationController::class, 'index'])
            ->name('index');
        Route::post('/{notification}/read', [\App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])
            ->name('markRead');
        Route::post('/mark-all-read', [\App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])
            ->name('markAllRead');
    });
});

/*
|--------------------------------------------------------------------------
| AUTH EMPLOYÉ
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->name('employee.')->group(function () {

    Route::middleware('guest:employees')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Employee\EmployeeLoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [\App\Http\Controllers\Employee\EmployeeLoginController::class, 'login']);
    });

    Route::post('/logout', [\App\Http\Controllers\Employee\EmployeeLoginController::class, 'logout'])
        ->middleware('auth:employees')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTES EMPLOYÉ PROTÉGÉES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:employees')->prefix('employee')->name('employee.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Employee\EmployeeDashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('appointments')->name('appointments.')->group(function () {

        Route::get('/', [\App\Http\Controllers\Employee\EmployeeAppointmentController::class, 'index'])
            ->name('index');

        Route::get('/{appointment}', [\App\Http\Controllers\Employee\EmployeeAppointmentController::class, 'show'])
            ->name('show');

        Route::patch('/{appointment}/status', [\App\Http\Controllers\Employee\EmployeeAppointmentController::class, 'updateStatus'])
            ->name('updateStatus');

        Route::patch('/{appointment}/notes', [\App\Http\Controllers\Employee\EmployeeAppointmentController::class, 'addNotes'])
            ->name('addNotes');
    });

    Route::get('/services', [\App\Http\Controllers\Employee\EmployeeServiceController::class, 'index'])
        ->name('services.index');

    Route::resource('leaves', \App\Http\Controllers\Employee\LeaveRequestController::class)
        ->only(['index', 'create', 'store']);

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Employee\EmployeeNotificationController::class, 'index'])
            ->name('index');
        Route::patch('/{notification}/read', [\App\Http\Controllers\Employee\EmployeeNotificationController::class, 'markAsRead'])
            ->name('markAsRead');
        Route::patch('/mark-all-read', [\App\Http\Controllers\Employee\EmployeeNotificationController::class, 'markAllAsRead'])
            ->name('markAllAsRead');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN PROTÉGÉES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);
        Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
        Route::resource('stocks', \App\Http\Controllers\Admin\StockController::class);

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');
    });

/*
|--------------------------------------------------------------------------
| STRIPE WEBHOOK
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [\App\Http\Controllers\Client\PaymentController::class, 'stripeWebhook'])
    ->name('stripe.webhook');
