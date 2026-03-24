<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware de vérification de l'état du compte client.
 *
 * S'assure que le client authentifié possède un compte actif.
 * Si le compte est désactivé, le client est automatiquement déconnecté
 * et redirigé vers la page de connexion avec un message d'erreur.
 */
class EnsureClientIsActive
{
    /**
     * Vérifie que le compte du client est actif avant de poursuivre la requête.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @param  \Closure  $next  Le prochain middleware ou contrôleur dans la pile
     * @return mixed  La réponse HTTP ou une redirection vers la page de connexion
     */
    public function handle(Request $request, Closure $next)
    {
        $client = Auth::guard('clients')->user();

        // Traiter la valeur null comme active (rétrocompatibilité)
        if ($client && $client->active === false) {
            // Déconnecter le client et invalider sa session
            Auth::guard('clients')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('client.login')
                ->with('error', 'Votre compte a été désactivé. Contactez le salon pour plus d\'informations.');
        }

        return $next($request);
    }
}
