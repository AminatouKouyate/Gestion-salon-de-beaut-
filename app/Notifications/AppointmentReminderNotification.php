<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminderNotification extends Notification
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
            ->subject('Rappel de rendez-vous - Salon de Beauté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Ceci est un rappel pour votre rendez-vous de demain.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Date: ' . $this->appointment->date->format('d/m/Y'))
            ->line('Heure: ' . $this->appointment->time)
            ->action('Voir mes rendez-vous', url('/client/appointments'))
            ->line('À demain !');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'appointment_reminder',
            'appointment_id' => $this->appointment->id,
            'message' => 'Rappel: Votre rendez-vous pour ' . $this->appointment->service->name . ' est prévu demain le ' . $this->appointment->date->format('d/m/Y') . ' à ' . $this->appointment->time . '.',
        ];
    }
}
