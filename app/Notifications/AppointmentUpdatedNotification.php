<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

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

    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_updated',
            'appointment_id' => $this->appointment->id,
            'message' => 'Votre rendez-vous pour ' . $this->appointment->service->name . ' a été modifié pour le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . '.',
        ];
    }
}
