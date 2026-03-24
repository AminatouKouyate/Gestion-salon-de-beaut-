<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Contrôleur de réinitialisation de mot de passe pour les administrateurs.
 *
 * Gère l'affichage du formulaire de réinitialisation et le traitement
 * de la requête de changement de mot de passe via le broker « users ».
 */
class AdminResetPasswordController extends Controller
{
    /**
     * Affiche le formulaire de réinitialisation de mot de passe pour un administrateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token  Le jeton de réinitialisation reçu par e-mail
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.admin-reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Traite la requête de réinitialisation de mot de passe de l'administrateur.
     *
     * Valide les données (jeton, e-mail, nouveau mot de passe), effectue
     * la réinitialisation via le broker « users », met à jour le mot de passe
     * hashé et régénère le jeton « remember me ».
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validation du jeton, de l'e-mail et du nouveau mot de passe
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Réinitialisation du mot de passe via le broker « users »
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Mise à jour du mot de passe hashé et du jeton « remember me »
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Déclenchement de l'événement de réinitialisation de mot de passe
                event(new PasswordReset($user));
            }
        );

        // Redirection vers la page de connexion en cas de succès, sinon retour avec erreur
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
