<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminPaymentNotification;
use Illuminate\Support\Facades\Hash;

class PaymentSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_simulate_payment_and_admins_are_notified()
    {
        Notification::fake();

        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create a client
        $client = Client::create([
            'name' => 'Test Client',
            'email' => 'client@example.test',
            'password' => Hash::make('password'),
            'phone' => '0700000000',
        ]);

        // Create a service
        $service = Service::create([
            'name' => 'Test Service',
            'description' => 'Desc',
            'price' => 1000,
            'duration' => 30,
            'active' => true,
        ]);

        // Create an appointment for that client
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'service_id' => $service->id,
            'scheduled_at' => now()->subDay(),
            'status' => 'confirmed',
        ]);

        // Act as client and post to simulate route
        $response = $this->actingAs($client, 'clients')->post(route('client.payments.simulate', $appointment));

        $response->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'appointment_id' => $appointment->id,
            'client_id' => $client->id,
        ]);

        $payment = Payment::where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('paid', $payment->status);

        // appointment should be updated to completed
        $this->assertEquals('completed', $appointment->fresh()->status->value ?? $appointment->fresh()->status);

        // Admins should be notified
        Notification::assertSentTo([ $admin ], AdminPaymentNotification::class);
    }
}
