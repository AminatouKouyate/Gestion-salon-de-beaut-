<?php

namespace App\Listeners;

use App\Events\PaymentSimulated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminPaymentNotification;
use App\Models\User;

/**
 * Listener d'envoi de notification de paiement aux administrateurs.
 *
 * Ce listener est exécuté de manière asynchrone (en file d'attente)
 * lorsqu'un événement PaymentSimulated est déclenché. Il récupère
 * tous les utilisateurs ayant le rôle administrateur et leur envoie
 * une notification les informant du nouveau paiement reçu.
 *
 * @package App\Listeners
 */
class SendAdminPaymentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Traite l'événement de paiement simulé.
     *
     * Récupère les administrateurs du système et leur envoie une
     * notification de paiement. En cas d'erreur, l'exception est
     * capturée et journalisée sans interrompre le processus.
     *
     * @param  \App\Events\PaymentSimulated  $event  L'événement contenant le paiement
     * @return void
     */
    public function handle(PaymentSimulated $event)
    {
        $payment = $event->payment;

        try {
            $admins = User::where('role', User::ROLE_ADMIN)->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AdminPaymentNotification($payment));
            }
        } catch (\Throwable $e) {
            \Illuminate\Log::error('Failed to send admin payment notification: ' . $e->getMessage());
        }
    }
}
