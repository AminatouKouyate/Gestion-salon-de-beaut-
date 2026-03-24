<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware de redirection des utilisateurs déjà authentifiés.
 *
 * Empêche les utilisateurs connectés d'accéder aux pages de connexion
 * ou d'inscription en les redirigeant vers leur tableau de bord
 * correspondant (admin, employé ou client).
 */
class RedirectIfAuthenticated
{
    /**
     * Redirige l'utilisateur authentifié vers son tableau de bord selon son rôle.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next  Le prochain middleware
     * @param  string|null  ...$guards  Les guards d'authentification à vérifier
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Si aucun guard n'est spécifié, utiliser le guard par défaut (null)
        $guards = empty($guards) ? [null] : $guards;

        // Parcourir chaque guard pour vérifier si l'utilisateur est connecté
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user) {
                    // Rediriger selon le rôle de l'utilisateur
                    switch ($user->role) {
                        case User::ROLE_ADMIN:
                            return redirect()->route('admin.dashboard');
                        case User::ROLE_EMPLOYEE:
                            return redirect()->route('employee.dashboard');
                        case User::ROLE_CLIENT:
                            return redirect()->route('client.dashboard');
                        default:
                            return redirect(RouteServiceProvider::HOME);
                    }
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
