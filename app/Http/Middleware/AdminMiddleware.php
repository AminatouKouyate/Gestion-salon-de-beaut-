<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

/**
 * Middleware d'autorisation administrateur.
 *
 * Vérifie que l'utilisateur authentifié via le guard "web" possède
 * le rôle administrateur avant de lui donner accès aux routes protégées
 * du back-office d'administration.
 */
class AdminMiddleware
{
    /**
     * Traite la requête entrante et vérifie les droits administrateur.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @param  \Closure  $next  Le prochain middleware ou contrôleur dans la pile
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier que l'utilisateur est connecté via le guard web (admin)
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        // Vérifier que l'utilisateur est bien un admin
        if (Auth::guard('web')->user()->role !== User::ROLE_ADMIN) {
            abort(403, 'Accès refusé : droits administrateur requis.');
        }

        return $next($request);
    }
}
