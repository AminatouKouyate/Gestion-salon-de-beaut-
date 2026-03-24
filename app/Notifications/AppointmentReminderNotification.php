<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification de rappel de rendez-vous.
 *
 * Envoyée automatiquement au client la veille de son rendez-vous
 * pour lui rappeler le service, la date et l'heure prévus.
 * Diffusée par email et enregistrée en base de données.
 */
class AppointmentReminderNotification extends Notification
{
    use Queueable;

    /**
     * Crée une nouvelle instance de la notification.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous à rappeler
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
     * Envoie un email de rappel au client avec les détails du rendez-vous
     * prévu pour le lendemain.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rappel de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Ceci est un rappel pour votre rendez-vous de demain.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Heure: ' . $this->appointment->time)
            ->action('Voir mes rendez-vous', url('/client/appointments'))
            ->line('À demain !');
    }

    /**
     * Construit la représentation en tableau pour le stockage en base de données.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return array<string, mixed>  Les données du rappel à stocker
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_reminder',
            'appointment_id' => $this->appointment->id,
            'message' => 'Rappel: Votre rendez-vous pour ' . $this->appointment->service->name . ' est prévu demain le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . '.',
        ];
    }
}
