<?php

/**
 * Fichier de définition des routes web de l'application.
 *
 * Organise toutes les routes HTTP de l'application en sections :
 * - Page d'accueil
 * - Authentification admin (guard web)
 * - Services publics
 * - Inclusion des fichiers de routes client, employé et admin
 * - Webhooks de paiement (Stripe, Orange Money, Wave)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Auth\AdminResetPasswordController;
use App\Http\Controllers\Client\PaymentController;

/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL
|--------------------------------------------------------------------------
| Route principale qui affiche la page de bienvenue avec les options
| de connexion pour les différents types d'utilisateurs.
*/
Route::get('/', function () {
    return view('auth.welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ADMIN (guard web)
|--------------------------------------------------------------------------
| Routes d'authentification pour les administrateurs.
| Utilise le guard "web" par défaut de Laravel.
| Inclut : connexion, déconnexion et réinitialisation du mot de passe.
*/
Route::middleware('guest:web')->group(function () {
    // Formulaire et traitement de connexion admin
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1'); // Max 5 tentatives par minute

    // Réinitialisation du mot de passe admin
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('admin.password.request');
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.email');
    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])
        ->name('admin.password.reset');
    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])
        ->name('admin.password.update');
});

// Déconnexion admin (nécessite d'être authentifié)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| SERVICES PUBLICS
|--------------------------------------------------------------------------
| Routes accessibles sans authentification pour consulter
| la liste des services proposés par le salon.
*/
Route::get('/services', [\App\Http\Controllers\Client\ServiceController::class, 'publicIndex'])
    ->name('services.public');

// Alias de compatibilité pour les anciennes vues qui utilisent route('service.index')
Route::redirect('/service-index', '/services')->name('service.index');

/*
|--------------------------------------------------------------------------
| ROUTES CLIENT, EMPLOYÉ ET ADMIN
|--------------------------------------------------------------------------
| Chargement des fichiers de routes séparés pour chaque type d'utilisateur.
*/
require __DIR__.'/client.php';
require __DIR__.'/employee.php';

Route::middleware(['auth:web', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

/*
|--------------------------------------------------------------------------
| WEBHOOKS DE PAIEMENT
|--------------------------------------------------------------------------
| Routes de callback pour les passerelles de paiement externes.
| Ces routes sont appelées par les serveurs Stripe, Orange Money
| et Wave pour notifier l'application des résultats de paiement.
*/
Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook'])
    ->name('stripe.webhook');

Route::post('/orange-money/callback', [PaymentController::class, 'orangeMoneyCallback'])
    ->name('orange-money.callback');

Route::post('/wave/callback', [PaymentController::class, 'waveCallback'])
    ->name('wave.callback');
