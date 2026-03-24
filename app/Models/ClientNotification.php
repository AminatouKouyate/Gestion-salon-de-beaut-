<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant une notification destinée à un client.
 *
 * Gère les notifications envoyées aux clients (rappels de rendez-vous,
 * promotions, confirmations, etc.) avec suivi de lecture.
 */
class ClientNotification extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relation : Le client destinataire de cette notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope : Filtre les notifications non lues.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Marque la notification comme lue avec horodatage.
     *
     * @return void
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }
}
