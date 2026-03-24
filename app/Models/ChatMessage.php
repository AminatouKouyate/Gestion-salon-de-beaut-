<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un message de chat entre un client et le système.
 *
 * Stocke les messages envoyés par les clients via le chatbot,
 * ainsi que les réponses automatiques générées et l'intention détectée.
 */
class ChatMessage extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'message',
        'response',
        'intent',
        'is_user_message',
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_user_message' => 'boolean',
    ];

    /**
     * Relation : Le client auteur de ce message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope : Filtre les messages pour un client spécifique.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $clientId Identifiant du client
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope : Récupère les messages récents, triés par date décroissante.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit Nombre maximum de messages à retourner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, $limit = 20)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
