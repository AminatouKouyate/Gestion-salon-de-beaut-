<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification d'annulation de rendez-vous.
 *
 * Envoyée au client lorsqu'un rendez-vous est annulé.
 * Contient les détails du rendez-vous annulé et propose
 * un lien pour effectuer une nouvelle réservation.
 * Diffusée par email et enregistrée en base de données.
 */
class AppointmentCancelledNotification extends Notification
{
    use Queueable;

    /**
     * Crée une nouvelle instance de la notification.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous annulé
     */
    public function __construct(public Appointment $appointment) {}

    /**
     * Détermine les canaux de diffusion de la notification.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return array<string>  Les canaux utilisés : email et base de données
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Construit la représentation email de la notification.
     *
     * Informe le client de l'annulation du rendez-vous avec les détails
     * du service, la date et l'heure, et un lien pour réserver à nouveau.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Annulation de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre rendez-vous a été annulé.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Heure: ' . $this->appointment->time)
            ->action('Réserver un nouveau rendez-vous', url('/client/appointments/create'))
            ->line('Nous espérons vous revoir bientôt !');
    }

    /**
     * Construit la représentation en tableau pour le stockage en base de données.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return array<string, mixed>  Les données de l'annulation à stocker
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_cancelled',
            'appointment_id' => $this->appointment->id,
            'message' => 'Votre rendez-vous pour ' . $this->appointment->service->name . ' le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . ' a été annulé.',
        ];
    }
}
