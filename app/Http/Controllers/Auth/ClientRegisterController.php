<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Contrôleur d'inscription dédié aux clients.
 *
 * Gère l'affichage du formulaire d'inscription client, la validation
 * des données, la création du compte client en base de données et
 * la connexion automatique après inscription.
 */
class ClientRegisterController extends Controller
{
    /**
     * URL de redirection après inscription réussie du client.
     *
     * @var string
     */
    protected string $redirectTo = '/client/dashboard';

    /**
     * Affiche le formulaire d'inscription réservé aux clients.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.client-register');
    }

    /**
     * Traite la requête d'inscription d'un nouveau client.
     *
     * Valide les données du formulaire, crée le client en base de données
     * puis le connecte automatiquement via le guard clients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des données d'inscription
        $validator = $this->validator($request->all());

        // Si la validation échoue, retour au formulaire avec les erreurs
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Création du client en base de données
        $client = $this->create($request->all());

        // Connexion automatique du client après inscription
        $this->guard()->login($client);

        return redirect($this->redirectTo);
    }

    /**
     * Crée et retourne un validateur pour les données d'inscription.
     *
     * @param  array  $data  Les données du formulaire d'inscription
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Crée une nouvelle instance Client en base de données.
     *
     * Initialise le compte avec les points de fidélité à zéro,
     * le statut actif et aucun rendez-vous comptabilisé.
     *
     * @param  array  $data  Les données validées du formulaire
     * @return \App\Models\Client  L'instance du client créé
     */
    protected function create(array $data): Client
    {
        return Client::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'active' => true,
            'loyalty_points' => 0,
            'total_appointments' => 0,
        ]);
    }

    /**
     * Retourne le guard d'authentification utilisé pour les clients.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('clients');
    }
}
