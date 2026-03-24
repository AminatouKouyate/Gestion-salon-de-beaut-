<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un paiement effectué par un client.
 *
 * Gère les informations de paiement incluant le montant, la méthode de paiement
 * (espèces, carte, mobile money), le statut et la référence de transaction.
 * Chaque paiement est lié à un rendez-vous et à un client.
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',       // ID du client effectuant le paiement
        'appointment_id',  // ID du rendez-vous associé
        'amount',          // Montant en FCFA
        'method',          // Méthode de paiement (stripe, paypal, cash, orange_money, wave, salon)
        'status',          // Statut du paiement (pending, processing, paid, completed, failed, canceled)
        'transaction_id',  // ID de transaction externe (Stripe, Orange Money, Wave, etc.)
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relation : Le rendez-vous associé à ce paiement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Relation : Le client qui a effectué ce paiement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope : Filtre les paiements terminés (completed).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Accesseur : Récupère le libellé français du statut.
     *
     * @return string Libellé du statut en français
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'    => 'En attente',
            'processing' => 'Traitement en cours',
            'paid'       => 'Payé',
            'completed'  => 'Terminé',
            'failed'     => 'Échoué',
            'canceled'   => 'Annulé',
            default      => ucfirst(str_replace('-', ' ', $this->status)),
        };
    }

    /**
     * Accesseur : Récupère le libellé français de la méthode de paiement.
     *
     * @return string Libellé de la méthode en français
     */
    public function getMethodLabelAttribute()
    {
        return match ($this->method) {
            'cash'         => 'Espèces',
            'salon'        => 'Au salon',
            'stripe'       => 'Carte Bancaire',
            'paypal'       => 'PayPal',
            'orange_money' => 'Orange Money',
            'wave'         => 'Wave',
            default        => ucfirst($this->method ?? 'Non défini'),
        };
    }

    /**
     * Accesseur : Génère le badge HTML correspondant au statut.
     * Utilisé dans les vues Blade pour afficher le statut visuellement.
     *
     * @return string Code HTML du badge avec style Bootstrap 4
     */
    public function getStatusBadgeAttribute(): string
    {
        $info = match ($this->status) {
            'pending'    => ['class' => 'warning', 'text' => 'En attente'],
            'processing' => ['class' => 'info',    'text' => 'Traitement en cours'],
            'paid'       => ['class' => 'success', 'text' => 'Payé'],
            'completed'  => ['class' => 'success', 'text' => 'Terminé'],
            'failed'     => ['class' => 'danger',  'text' => 'Échoué'],
            'canceled'   => ['class' => 'secondary', 'text' => 'Annulé'],
            default      => ['class' => 'secondary', 'text' => ucfirst($this->status)],
        };

        return '<span class="badge badge-' . $info['class'] . '">' . $info['text'] . '</span>';
    }
}
