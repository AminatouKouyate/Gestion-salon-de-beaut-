<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
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
