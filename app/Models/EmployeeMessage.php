<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un message envoyé par un employé à l'administration.
 *
 * Permet aux employés de communiquer avec l'administration du salon
 * (questions, demandes, signalements) avec suivi des réponses.
 */
class EmployeeMessage extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'subject',
        'message',
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
        'responded_at' => 'datetime',
    ];

    /**
     * Relation : L'employé auteur de ce message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope : Filtre les messages en attente de réponse.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope : Filtre les messages ayant reçu une réponse.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    /**
     * Accesseur : Récupère le libellé traduit du statut du message.
     *
     * @return string Libellé en français du statut
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'answered' => 'Répondu',
            'closed' => 'Fermé',
            default => ucfirst($this->status),
        };
    }

    /**
     * Accesseur : Génère le badge HTML correspondant au statut du message.
     *
     * @return string Code HTML du badge avec style Bootstrap 4
     */
    public function getStatusBadgeAttribute()
    {
        $info = match ($this->status) {
            'pending' => ['class' => 'warning', 'text' => 'En attente'],
            'answered' => ['class' => 'success', 'text' => 'Répondu'],
            'closed' => ['class' => 'secondary', 'text' => 'Fermé'],
            default => ['class' => 'secondary', 'text' => ucfirst($this->status)],
        };

        return '<span class="badge badge-' . $info['class'] . '">' . $info['text'] . '</span>';
    }
}
