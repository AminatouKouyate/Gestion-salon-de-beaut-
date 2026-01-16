<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\ClientNotificationService;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Send reminder notifications for appointments scheduled for the next 24 hours';

    public function handle(ClientNotificationService $notificationService): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $appointments = Appointment::where('date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNull('reminder_sent_at')
            ->with(['client', 'service'])
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            $notificationService->notifyAppointmentReminder($appointment);
            $appointment->update(['reminder_sent_at' => now()]);
            $count++;
        }

        $this->info("Sent {$count} reminder notifications.");

        return Command::SUCCESS;
    }
}
