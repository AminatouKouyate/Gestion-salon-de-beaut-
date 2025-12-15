<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Constantes pour les rôles afin d'éviter les erreurs de frappe
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EMPLOYEE = 'employee';
    public const ROLE_CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role === $roleName;
    }

    /**
     * Vérifie si l'utilisateur a au moins un des rôles spécifiés.
     *
     * @param array<string> $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        if (empty($this->role)) {
            return false;
        }

        return in_array($this->role, $roles, true);
    }

    /**
     * Mutator pour s'assurer que le rôle est toujours stocké en minuscules.
     *
     * @param string $value
     * @return void
     */
    public function setRoleAttribute(string $value): void
    {
        $this->attributes['role'] = strtolower($value);
    }
}
