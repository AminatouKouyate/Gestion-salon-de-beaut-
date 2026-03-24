<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant l'horaire de travail d'un employé pour un jour de la semaine.
 *
 * Gère les horaires hebdomadaires récurrents des employés, incluant les heures
 * de début et fin de travail, ainsi que les pauses.
 */
class EmployeeSchedule extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_working',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_week' => 'integer',
        'is_working' => 'boolean',
    ];

    /**
     * Correspondance des numéros de jours avec leurs noms en français.
     *
     * @var array<int, string>
     */
    public const DAYS_FR = [
        0 => 'Dimanche',
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
    ];

    /**
     * Correspondance des numéros de jours avec leurs abréviations.
     *
     * @var array<int, string>
     */
    public const DAYS_SHORT_FR = [
        0 => 'Dim',
        1 => 'Lun',
        2 => 'Mar',
        3 => 'Mer',
        4 => 'Jeu',
        5 => 'Ven',
        6 => 'Sam',
    ];

    /**
     * Relation : L'employé auquel appartient cet horaire.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Accesseur : Récupère le nom du jour en français.
     *
     * @return string
     */
    public function getDayNameAttribute(): string
    {
        return self::DAYS_FR[$this->day_of_week] ?? '';
    }

    /**
     * Accesseur : Récupère l'abréviation du jour en français.
     *
     * @return string
     */
    public function getDayShortNameAttribute(): string
    {
        return self::DAYS_SHORT_FR[$this->day_of_week] ?? '';
    }

    /**
     * Accesseur : Formate la plage horaire de travail.
     *
     * @return string
     */
    public function getWorkingHoursAttribute(): string
    {
        if (!$this->is_working) {
            return 'Repos';
        }

        $start = substr($this->start_time, 0, 5);
        $end = substr($this->end_time, 0, 5);

        return "{$start} - {$end}";
    }

    /**
     * Accesseur : Formate la plage horaire de pause.
     *
     * @return string|null
     */
    public function getBreakHoursAttribute(): ?string
    {
        if (!$this->break_start || !$this->break_end) {
            return null;
        }

        $start = substr($this->break_start, 0, 5);
        $end = substr($this->break_end, 0, 5);

        return "{$start} - {$end}";
    }

    /**
     * Scope : Filtre les horaires pour un jour spécifique de la semaine.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $dayOfWeek Numéro du jour (0=dimanche)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope : Filtre les horaires des jours travaillés.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWorking($query)
    {
        return $query->where('is_working', true);
    }

    /**
     * Vérifie si une heure donnée est pendant la pause.
     *
     * @param string $time Format H:i ou H:i:s
     * @return bool
     */
    public function isDuringBreak(string $time): bool
    {
        if (!$this->break_start || !$this->break_end) {
            return false;
        }

        return $time >= $this->break_start && $time < $this->break_end;
    }

    /**
     * Vérifie si une heure donnée est dans les horaires de travail.
     *
     * @param string $time Format H:i ou H:i:s
     * @return bool
     */
    public function isWithinWorkingHours(string $time): bool
    {
        if (!$this->is_working) {
            return false;
        }

        return $time >= $this->start_time && $time < $this->end_time;
    }
}
