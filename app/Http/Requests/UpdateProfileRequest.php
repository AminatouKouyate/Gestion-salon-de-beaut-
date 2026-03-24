<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Requête de validation pour la mise à jour du profil client.
 *
 * Définit les règles de validation lorsque le client modifie
 * ses propres informations de profil depuis son espace personnel.
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Seuls les clients authentifiés via le guard "clients" sont autorisés.
     *
     * @return bool  Vrai si le client est authentifié
     */
    public function authorize()
    {
        return Auth::guard('clients')->check();
    }

    /**
     * Retourne les règles de validation pour la mise à jour du profil.
     *
     * L'unicité de l'email exclut le client connecté pour éviter
     * les conflits avec son propre email existant.
     *
     * @return array<string, string>  Les règles de validation :
     *   - name : obligatoire, chaîne, max 255 caractères
     *   - email : obligatoire, format email, unique sauf pour le client connecté
     *   - phone : optionnel, max 20 caractères
     *   - address : optionnel, max 255 caractères
     *   - password : optionnel, min 8 caractères, doit être confirmé
     */
    public function rules()
    {
        // Récupérer l'identifiant du client connecté pour exclure son email de la vérification d'unicité
        $clientId = Auth::guard('clients')->id();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $clientId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
