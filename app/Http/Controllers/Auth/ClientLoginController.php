<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientLoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gère une requête d'authentification.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tente de connecter l'utilisateur avec le guard clients
        if (Auth::guard('clients')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('client.dashboard'));
        }

        // Tente de connecter l'utilisateur avec le guard par défaut (admin)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirige en fonction du rôle
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.clients.index'));
            }

            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnecte le client.
     */
    public function logout(Request $request)
    {
        Auth::guard('clients')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
