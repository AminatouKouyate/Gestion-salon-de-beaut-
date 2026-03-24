<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ClientNotification;

/**
 * Service de gestion des notifications destinées aux clients.
 *
 * Ce service centralise la création des notifications client liées
 * aux rendez-vous du salon de beauté : réservation, modification,
 * annulation et rappel. Chaque notification est enregistrée en base
 * de données via le modèle ClientNotification.
 *
 * @package App\Services
 */
class ClientNotificationService
{
    /**
     * Notifie le client de la confirmation de son rendez-vous.
     *
     * Crée une notification informant le client que son rendez-vous
     * a été réservé avec succès, en précisant le service, la date et l'heure.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous réservé
     * @return void
     */
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' le ' . $appointment->scheduled_at->format('d/m/Y') . ' à ' . $appointment->scheduled_at->format('H:i') . ' a été confirmé.',
            'type' => 'appointment_booked',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    /**
     * Notifie le client de la modification de son rendez-vous.
     *
     * Crée une notification informant le client que les détails de son
     * rendez-vous ont été modifiés (nouvelle date et/ou heure).
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous modifié
     * @return void
     */
    public function notifyAppointmentUpdated(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous modifié',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' a été modifié pour le ' . $appointment->scheduled_at->format('d/m/Y') . ' à ' . $appointment->scheduled_at->format('H:i') . '.',
            'type' => 'appointment_updated',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    /**
     * Notifie le client de l'annulation de son rendez-vous.
     *
     * Crée une notification informant le client que son rendez-vous
     * a été annulé, en précisant le service et le créneau concerné.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous annulé
     * @return void
     */
    public function notifyAppointmentCancelled(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rendez-vous annulé',
            'message' => 'Votre rendez-vous pour ' . $appointment->service->name . ' le ' . $appointment->scheduled_at->format('d/m/Y') . ' à ' . $appointment->scheduled_at->format('H:i') . ' a été annulé.',
            'type' => 'appointment_cancelled',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }

    /**
     * Envoie un rappel de rendez-vous au client.
     *
     * Crée une notification rappelant au client son rendez-vous prévu
     * pour le lendemain, avec le service, la date et l'heure.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous à rappeler
     * @return void
     */
    public function notifyAppointmentReminder(Appointment $appointment): void
    {
        $appointment->loadMissing('service');

        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'title' => 'Rappel de rendez-vous',
            'message' => 'Rappel: Votre rendez-vous pour ' . $appointment->service->name . ' est prévu demain le ' . $appointment->scheduled_at->format('d/m/Y') . ' à ' . $appointment->scheduled_at->format('H:i') . '.',
            'type' => 'appointment_reminder',
            'data' => ['appointment_id' => $appointment->id],
            'read' => false,
        ]);
    }
}
