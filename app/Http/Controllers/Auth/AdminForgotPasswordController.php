<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * Contrôleur de mot de passe oublié pour les administrateurs.
 *
 * Permet aux administrateurs de demander un lien de réinitialisation
 * de mot de passe envoyé par e-mail via le broker « users ».
 */
class AdminForgotPasswordController extends Controller
{
    /**
     * Affiche le formulaire de demande de réinitialisation de mot de passe pour les administrateurs.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.admin-forgot-password');
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe par e-mail à l'administrateur.
     *
     * Valide l'adresse e-mail fournie, puis utilise le broker « users »
     * pour envoyer le lien. Retourne un message de succès ou d'erreur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validation de l'adresse e-mail
        $request->validate([
            'email' => 'required|email',
        ]);

        // Envoi du lien de réinitialisation via le broker « users »
        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        // Retour avec message de succès ou d'erreur selon le résultat
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
