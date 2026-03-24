<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware de vérification du rôle utilisateur.
 *
 * Vérifie que l'utilisateur authentifié possède le rôle requis
 * pour accéder à la route demandée. Retourne une erreur 403
 * si le rôle ne correspond pas.
 */
class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté possède le rôle spécifié.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @param  \Closure  $next  Le prochain middleware ou contrôleur dans la pile
     * @param  string  $role  Le rôle requis pour accéder à la route
     * @return mixed  La réponse HTTP ou une erreur 403 si le rôle est incorrect
     */
    public function handle($request, Closure $next, $role)
    {
        // Vérifier que l'utilisateur est connecté et possède le bon rôle
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Accès interdit');
        }

        return $next($request);
    }
}
