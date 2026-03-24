<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur pour la gestion du profil et du tableau de bord côté client.
 * 
 * Ce contrôleur gère les fonctionnalités liées au compte client :
 * - Affichage du tableau de bord personnalisé
 * - Consultation et modification du profil
 * - Gestion de la photo de profil (upload, suppression)
 * - Désactivation du compte
 * 
 * @package App\Http\Controllers\Client
 */
class ProfileController extends Controller
{
    /**
     * Affiche le tableau de bord du client connecté.
     *
     * Le tableau de bord présente une vue d'ensemble de l'activité du client :
     * - Ses prochains rendez-vous à venir
     * - Ses notifications non lues (5 dernières)
     * - Ses paiements récents (5 derniers)
     *
     * @return \Illuminate\View\View La vue du tableau de bord avec les données agrégées
     */
    public function dashboard()
    {
        // Récupérer le client connecté via le guard 'clients'
        $client = Auth::guard('clients')->user();
        
        // Récupérer les rendez-vous à venir du client
        // La méthode getUpcomingAppointments() est définie dans le modèle Client
        $upcomingAppointments = $client->getUpcomingAppointments();
        
        // Récupérer les 5 dernières notifications non lues
        // Utilise le système de notifications Laravel
        $unreadNotifications = $client->unreadNotifications()->take(5)->get();
        
        // Récupérer les 5 derniers paiements avec le service associé
        $recentPayments = $client->payments()
            ->with('appointment.service') // Eager loading pour les performances
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalPaid = $client->payments()->where('status', 'paid')->sum('amount');
        $pendingPayments = $client->payments()->where('status', 'pending')->count();
        $totalAppointments = $client->appointments()->count();
        $completedAppointments = $client->appointments()->where('status', 'completed')->count();

        return view('clients.dashboard', compact(
            'client',
            'upcomingAppointments',
            'unreadNotifications',
            'recentPayments',
            'totalPaid',
            'pendingPayments',
            'totalAppointments',
            'completedAppointments'
        ));
    }

    /**
     * Affiche le formulaire de profil du client.
     *
     * Permet au client de visualiser ses informations personnelles
     * et d'accéder aux options de modification.
     *
     * @return \Illuminate\View\View La vue du profil avec les données du client
     */
    public function profile()
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        return view('clients.profile', compact('client'));
    }

    /**
     * Met à jour les informations du profil client.
     *
     * Cette méthode permet de modifier les informations personnelles :
     * - Nom complet
     * - Adresse email (avec vérification d'unicité)
     * - Numéro de téléphone
     * - Adresse postale
     * - Allergies connues (important pour les soins de beauté)
     * - Mot de passe (optionnel, avec confirmation)
     *
     * @param Request $request Les données du formulaire de profil
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     * @throws \Illuminate\Validation\ValidationException Si les données sont invalides
     */
    public function updateProfile(Request $request)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Validation des données du formulaire
        // Note : l'email doit être unique, mais on exclut l'email actuel du client
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'allergies' => 'nullable|string|max:1000',
            'password' => 'nullable|min:8|confirmed', // Doit correspondre à password_confirmation
        ]);

        // Préparer les données à mettre à jour (exclure le mot de passe pour l'instant)
        $data = $request->except('password');

        // Si un nouveau mot de passe est fourni, le hasher et l'ajouter aux données
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Mettre à jour le profil du client
        $client->update($data);

        return back()->with('success', 'Profil mis à jour');
    }

    /**
     * Désactive le compte du client.
     *
     * Cette action est irréversible (le compte reste en base mais marqué inactif).
     * Le client devra contacter le support pour réactiver son compte.
     * 
     * Processus de sécurité :
     * 1. Vérification du mot de passe actuel pour confirmer l'identité
     * 2. Marquage du compte comme inactif
     * 3. Déconnexion et invalidation de la session
     *
     * @param Request $request Requête contenant le mot de passe de confirmation
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page de connexion
     * @throws \Illuminate\Validation\ValidationException Si le mot de passe est incorrect
     */
    public function deactivate(Request $request)
    {
        // Valider que le mot de passe fourni est correct
        // La règle 'current_password' vérifie contre le guard 'clients'
        $request->validate([
            'password' => 'required|current_password:clients',
        ]);

        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Désactiver le compte (le champ 'active' passe à false)
        $client->update(['active' => false]);

        // Déconnecter le client
        Auth::guard('clients')->logout();
        
        // Invalider la session pour des raisons de sécurité
        $request->session()->invalidate();
        
        // Régénérer le token CSRF pour prévenir les attaques
        $request->session()->regenerateToken();

        return redirect()->route('client.login')
            ->with('success', 'Votre compte a été désactivé avec succès.');
    }

    /**
     * Met à jour la photo de profil du client.
     *
     * Cette méthode gère l'upload d'une nouvelle photo :
     * 1. Valide le fichier (image, formats autorisés, taille max 2MB)
     * 2. Supprime l'ancienne photo si elle existe
     * 3. Stocke la nouvelle photo dans le disque 'public'
     * 4. Met à jour le chemin dans la base de données
     *
     * @param Request $request Requête contenant le fichier photo
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     * @throws \Illuminate\Validation\ValidationException Si le fichier est invalide
     */
    public function updatePhoto(Request $request)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Valider le fichier uploadé
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);
        
        // Supprimer l'ancienne photo si elle existe
        if ($client->photo && Storage::disk('public')->exists($client->photo)) {
            Storage::disk('public')->delete($client->photo);
        }
        
        // Stocker la nouvelle photo dans le dossier 'photos/clients'
        // Le fichier sera accessible via storage/app/public/photos/clients/
        $path = $request->file('photo')->store('photos/clients', 'public');
        
        // Mettre à jour le chemin de la photo dans la base de données
        $client->update(['photo' => $path]);
        
        return redirect()->route('client.profile')->with('success', 'Photo mise à jour.');
    }

    /**
     * Supprime la photo de profil du client.
     *
     * Cette méthode supprime la photo actuelle du client :
     * 1. Supprime le fichier du stockage
     * 2. Met à jour la base de données (photo = null)
     *
     * @return \Illuminate\Http\RedirectResponse Redirection vers le profil avec message de succès
     */
    public function deletePhoto()
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Supprimer le fichier physique si la photo existe
        if ($client->photo && Storage::disk('public')->exists($client->photo)) {
            Storage::disk('public')->delete($client->photo);
        }
        
        // Mettre à jour la base de données (supprimer la référence)
        $client->update(['photo' => null]);
        
        return redirect()->route('client.profile')->with('success', 'Photo supprimée.');
    }
}
