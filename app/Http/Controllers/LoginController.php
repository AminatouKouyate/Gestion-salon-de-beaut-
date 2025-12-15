<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post-authentication redirect path based on user role.
     *
     * @return string
     */
    public function redirectTo()
    {
        $user = Auth::user();

        switch ($user->role) {
            case User::ROLE_ADMIN:
                return '/admin/appointments'; // Redirige l'admin vers la liste des RDV
            case User::ROLE_EMPLOYEE:
                return '/employee/dashboard'; // À définir : tableau de bord de l'employé
            case User::ROLE_CLIENT:
                return '/'; // Redirige le client vers la page d'accueil
            default:
                return RouteServiceProvider::HOME; // Redirection par défaut
        }
    }
}
