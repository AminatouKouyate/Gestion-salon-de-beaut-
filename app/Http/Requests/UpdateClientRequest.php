<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de validation pour la mise à jour d'un client existant.
 *
 * Définit les règles de validation appliquées lors de la modification
 * des informations d'un client. L'unicité de l'email exclut le client
 * en cours de modification pour éviter les faux conflits.
 */
class UpdateClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Seuls les utilisateurs authentifiés (administrateurs) peuvent modifier un client.
     *
     * @return bool  Vrai si l'utilisateur est authentifié
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Retourne les règles de validation pour la mise à jour d'un client.
     *
     * L'unicité de l'email ignore le client en cours de modification
     * afin de permettre la sauvegarde sans changer l'email.
     *
     * @return array<string, mixed>  Les règles de validation :
     *   - name : obligatoire, chaîne, max 255 caractères
     *   - email : obligatoire, format email, unique sauf pour le client courant
     *   - password : optionnel, min 8 caractères, doit être confirmé
     *   - phone : optionnel, format numéro de téléphone international (7 à 15 chiffres)
     *   - address : optionnel, max 255 caractères
     */
    public function rules()
    {
        // Récupérer le client depuis le paramètre de route
        $client = $this->route('client');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . ($client->id ?? 'NULL'),
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => ['nullable', 'regex:/^\+?[0-9]{7,15}$/'],
            'address' => 'nullable|string|max:255',
        ];
    }
}
