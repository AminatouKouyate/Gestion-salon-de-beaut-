<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Contrôleur de gestion des sessions authentifiées.
 *
 * Gère la connexion, la déconnexion et la redirection
 * vers le tableau de bord approprié selon le rôle de l'utilisateur
 * (admin, employé, client).
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Utilise la même vue de connexion que les clients, mais la logique de traitement sera différente.
        // Assurez-vous que la vue peut gérer cela, ou créez une vue dédiée pour l'administrateur.
        return view('auth.login');
    }

    /**
     * Gère une requête d'authentification entrante.
     *
     * Valide les identifiants, tente l'authentification et régénère
     * la session avant de rediriger vers le tableau de bord approprié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException Si les identifiants sont invalides
     */
    public function store(Request $request)
    {
        // Validation des champs email et mot de passe
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tentative d'authentification avec option « se souvenir de moi »
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Régénération de la session pour éviter les attaques de fixation de session
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    /**
     * Détermine la route de redirection en fonction du rôle de l'utilisateur.
     *
     * @return string L'URL de redirection vers le tableau de bord correspondant
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        // Vérifier le rôle de l'utilisateur et rediriger vers le bon tableau de bord
        switch ($user->role) {
            case User::ROLE_ADMIN:
                return route('admin.dashboard');

            case User::ROLE_EMPLOYEE:
                return route('employee.dashboard');

            case User::ROLE_CLIENT:
                return route('client.dashboard');

            default:
                // Par défaut, rediriger vers la page d'accueil
                return '/';
        }
    }

    /**
     * Détruit une session authentifiée (déconnexion).
     *
     * Déconnecte l'utilisateur, invalide la session et régénère
     * le jeton CSRF pour des raisons de sécurité.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // Déconnexion via le guard web
        Auth::guard('web')->logout();

        // Invalidation de la session et régénération du jeton CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
