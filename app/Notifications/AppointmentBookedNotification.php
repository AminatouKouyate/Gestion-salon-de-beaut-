<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification de confirmation de réservation de rendez-vous.
 *
 * Envoyée au client lorsqu'un rendez-vous est réservé avec succès.
 * Contient les détails du service, la date et l'heure du rendez-vous.
 * Diffusée par email et enregistrée en base de données.
 */
class AppointmentBookedNotification extends Notification
{
    use Queueable;

    /**
     * Crée une nouvelle instance de la notification.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous réservé
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
     * Envoie un email de confirmation avec le nom du service,
     * la date, l'heure et un lien vers la liste des rendez-vous.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre rendez-vous a été réservé avec succès.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Heure: ' . $this->appointment->time)
            ->action('Voir mes rendez-vous', url('/client/appointments'))
            ->line('Merci de votre confiance !');
    }

    /**
     * Construit la représentation en tableau pour le stockage en base de données.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return array<string, mixed>  Les données du rendez-vous à stocker
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_booked',
            'appointment_id' => $this->appointment->id,
            'message' => 'Votre rendez-vous pour ' . $this->appointment->service->name . ' le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . ' a été confirmé.',
        ];
    }
}
