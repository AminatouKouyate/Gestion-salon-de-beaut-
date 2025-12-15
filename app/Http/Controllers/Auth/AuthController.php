<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- AFFICHAGE DES FORMULAIRES ---
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    // --- INSCRIPTION ---
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client', // valeur par défaut
        ]);

        Auth::login($user);

        return redirect('/client');
    }

    // --- CONNEXION ---
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            // Redirection selon rôle
            return match ($user->role) {
                'admin'   => redirect('/admin'),
                'employe' => redirect('/employe'),
                default   => redirect('/client'),
            };
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.'
        ]);
    }

    // --- DECONNEXION ---
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
