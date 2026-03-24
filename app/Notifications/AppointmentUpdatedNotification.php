<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification de modification de rendez-vous.
 *
 * Envoyée au client lorsqu'un rendez-vous existant est modifié
 * (changement de date, d'heure ou de service). Contient les
 * nouvelles informations du rendez-vous.
 * Diffusée par email et enregistrée en base de données.
 */
class AppointmentUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Crée une nouvelle instance de la notification.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous modifié
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
     * Informe le client des nouvelles date et heure du rendez-vous
     * modifié, avec un lien pour consulter ses rendez-vous.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Modification de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre rendez-vous a été modifié.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Nouvelle date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Nouvelle heure: ' . $this->appointment->time)
            ->action('Voir mes rendez-vous', url('/client/appointments'))
            ->line('Merci de votre confiance !');
    }

    /**
     * Construit la représentation en tableau pour le stockage en base de données.
     *
     * @param  mixed  $notifiable  L'entité à notifier (client)
     * @return array<string, mixed>  Les données de la modification à stocker
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_updated',
            'appointment_id' => $this->appointment->id,
            'message' => 'Votre rendez-vous pour ' . $this->appointment->service->name . ' a été modifié pour le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . '.',
        ];
    }
}
