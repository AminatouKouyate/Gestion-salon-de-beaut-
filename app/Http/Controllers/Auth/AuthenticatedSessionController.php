<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Utilise la même vue de login que les clients, mais la logique de post sera différente.
        // Assurez-vous que la vue peut gérer cela, ou créez une vue dédiée pour l'admin.
        return view('auth.login');
    }

    /**
     * Gère une requête d'authentification entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    /**
     * Détermine la route de redirection en fonction du rôle de l'utilisateur.
     *
     * @return string
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
