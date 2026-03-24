<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un rendez-vous dans le salon de beauté.
 *
 * Un rendez-vous lie un client à un service spécifique
 * et est assigné à un employé pour une date et heure précises.
 * Il gère également l'envoi de rappels et le suivi du statut.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique du rendez-vous
 * @property int $client_id Identifiant du client
 * @property int $service_id Identifiant du service demandé
 * @property int|null $employee_id Identifiant de l'employé assigné
 * @property \Carbon\Carbon $scheduled_at Date et heure du rendez-vous
 * @property AppointmentStatus $status Statut du rendez-vous (pending, confirmed, completed, canceled, no-show)
 * @property string|null $notes Notes internes sur le rendez-vous
 * @property bool $reminder_sent Indique si un rappel a été envoyé
 * @property \Carbon\Carbon|null $reminder_sent_at Date d'envoi du dernier rappel
 * @property \Carbon\Carbon $created_at Date de création
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 *
 * @property-read Client $client Le client associé
 * @property-read Service $service Le service demandé
 * @property-read Employee|null $employee L'employé assigné
 * @property-read Payment|null $payment Le paiement associé
 * @property-read \Carbon\Carbon|null $date La date du rendez-vous (sans heure)
 * @property-read string|null $time L'heure du rendez-vous au format HH:MM
 * @property-read string|null $formatted_date La date formatée (JJ/MM/AAAA)
 * @property-read string|null $formatted_time L'heure formatée (HH:MM)
 * @property-read string $status_label Le libellé du statut en français
 * @property-read string $status_badge Le badge HTML du statut
 */
class Appointment extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'service_id',
        'employee_id',
        'scheduled_at',
        'status',
        'notes',
        'reminder_sent',
        'reminder_sent_at',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'status' => AppointmentStatus::class,
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Accesseur : Récupère la date du rendez-vous (sans l'heure).
     *
     * @return \Carbon\Carbon|null
     */
    public function getDateAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->copy()->startOfDay() : null;
    }

    /**
     * Accesseur : Récupère l'heure du rendez-vous au format HH:MM.
     *
     * @return string|null
     */
    public function getTimeAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('H:i') : null;
    }

    /**
     * Accesseur : Récupère la date formatée (JJ/MM/AAAA).
     *
     * @return string|null
     */
    public function getFormattedDateAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('d/m/Y') : null;
    }

    /**
     * Accesseur : Récupère l'heure formatée (HH:MM).
     *
     * @return string|null
     */
    public function getFormattedTimeAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('H:i') : null;
    }

    /**
     * Relation : Le client qui a pris ce rendez-vous.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation : Le service demandé pour ce rendez-vous.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relation : L'employé assigné à ce rendez-vous.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relation : Le paiement associé à ce rendez-vous.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Scope : Filtre les rendez-vous en attente de confirmation.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope : Filtre les rendez-vous confirmés.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope : Filtre les rendez-vous à venir (date >= maintenant).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now());
    }

    /**
     * Accesseur : Récupère le libellé du statut en français.
     *
     * Convertit les valeurs de l'enum AppointmentStatus en texte lisible
     * pour l'affichage dans les vues.
     *
     * @return string Le libellé du statut traduit en français
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            AppointmentStatus::Pending   => 'En attente',
            AppointmentStatus::Confirmed => 'Confirmé',
            AppointmentStatus::Completed => 'Terminé',
            AppointmentStatus::Canceled  => 'Annulé',
            AppointmentStatus::NoShow    => 'Absent',
            default => ucfirst($this->status->value ?? $this->status),
        };
    }

    /**
     * Génère le badge HTML correspondant au statut du rendez-vous.
     *
     * @return string Le code HTML du badge avec la classe Bootstrap appropriée
     */
    public function getStatusBadgeAttribute(): string
    {
        $statusInfo = match ($this->status) {
            AppointmentStatus::Pending   => ['class' => 'warning', 'text' => 'En attente'],
            AppointmentStatus::Confirmed => ['class' => 'primary',    'text' => 'Confirmé'],
            AppointmentStatus::Completed => ['class' => 'success', 'text' => 'Terminé'],
            AppointmentStatus::Canceled  => ['class' => 'danger',  'text' => 'Annulé'],
            AppointmentStatus::NoShow    => ['class' => 'secondary',    'text' => 'Absent'],
        };

        return '<span class="badge badge-' . $statusInfo['class'] . '">' . $statusInfo['text'] . '</span>';
    }
}
