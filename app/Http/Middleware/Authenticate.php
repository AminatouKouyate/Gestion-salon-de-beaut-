<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Middleware d'authentification.
 *
 * Étend le middleware d'authentification de Laravel pour rediriger
 * les utilisateurs non authentifiés vers la page de connexion
 * correspondante selon le type de route (employé, client ou admin).
 */
class Authenticate extends Middleware
{
    /**
     * Détermine l'URL de redirection lorsque l'utilisateur n'est pas authentifié.
     *
     * Redirige vers la page de connexion appropriée en fonction du préfixe
     * de la route (employee.*, client.*, ou login par défaut pour l'admin).
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @return string|null  L'URL de redirection, ou null si la requête attend du JSON
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Redirection vers la page de connexion employé
            if ($request->routeIs('employee.*')) {
                return route('employee.login');
            }
            // Redirection vers la page de connexion client
            if ($request->routeIs('client.*')) {
                return route('client.login');
            }
            // Redirection par défaut vers la page de connexion admin
            return route('login');
        }
    }
}
