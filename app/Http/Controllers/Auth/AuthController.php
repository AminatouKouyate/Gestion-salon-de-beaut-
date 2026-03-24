<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Contrôleur d'authentification général.
 *
 * Gère l'inscription, la connexion et la déconnexion des utilisateurs
 * avec redirection basée sur le rôle (admin, employé, client).
 */
class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin() {
        return view('auth.login');
    }

    /**
     * Affiche le formulaire d'inscription.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister() {
        return view('auth.register');
    }

    /**
     * Traite la requête d'inscription d'un nouvel utilisateur.
     *
     * Valide les données du formulaire, crée l'utilisateur avec le rôle
     * « client » par défaut, le connecte automatiquement puis le redirige
     * vers son tableau de bord.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des champs obligatoires
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        // Création de l'utilisateur en base de données avec mot de passe hashé
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client', // valeur par défaut
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        return redirect('/client');
    }

    /**
     * Traite la requête de connexion d'un utilisateur.
     *
     * Vérifie les identifiants puis redirige l'utilisateur vers le
     * tableau de bord correspondant à son rôle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validation des identifiants
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Tentative d'authentification avec les identifiants fournis
        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            // Redirection selon le rôle de l'utilisateur
            return match ($user->role) {
                'admin'   => redirect('/admin'),
                'employe' => redirect('/employe'),
                default   => redirect('/client'),
            };
        }

        // Échec d'authentification : retour avec message d'erreur
        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.'
        ]);
    }

    /**
     * Déconnecte l'utilisateur et le redirige vers la page de connexion.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
