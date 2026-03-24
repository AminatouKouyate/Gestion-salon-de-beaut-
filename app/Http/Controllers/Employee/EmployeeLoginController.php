<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Contrôleur pour l'authentification des employés.
 * 
 * Gère la connexion, la déconnexion et l'affichage du formulaire
 * de connexion pour les employés du salon.
 */
class EmployeeLoginController extends Controller
{
    /**
     * URL de redirection après connexion réussie.
     *
     * @var string
     */
    protected $redirectTo = '/employee/dashboard';

    /**
     * Affiche le formulaire de connexion pour les employés.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.employee-login');
    }

    /**
     * Retourne le guard d'authentification utilisé pour les employés.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('employees');
    }

    /**
     * Traite une tentative de connexion d'un employé.
     * 
     * Valide les identifiants, tente l'authentification et régénère
     * la session en cas de succès.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant email et mot de passe
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException  Si les identifiants sont invalides
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tentative d'authentification avec le guard employé
        if (! $this->guard()->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Régénération de la session pour sécurité
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Déconnecte l'employé de l'application.
     * 
     * Invalide la session et régénère le token CSRF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
