<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCancelledNotification extends Notification
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
            ->subject('Annulation de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre rendez-vous a été annulé.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Heure: ' . $this->appointment->time)
            ->action('Réserver un nouveau rendez-vous', url('/client/appointments/create'))
            ->line('Nous espérons vous revoir bientôt !');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_cancelled',
            'appointment_id' => $this->appointment->id,
            'message' => 'Votre rendez-vous pour ' . $this->appointment->service->name . ' le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . ' a été annulé.',
        ];
    }
}
