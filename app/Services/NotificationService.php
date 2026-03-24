<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientNotification;
use App\Models\Appointment;

/**
 * Service d'envoi de notifications aux clients.
 *
 * Ce service fournit des méthodes statiques pour envoyer différents types
 * de notifications aux clients : rappels de rendez-vous, confirmations,
 * paiements, points de fidélité et alertes promotionnelles. Chaque
 * notification est enregistrée en base de données via ClientNotification.
 *
 * @package App\Services
 */
class NotificationService
{
    /**
     * Envoie un rappel de rendez-vous au client.
     *
     * Crée une notification de rappel et marque le rendez-vous comme
     * ayant reçu son rappel (mise à jour de reminder_sent et reminder_sent_at).
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous à rappeler
     * @return void
     */
    public static function sendAppointmentReminder(Appointment $appointment): void
    {
        $client = $appointment->client;
        
        ClientNotification::create([
            'client_id' => $client->id,
            'type' => 'appointment_reminder',
            'title' => 'Rappel de rendez-vous',
            'message' => "N'oubliez pas votre rendez-vous pour {$appointment->service->name} le {$appointment->date->format('d/m/Y')} à {$appointment->time}.",
            'data' => [
                'appointment_id' => $appointment->id,
                'service' => $appointment->service->name,
                'date' => $appointment->date->format('Y-m-d'),
                'time' => $appointment->time,
            ],
        ]);

        $appointment->update([
            'reminder_sent' => true,
            'reminder_sent_at' => now(),
        ]);
    }

    /**
     * Envoie une confirmation de rendez-vous au client.
     *
     * Crée une notification informant le client que son rendez-vous
     * a été confirmé avec le service, la date et l'heure prévus.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous confirmé
     * @return void
     */
    public static function sendAppointmentConfirmation(Appointment $appointment): void
    {
        $client = $appointment->client;
        
        ClientNotification::create([
            'client_id' => $client->id,
            'type' => 'appointment_confirmed',
            'title' => 'Rendez-vous confirmé',
            'message' => "Votre rendez-vous pour {$appointment->service->name} le {$appointment->date->format('d/m/Y')} à {$appointment->time} a été confirmé.",
            'data' => [
                'appointment_id' => $appointment->id,
            ],
        ]);
    }

    /**
     * Envoie une confirmation de paiement au client.
     *
     * Crée une notification informant le client que son paiement
     * a été reçu avec succès pour le service concerné.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous lié au paiement
     * @param  float                    $amount       Le montant du paiement en euros
     * @return void
     */
    public static function sendPaymentConfirmation(Appointment $appointment, float $amount): void
    {
        $client = $appointment->client;
        
        ClientNotification::create([
            'client_id' => $client->id,
            'type' => 'payment_confirmed',
            'title' => 'Paiement reçu',
            'message' => "Votre paiement de {$amount}€ pour {$appointment->service->name} a été reçu. Merci !",
            'data' => [
                'appointment_id' => $appointment->id,
                'amount' => $amount,
            ],
        ]);
    }

    /**
     * Notifie le client des points de fidélité gagnés.
     *
     * Crée une notification informant le client du nombre de points
     * de fidélité qu'il vient de gagner ainsi que son nouveau total.
     *
     * @param  \App\Models\Client  $client  Le client ayant gagné des points
     * @param  int                 $points  Le nombre de points gagnés
     * @return void
     */
    public static function sendLoyaltyPointsEarned(Client $client, int $points): void
    {
        ClientNotification::create([
            'client_id' => $client->id,
            'type' => 'loyalty_points',
            'title' => 'Points fidélité gagnés !',
            'message' => "Vous avez gagné {$points} points fidélité ! Votre total : {$client->loyalty_points} points.",
            'data' => [
                'points_earned' => $points,
                'total_points' => $client->loyalty_points,
            ],
        ]);
    }

    /**
     * Envoie une alerte promotionnelle au client.
     *
     * Crée une notification informant le client d'une promotion
     * en cours au salon de beauté.
     *
     * @param  \App\Models\Client  $client          Le client destinataire
     * @param  string              $promotionTitle   Le titre de la promotion
     * @param  string              $message          Le message décrivant la promotion
     * @return void
     */
    public static function sendPromotionAlert(Client $client, string $promotionTitle, string $message): void
    {
        ClientNotification::create([
            'client_id' => $client->id,
            'type' => 'promotion',
            'title' => $promotionTitle,
            'message' => $message,
            'data' => [],
        ]);
    }
}
