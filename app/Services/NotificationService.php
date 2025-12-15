<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientNotification;
use App\Models\Appointment;

class NotificationService
{
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
