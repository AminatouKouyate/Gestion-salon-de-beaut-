<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle représentant un client du salon de beauté.
 *
 * Gère les informations personnelles du client, son programme de fidélité,
 * ses rendez-vous et son historique de paiements.
 * Étend Authenticatable pour permettre l'authentification des clients.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique du client
 * @property string $name Nom complet du client
 * @property string $email Adresse email (unique, utilisée pour la connexion)
 * @property string $password Mot de passe hashé
 * @property string|null $phone Numéro de téléphone
 * @property string|null $address Adresse postale
 * @property string|null $allergies Allergies connues (informations médicales importantes)
 * @property int $loyalty_points Points de fidélité accumulés
 * @property int $total_appointments Nombre total de rendez-vous effectués
 * @property bool $active Indique si le compte client est actif
 * @property string|null $photo URL de la photo de profil
 * @property \Carbon\Carbon $created_at Date de création du compte
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Appointment[] $appointments Tous les rendez-vous du client
 * @property-read \Illuminate\Database\Eloquent\Collection|Payment[] $payments Historique des paiements
 * @property-read \Illuminate\Database\Eloquent\Collection|ClientNotification[] $notifications Notifications du client
 */
class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'allergies',
        'loyalty_points',
        'total_appointments',
        'active',
        'photo',
    ];

    /**
     * Attributs masqués lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * Relation : Tous les rendez-vous pris par ce client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relation : Tous les paiements effectués par ce client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation : Toutes les notifications destinées à ce client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(ClientNotification::class);
    }

    /**
     * Récupère les notifications non lues du client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Ajoute des points de fidélité au compte du client.
     *
     * @param int $points Nombre de points à ajouter
     * @return void
     */
    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    /**
     * Utilise des points de fidélité du compte du client.
     *
     * @param int $points Nombre de points à utiliser
     * @return bool True si les points ont été déduits, false si solde insuffisant
     */
    public function useLoyaltyPoints(int $points): bool
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }

    /**
     * Détermine le niveau de fidélité du client selon ses points.
     *
     * Niveaux : Bronze (0-99), Argent (100-199), Or (200-499), Platine (500+)
     *
     * @return string Le niveau de fidélité du client
     */
    public function getLoyaltyLevel(): string
    {
        $points = $this->loyalty_points ?? 0;

        return match(true) {
            $points >= 500 => 'Platine',
            $points >= 200 => 'Or',
            $points >= 100 => 'Argent',
            default => 'Bronze',
        };
    }

    /**
     * Calcule le pourcentage de réduction selon le niveau de fidélité.
     *
     * @return int Pourcentage de réduction (0, 10, 15 ou 20)
     */
    public function getLoyaltyDiscount(): int
    {
        return match($this->getLoyaltyLevel()) {
            'Platine' => 20,
            'Or' => 15,
            'Argent' => 10,
            default => 0,
        };
    }

    /**
     * Récupère les rendez-vous à venir du client.
     *
     * Inclut les rendez-vous en attente et confirmés, triés par date.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingAppointments()
    {
        return $this->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->with(['service', 'employee'])
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Récupère l'historique des rendez-vous terminés du client.
     *
     * Triés par date décroissante (plus récents en premier).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedAppointments()
    {
        return $this->appointments()
            ->where('status', 'completed')
            ->with(['service', 'employee', 'payment'])
            ->orderBy('scheduled_at', 'desc')
            ->get();
    }
}
