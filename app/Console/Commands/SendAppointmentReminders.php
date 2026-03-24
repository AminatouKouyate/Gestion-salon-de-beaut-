<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\ClientNotificationService;
use Illuminate\Console\Command;

/**
 * Commande Artisan d'envoi des rappels de rendez-vous.
 *
 * Cette commande récupère tous les rendez-vous prévus pour le lendemain
 * (dans les prochaines 24 heures) qui n'ont pas encore reçu de rappel,
 * puis envoie une notification de rappel à chaque client concerné.
 * Elle est destinée à être exécutée quotidiennement via le planificateur de tâches.
 *
 * @package App\Console\Commands
 */
class SendAppointmentReminders extends Command
{
    /**
     * Le nom et la signature de la commande Artisan.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * La description de la commande Artisan.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for appointments scheduled for the next 24 hours';

    /**
     * Exécute la commande d'envoi des rappels.
     *
     * Recherche les rendez-vous prévus pour le lendemain avec un statut
     * « pending » ou « confirmed » et pour lesquels aucun rappel n'a encore
     * été envoyé. Pour chaque rendez-vous trouvé, envoie une notification
     * de rappel au client et marque le rendez-vous comme rappelé.
     *
     * @param  \App\Services\ClientNotificationService  $notificationService  Le service de notifications client
     * @return int  Code de sortie de la commande
     */
    public function handle(ClientNotificationService $notificationService): int
    {
        // Définir la plage horaire du lendemain (de 00:00 à 23:59)
        $tomorrowStart = now()->addDay()->startOfDay();
        $tomorrowEnd = now()->addDay()->endOfDay();

        // Récupérer les rendez-vous éligibles au rappel
        $appointments = Appointment::whereBetween('scheduled_at', [$tomorrowStart, $tomorrowEnd])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNull('reminder_sent_at')
            ->with(['client', 'service'])
            ->get();

        $count = 0;

        // Envoyer un rappel pour chaque rendez-vous et marquer comme envoyé
        foreach ($appointments as $appointment) {
            $notificationService->notifyAppointmentReminder($appointment);
            $appointment->update(['reminder_sent_at' => now()]);
            $count++;
        }

        $this->info("Sent {$count} reminder notifications.");

        return Command::SUCCESS;
    }
}
