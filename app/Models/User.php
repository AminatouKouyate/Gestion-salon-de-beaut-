<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle représentant un utilisateur du système (administrateur).
 *
 * Ce modèle gère l'authentification principale du back-office.
 * Les rôles disponibles sont : admin, employee, client.
 * Il est principalement utilisé pour les administrateurs du salon.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique de l'utilisateur
 * @property string $name Nom complet de l'utilisateur
 * @property string $email Adresse email (unique, utilisée pour la connexion)
 * @property string $password Mot de passe hashé automatiquement via le cast 'hashed'
 * @property string|null $role Rôle de l'utilisateur (admin, employee, client)
 * @property string|null $photo URL de la photo de profil
 * @property \Carbon\Carbon|null $email_verified_at Date de vérification de l'email
 * @property string|null $remember_token Jeton de mémorisation de session
 * @property \Carbon\Carbon $created_at Date de création du compte
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Constantes pour les rôles afin d'éviter les erreurs de frappe
     * et centraliser les valeurs utilisées dans les middlewares et les vues.
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EMPLOYEE = 'employee';
    public const ROLE_CLIENT = 'client';

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',     // Nom complet de l'utilisateur
        'email',    // Adresse email unique pour la connexion
        'password', // Mot de passe (hashé automatiquement via le cast)
        'role',     // Rôle : admin, employee ou client
        'photo',    // URL de la photo de profil
    ];

    /**
     * Attributs masqués lors de la sérialisation (JSON/API).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',       // Mot de passe hashé (sécurité)
        'remember_token', // Jeton de session (sécurité)
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Conversion en objet Carbon pour la date de vérification
            'password' => 'hashed',            // Hashage automatique du mot de passe à l'écriture
        ];
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique.
     *
     * @param string $roleName Le nom du rôle à vérifier (ex: 'admin')
     * @return bool True si l'utilisateur a ce rôle
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role === $roleName;
    }

    /**
     * Vérifie si l'utilisateur a au moins un des rôles spécifiés.
     *
     * Utile pour les vérifications d'autorisation combinées
     * (ex: accès admin OU employee).
     *
     * @param array<string> $roles Liste des rôles acceptés
     * @return bool True si l'utilisateur possède au moins un des rôles
     */
    public function hasAnyRole(array $roles): bool
    {
        if (empty($this->role)) {
            return false;
        }

        return in_array($this->role, $roles, true);
    }

    /**
     * Mutateur : Normalise le rôle en minuscules avant l'enregistrement.
     *
     * Garantit la cohérence des données en forçant le stockage en minuscules,
     * quel que soit le format d'entrée.
     *
     * @param string $value Le rôle à stocker
     * @return void
     */
    public function setRoleAttribute(string $value): void
    {
        $this->attributes['role'] = strtolower($value);
    }
}
