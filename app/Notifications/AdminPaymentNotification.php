<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

/**
 * Notification de paiement destinée à l'administrateur.
 *
 * Envoie une notification à l'administrateur lorsqu'un paiement simulé
 * est enregistré par un client. La notification est envoyée par email,
 * enregistrée en base de données et diffusée en temps réel (broadcast).
 */
class AdminPaymentNotification extends Notification
{
    use Queueable;

    /** @var \App\Models\Payment Le paiement concerné par la notification */
    protected Payment $payment;

    /**
     * Crée une nouvelle instance de la notification.
     *
     * @param  \App\Models\Payment  $payment  Le paiement simulé à notifier
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Détermine les canaux de diffusion de la notification.
     *
     * @param  mixed  $notifiable  L'entité à notifier (administrateur)
     * @return array<string>  Les canaux utilisés : email et base de données
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Construit la représentation email de la notification.
     *
     * Utilise une vue Blade dédiée pour le contenu de l'email
     * avec les détails du paiement et un lien vers la page de gestion.
     *
     * @param  mixed  $notifiable  L'entité à notifier (administrateur)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Générer l'URL vers la page de détail du paiement côté admin
        $url = url('/admin/payments/' . $this->payment->id);

        // Utiliser une vue Blade dédiée pour le corps de l'email
        return (new MailMessage)
                    ->subject('Nouveau paiement simulé #' . $this->payment->id)
                    ->view('emails.admin_payment_simulated', ['payment' => $this->payment, 'url' => $url]);
    }

    /**
     * Construit la représentation en base de données de la notification.
     *
     * @param  mixed  $notifiable  L'entité à notifier (administrateur)
     * @return array<string, mixed>  Les données à stocker en base
     */
    public function toDatabase($notifiable)
    {
        return [
            'payment_id' => $this->payment->id,
            'client_id' => $this->payment->client_id,
            'amount' => $this->payment->amount,
            'appointment_id' => $this->payment->appointment_id,
            'message' => 'Paiement simulé enregistré',
        ];
    }

    /**
     * Construit la représentation broadcast (temps réel) de la notification.
     *
     * @param  mixed  $notifiable  L'entité à notifier (administrateur)
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([ 'payment_id' => $this->payment->id ]);
    }
}
