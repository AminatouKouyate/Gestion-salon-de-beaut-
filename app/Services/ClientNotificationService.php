<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ClientNotification;

class ClientNotificationService
{
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' le ' . $appointment->date->format('d/m/Y') . ' à ' . $appointment->time . ' a été confirmé.',
            'type' => 'appointment_booked',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    public function notifyAppointmentUpdated(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous modifié',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' a été modifié pour le ' . $appointment->date->format('d/m/Y') . ' à ' . $appointment->time . '.',
            'type' => 'appointment_updated',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    public function notifyAppointmentCancelled(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous annulé',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' le ' . $appointment->date->format('d/m/Y') . ' à ' . $appointment->time . ' a été annulé.',
            'type' => 'appointment_cancelled',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    public function notifyAppointmentReminder(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rappel de rendez-vous',
            'message' => 'Rappel: Votre rendez-vous pour ' . $appointment->service->name . ' est prévu demain le ' . $appointment->date->format('d/m/Y') . ' à ' . $appointment->time . '.',
            'type' => 'appointment_reminder',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }
}
