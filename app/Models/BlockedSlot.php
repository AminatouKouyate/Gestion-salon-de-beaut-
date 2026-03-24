<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Modèle représentant un créneau bloqué (indisponibilité ponctuelle).
 *
 * Permet de bloquer des créneaux horaires pour un employé spécifique
 * ou pour l'ensemble du salon (blocage global si employee_id est null).
 */
class BlockedSlot extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'start_datetime',
        'end_datetime',
        'reason',
        'created_by',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Relation : L'employé concerné par ce blocage (nullable pour blocage global).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relation : L'utilisateur ayant créé ce blocage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope : Filtre les blocages pour une date spécifique.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|string $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDate($query, $date)
    {
        $date = Carbon::parse($date);
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        return $query->where(function ($q) use ($startOfDay, $endOfDay) {
            $q->where('start_datetime', '<=', $endOfDay)
              ->where('end_datetime', '>=', $startOfDay);
        });
    }

    /**
     * Scope : Filtre les blocages dans une plage de dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return $query->where(function ($q) use ($start, $end) {
            $q->where('start_datetime', '<=', $end)
              ->where('end_datetime', '>=', $start);
        });
    }

    /**
     * Scope : Filtre les blocages globaux (sans employé spécifique).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('employee_id');
    }

    /**
     * Scope : Filtre les blocages pour un employé spécifique.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $employeeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope : Filtre les blocages actifs (en cours ou à venir).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('end_datetime', '>=', now());
    }

    /**
     * Accesseur : Vérifie si le blocage est global.
     *
     * @return bool
     */
    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->employee_id);
    }

    /**
     * Accesseur : Formate la durée du blocage.
     *
     * @return string
     */
    public function getDurationAttribute(): string
    {
        $diff = $this->start_datetime->diff($this->end_datetime);

        if ($diff->days > 0) {
            return $diff->days . ' jour(s)';
        }

        return $diff->h . 'h' . str_pad($diff->i, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Vérifie si un moment donné est bloqué par ce créneau.
     *
     * @param Carbon|string $datetime
     * @return bool
     */
    public function coversDatetime($datetime): bool
    {
        $dt = Carbon::parse($datetime);

        return $dt >= $this->start_datetime && $dt < $this->end_datetime;
    }
}
