<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * Contrôleur d'inscription des utilisateurs.
 *
 * Gère l'affichage du formulaire d'inscription et la création
 * d'un nouveau compte utilisateur avec le rôle « client » par défaut.
 * Connecte automatiquement l'utilisateur après inscription.
 */
class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traite la requête d'inscription d'un nouvel utilisateur.
     *
     * Valide les données du formulaire (nom, e-mail unique, mot de passe confirmé),
     * crée l'utilisateur en base de données avec le rôle « client » par défaut,
     * le connecte automatiquement et régénère la session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des données d'inscription avec les règles de mot de passe par défaut
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Création de l'utilisateur en base de données avec mot de passe hashé
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // Assigne le rôle 'client' par défaut
        ]);

        // Connexion automatique de l'utilisateur après inscription
        Auth::login($user);

        // Régénération de la session pour la sécurité
        $request->session()->regenerate();

        // Redirection vers le tableau de bord client
        return redirect()->intended(route('client.dashboard'));
    }
}
