<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de validation pour la création d'un nouveau client.
 *
 * Définit les règles de validation appliquées lors de l'enregistrement
 * d'un nouveau client dans le système (nom, email, mot de passe, etc.).
 */
class StoreClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Seuls les utilisateurs authentifiés (administrateurs) peuvent créer un client.
     *
     * @return bool  Vrai si l'utilisateur est authentifié
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Retourne les règles de validation pour la création d'un client.
     *
     * @return array<string, string>  Les règles de validation :
     *   - name : obligatoire, chaîne, max 255 caractères
     *   - email : obligatoire, format email, unique dans la table clients
     *   - password : obligatoire, min 8 caractères, doit être confirmé
     *   - phone : optionnel, max 20 caractères
     *   - address : optionnel, max 255 caractères
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ];
    }
}
