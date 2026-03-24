<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant une demande de congé d'un employé.
 *
 * Gère les demandes de congé soumises par les employés,
 * leur approbation ou rejet par l'administration, et le suivi des réponses.
 */
class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'admin_response',
        'responded_at',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'responded_at' => 'datetime',
    ];

    /**
     * Relation : L'employé ayant soumis cette demande de congé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope : Filtre les demandes en attente de décision.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope : Filtre les demandes approuvées.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope : Filtre les demandes rejetées.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Accesseur : Calcule le nombre de jours de congé demandés.
     *
     * @return int Nombre de jours (inclusif)
     */
    public function getDaysCountAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Accesseur : Récupère le libellé traduit du statut.
     *
     * @return string Libellé en français du statut
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'  => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            default    => ucfirst($this->status),
        };
    }

    /**
     * Accesseur : Génère le badge HTML correspondant au statut de la demande.
     *
     * @return string Code HTML du badge avec style Bootstrap 4
     */
    public function getStatusBadgeAttribute()
    {
        $info = match ($this->status) {
            'pending'  => ['class' => 'warning', 'text' => 'En attente'],
            'approved' => ['class' => 'success', 'text' => 'Approuvée'],
            'rejected' => ['class' => 'danger', 'text' => 'Rejetée'],
            default    => ['class' => 'secondary', 'text' => ucfirst($this->status)],
        };

        return '<span class="badge badge-' . $info['class'] . '">' . $info['text'] . '</span>';
    }
}
