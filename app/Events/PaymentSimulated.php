<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement déclenché lors de la simulation d'un paiement.
 *
 * Cet événement est émis lorsqu'un paiement est simulé avec succès
 * dans le système. Il permet aux listeners associés (comme l'envoi
 * de notifications aux administrateurs) de réagir au paiement.
 *
 * @package App\Events
 */
class PaymentSimulated
{
    use Dispatchable, SerializesModels;

    /**
     * L'instance du paiement simulé.
     *
     * @var \App\Models\Payment
     */
    public Payment $payment;

    /**
     * Crée une nouvelle instance de l'événement.
     *
     * @param  \App\Models\Payment  $payment  Le paiement qui a été simulé
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
