<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * Contrôleur de gestion du profil administrateur.
 * 
 * Ce contrôleur permet à l'administrateur de gérer son compte personnel :
 * - Consultation et modification des informations personnelles (nom, email)
 * - Upload, mise à jour et suppression de la photo de profil
 * - Changement du mot de passe avec vérification de l'ancien mot de passe
 * 
 * Toutes les actions nécessitent une authentification via le guard 'web'.
 * Les photos de profil sont stockées sur le disque 'public' dans le dossier 'photos/admins'.
 * 
 * @package App\Http\Controllers\Admin
 */
class ProfileController extends Controller
{
    /**
     * Affiche la page de profil de l'administrateur.
     * 
     * Récupère l'utilisateur actuellement authentifié via le guard 'web'
     * et transmet ses données à la vue pour affichage.
     *
     * @return \Illuminate\View\View Vue du profil administrateur
     */
    public function index()
    {
        // Récupération de l'administrateur connecté via le guard 'web'
        $user = Auth::guard('web')->user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Met à jour les informations du profil (nom et email).
     * 
     * Valide que le nom est renseigné et que l'email est unique
     * (en excluant l'utilisateur courant de la vérification d'unicité).
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant 'name' et 'email'
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     */
    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Validation des champs nom et email avec messages d'erreur personnalisés en français
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ]);

        // Mise à jour du nom et de l'email en base de données
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Met à jour la photo de profil de l'administrateur.
     * 
     * Gère le cycle complet du changement de photo :
     * 1. Validation du fichier (format image, taille max 2 Mo)
     * 2. Suppression de l'ancienne photo du disque si elle existe
     * 3. Stockage de la nouvelle photo dans 'photos/admins'
     * 4. Mise à jour du chemin en base de données
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant le fichier 'photo'
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Validation : le fichier doit être une image de format autorisé, max 2 Mo
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'photo.required' => 'Veuillez sélectionner une photo.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        // Supprimer l'ancienne photo du disque public si elle existe
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Stocker la nouvelle photo dans le dossier 'photos/admins' du disque public
        $path = $request->file('photo')->store('photos/admins', 'public');
        
        // Enregistrer le nouveau chemin de la photo en base de données
        $user->update(['photo' => $path]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Photo de profil mise à jour avec succès.');
    }

    /**
     * Supprime la photo de profil de l'administrateur.
     * 
     * Supprime le fichier physique du disque public puis met à jour
     * le champ 'photo' à null en base de données.
     *
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     */
    public function deletePhoto()
    {
        $user = Auth::guard('web')->user();

        // Suppression du fichier physique sur le disque public
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Réinitialisation du champ photo à null en base de données
        $user->update(['photo' => null]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Photo de profil supprimée.');
    }

    /**
     * Met à jour le mot de passe de l'administrateur.
     * 
     * Processus sécurisé en 3 étapes :
     * 1. Validation du formulaire (mot de passe actuel requis, nouveau mot de passe min 8 caractères + confirmation)
     * 2. Vérification que le mot de passe actuel saisi correspond au hash en base
     * 3. Hashage et enregistrement du nouveau mot de passe
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant 'current_password', 'password' et 'password_confirmation'
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès ou erreur
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Validation : mot de passe actuel requis, nouveau mot de passe min 8 caractères avec confirmation
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        // Vérification que le mot de passe actuel saisi correspond au hash stocké en base
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Hashage et enregistrement du nouveau mot de passe
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Mot de passe modifié avec succès.');
    }
}
