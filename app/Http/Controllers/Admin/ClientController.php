<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur de gestion des clients pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'ensemble des opérations CRUD (Create, Read, Update, Delete)
 * pour les clients du salon de beauté. Il permet également de gérer l'activation
 * et la désactivation des comptes clients.
 * 
 * @package App\Http\Controllers\Admin
 */
class ClientController extends Controller
{
    /**
     * Affiche la liste paginée de tous les clients.
     * 
     * Récupère tous les clients et les affiche dans un tableau paginé
     * à raison de 10 clients par page pour une navigation optimale.
     *
     * @return \Illuminate\View\View Vue de la liste des clients
     */
    public function index()
    {
        // Récupération paginée des clients (10 par page)
        $clients = Client::paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau client.
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Enregistre un nouveau client dans la base de données.
     * 
     * Valide les données du formulaire puis crée le client.
     * Le mot de passe est automatiquement hashé via Hash::make()
     * pour garantir la sécurité des données.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Validation des données avec contrainte d'unicité sur l'email
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Création du client avec hashage sécurisé du mot de passe
            Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.clients.index')->with('success', 'Client ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'un client avec ses rendez-vous et paiements récents.
     *
     * @param  \App\Models\Client  $client Instance du client à afficher
     * @return \Illuminate\View\View Vue de détail du client
     */
    public function show(Client $client)
    {
        $client->load([
            'appointments' => fn($q) => $q->with(['service', 'employee'])->latest('scheduled_at')->limit(10),
            'payments' => fn($q) => $q->with('appointment.service')->latest()->limit(10),
        ]);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Affiche le formulaire de modification d'un client existant.
     * 
     * Utilise le Route Model Binding de Laravel pour récupérer
     * automatiquement le client correspondant à l'ID.
     *
     * @param  \App\Models\Client  $client Instance du client à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Met à jour un client existant dans la base de données.
     * 
     * Valide les données puis met à jour uniquement les champs
     * autorisés (nom, email, téléphone). Le mot de passe n'est
     * pas modifiable via cette méthode.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données
     * @param  \App\Models\Client  $client Instance du client à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Client $client)
    {
        // Validation avec exclusion de l'email du client actuel pour l'unicité
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Mise à jour limitée aux champs autorisés uniquement
        try {
            $client->update($request->only('name', 'email', 'phone'));
            return redirect()->route('admin.clients.index')->with('success', 'Client mis à jour.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un client de la base de données.
     * 
     * Effectue une suppression définitive du client.
     * Les rendez-vous et paiements associés sont gérés
     * par les contraintes de clé étrangère en base de données.
     *
     * @param  \App\Models\Client  $client Instance du client à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('admin.clients.index')->with('success', 'Client supprimé.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce client : ' . $e->getMessage());
        }
    }

    /**
     * Réactive le compte d'un client précédemment désactivé.
     * 
     * Passe le champ 'active' à true, ce qui permet au client
     * de se reconnecter et d'être sélectionnable pour les rendez-vous.
     *
     * @param  \App\Models\Client  $client Instance du client à réactiver
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function reactivate(Client $client)
    {
        try {
            $client->update(['active' => true]);
            return redirect()->route('admin.clients.index')->with('success', 'Client réactivé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Désactive le compte d'un client.
     * 
     * Passe le champ 'active' à false. Un client désactivé :
     * - Ne peut plus se connecter à son espace
     * - N'apparaît plus dans les sélections de rendez-vous
     * - Conserve son historique de rendez-vous et paiements
     *
     * @param  \App\Models\Client  $client Instance du client à désactiver
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function deactivate(Client $client)
    {
        try {
            $client->update(['active' => false]);
            return redirect()->route('admin.clients.index')->with('success', 'Client désactivé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
