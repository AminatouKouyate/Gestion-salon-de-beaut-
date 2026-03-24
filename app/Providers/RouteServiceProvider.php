<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * Fournisseur de services de routage de l'application.
 *
 * Configure le chargement des fichiers de routes (web et API),
 * définit la route d'accueil par défaut après authentification,
 * et met en place la limitation de débit pour les requêtes API.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Chemin vers la route d'accueil de l'application.
     *
     * Les utilisateurs sont généralement redirigés ici après l'authentification.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Initialise la configuration des routes et des liaisons de modèles.
     *
     * Charge les fichiers de routes web.php (avec le middleware "web")
     * et api.php (avec le middleware "api" et le préfixe "/api").
     *
     * @return void
     */
    public function boot(): void
    {
        // Configurer les limiteurs de débit
        $this->configureRateLimiting();

        // Charger les fichiers de routes
        $this->routes(function () {
            // Routes API avec le préfixe /api et le middleware API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Routes web avec le middleware web (sessions, CSRF, etc.)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure les limiteurs de débit pour l'application.
     *
     * Limite les requêtes API à 60 par minute par utilisateur authentifié
     * ou par adresse IP pour les utilisateurs non authentifiés.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
