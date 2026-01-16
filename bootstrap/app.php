<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Models\User;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'client.active' => \App\Http\Middleware\EnsureClientIsActive::class,
        ]);

        // Redirection pour les utilisateurs connectés (middleware 'guest')
        $middleware->redirectUsersTo(function (Request $request) {
            // Vérification des gardes spécifiques
            if (auth()->guard('clients')->check()) {
                return route('client.dashboard');
            }
            if (auth()->guard('employees')->check()) {
                return route('employee.dashboard');
            }
            if (auth()->guard('web')->check()) {
                return route('admin.dashboard');
            }
            return route('home');
        });

        // Redirection pour les utilisateurs non connectés (middleware 'auth')
        $middleware->redirectGuestsTo(function (Request $request) {
            // Déterminer vers quelle page de login rediriger selon le path
            $path = $request->path();
            
            if (str_starts_with($path, 'client')) {
                return route('client.login');
            }
            if (str_starts_with($path, 'employee')) {
                return route('employee.login');
            }
            if (str_starts_with($path, 'admin')) {
                return route('login');
            }
            
            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
