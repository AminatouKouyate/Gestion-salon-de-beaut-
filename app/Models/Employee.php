<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

/**
 * Modèle représentant un employé du salon de beauté.
 *
 * Gère les informations personnelles de l'employé, ses horaires de travail,
 * ses spécialités, ses rendez-vous assignés et ses demandes de congé.
 * Étend Authenticatable pour permettre l'authentification des employés.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique de l'employé
 * @property string $name Nom complet de l'employé
 * @property string $email Adresse email (unique, utilisée pour la connexion)
 * @property string $password Mot de passe hashé
 * @property string|null $phone Numéro de téléphone
 * @property string $role Rôle de l'employé (admin, manager, stylist, etc.)
 * @property bool $is_active Indique si le compte est actif
 * @property string|null $specialties Spécialités de l'employé (texte libre)
 * @property string|null $work_start_time Heure de début de travail par défaut
 * @property string|null $work_end_time Heure de fin de travail par défaut
 * @property array|null $work_days Jours de travail (tableau d'entiers 0-6)
 * @property string|null $photo URL de la photo de profil
 * @property \Carbon\Carbon $created_at Date de création du compte
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Appointment[] $appointments Rendez-vous assignés
 * @property-read \Illuminate\Database\Eloquent\Collection|LeaveRequest[] $leaveRequests Demandes de congé
 * @property-read \Illuminate\Database\Eloquent\Collection|EmployeeNotification[] $notifications Notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|Service[] $services Services que l'employé peut réaliser
 * @property-read \Illuminate\Database\Eloquent\Collection|EmployeeSchedule[] $schedules Horaires hebdomadaires
 * @property-read \Illuminate\Database\Eloquent\Collection|BlockedSlot[] $blockedSlots Créneaux bloqués
 */
class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'role',
        'is_active',
        'specialties',
        'work_start_time',
        'work_end_time',
        'work_days',
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
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'work_days' => 'array',
    ];

    /**
     * Relation : Tous les rendez-vous assignés à cet employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relation : Toutes les demandes de congé de cet employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Relation : Toutes les notifications destinées à cet employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(EmployeeNotification::class);
    }

    /**
     * Récupère les notifications non lues de l'employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Compte le nombre de notifications non lues de l'employé.
     *
     * @return int
     */
    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Récupère les rendez-vous à venir de l'employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at');
    }

    /**
     * Récupère les rendez-vous du jour pour l'employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todayAppointments()
    {
        return $this->appointments()
            ->whereDate('scheduled_at', now()->toDateString())
            ->orderBy('scheduled_at');
    }

    /**
     * Relation : Les services que cet employé peut réaliser.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * Relation : Les horaires hebdomadaires de cet employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    /**
     * Relation : Les créneaux bloqués de cet employé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class);
    }

    /**
     * Vérifie si l'employé est disponible à un moment donné.
     *
     * Prend en compte:
     * - Les horaires de travail hebdomadaires
     * - Les pauses
     * - Les congés approuvés
     * - Les créneaux bloqués
     *
     * @param Carbon|string $datetime Le moment à vérifier
     * @return bool
     */
    public function isAvailableAt($datetime): bool
    {
        $dt = Carbon::parse($datetime);
        $dayOfWeek = (int) $dt->dayOfWeek;
        $time = $dt->format('H:i:s');

        // Vérifier l'horaire de travail pour ce jour
        $schedule = $this->schedules()->forDay($dayOfWeek)->first();

        if (!$schedule || !$schedule->is_working) {
            return false;
        }

        // Vérifier si dans les heures de travail
        if (!$schedule->isWithinWorkingHours($time)) {
            return false;
        }

        // Vérifier si pendant une pause
        if ($schedule->isDuringBreak($time)) {
            return false;
        }

        // Vérifier les congés approuvés
        $hasApprovedLeave = $this->leaveRequests()
            ->approved()
            ->where('start_date', '<=', $dt->toDateString())
            ->where('end_date', '>=', $dt->toDateString())
            ->exists();

        if ($hasApprovedLeave) {
            return false;
        }

        // Vérifier les créneaux bloqués (spécifiques à l'employé ou globaux)
        $isBlocked = BlockedSlot::where(function ($query) {
                $query->where('employee_id', $this->id)
                      ->orWhereNull('employee_id');
            })
            ->where('start_datetime', '<=', $dt)
            ->where('end_datetime', '>', $dt)
            ->exists();

        if ($isBlocked) {
            return false;
        }

        return true;
    }

    /**
     * Récupère les créneaux disponibles pour une date et durée de service données.
     *
     * @param Carbon|string $date La date pour laquelle chercher les créneaux
     * @param int $serviceDuration Durée du service en minutes
     * @param int $slotInterval Intervalle entre les créneaux en minutes (défaut: 30)
     * @return array Liste des créneaux disponibles au format ['start' => Carbon, 'end' => Carbon]
     */
    public function getAvailableSlotsForDate($date, int $serviceDuration, int $slotInterval = 30): array
    {
        $date = Carbon::parse($date);
        $dayOfWeek = (int) $date->dayOfWeek;

        // Récupérer l'horaire du jour
        $schedule = $this->schedules()->forDay($dayOfWeek)->first();

        if (!$schedule || !$schedule->is_working) {
            return [];
        }

        $slots = [];
        $startOfDay = $date->copy()->setTimeFromTimeString($schedule->start_time);
        $endOfDay = $date->copy()->setTimeFromTimeString($schedule->end_time);

        // Récupérer les rendez-vous existants pour ce jour
        $existingAppointments = $this->appointments()
            ->whereDate('scheduled_at', $date->toDateString())
            ->whereNotIn('status', ['canceled'])
            ->get();

        // Récupérer les créneaux bloqués pour ce jour
        $blockedSlots = BlockedSlot::where(function ($query) {
                $query->where('employee_id', $this->id)
                      ->orWhereNull('employee_id');
            })
            ->forDate($date)
            ->get();

        // Vérifier si en congé approuvé
        $onLeave = $this->leaveRequests()
            ->approved()
            ->where('start_date', '<=', $date->toDateString())
            ->where('end_date', '>=', $date->toDateString())
            ->exists();

        if ($onLeave) {
            return [];
        }

        $currentSlot = $startOfDay->copy();

        while ($currentSlot->copy()->addMinutes($serviceDuration) <= $endOfDay) {
            $slotEnd = $currentSlot->copy()->addMinutes($serviceDuration);
            $isAvailable = true;

            // Vérifier la pause
            if ($schedule->break_start && $schedule->break_end) {
                $breakStart = $date->copy()->setTimeFromTimeString($schedule->break_start);
                $breakEnd = $date->copy()->setTimeFromTimeString($schedule->break_end);

                if ($currentSlot < $breakEnd && $slotEnd > $breakStart) {
                    $isAvailable = false;
                }
            }

            // Vérifier les rendez-vous existants
            foreach ($existingAppointments as $appointment) {
                $apptStart = Carbon::parse($appointment->scheduled_at);
                $apptEnd = $apptStart->copy()->addMinutes($appointment->service->duration ?? 30);

                if ($currentSlot < $apptEnd && $slotEnd > $apptStart) {
                    $isAvailable = false;
                    break;
                }
            }

            // Vérifier les créneaux bloqués
            foreach ($blockedSlots as $blocked) {
                if ($currentSlot < $blocked->end_datetime && $slotEnd > $blocked->start_datetime) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    'start' => $currentSlot->copy(),
                    'end' => $slotEnd->copy(),
                    'formatted' => $currentSlot->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                ];
            }

            $currentSlot->addMinutes($slotInterval);
        }

        return $slots;
    }
}
