<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Contrôleur de connexion dédié aux clients.
 *
 * Gère l'affichage du formulaire de connexion client, la tentative
 * d'authentification via le guard « clients » et la déconnexion.
 */
class ClientLoginController extends Controller
{
    /**
     * URL de redirection après connexion réussie du client.
     *
     * @var string
     */
    protected $redirectTo = '/client/dashboard';

    /**
     * Affiche le formulaire de connexion réservé aux clients.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.client-login');
    }

    /**
     * Retourne le guard d'authentification utilisé pour les clients.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('clients');
    }

    /**
     * Traite la tentative de connexion d'un client.
     *
     * Valide les identifiants, tente l'authentification via le guard clients,
     * régénère la session et redirige vers le tableau de bord client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException Si les identifiants sont invalides
     */
    public function login(Request $request)
    {
        // Validation des champs email et mot de passe
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tentative d'authentification via le guard clients avec option « se souvenir de moi »
        if (! $this->guard()->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Régénération de la session pour la sécurité
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Déconnecte le client de l'application.
     *
     * Invalide la session et régénère le jeton CSRF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        // Invalidation de la session et régénération du jeton CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
