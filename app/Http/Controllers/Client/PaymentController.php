<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $client = Auth::guard('clients')->user();
        $payments = Payment::whereHas('appointment', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })
            ->with('appointment.service')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('Clients.payments.index', compact('payments'));
    }

    public function create(Appointment $appointment = null)
    {
        $client = Auth::guard('clients')->user();

        if ($appointment) {
            if ($appointment->client_id !== $client->id) {
                abort(403);
            }
            if ($appointment->payment) {
                return back()->with('error', 'Ce rendez-vous a déjà été payé');
            }
        }

        $unpaidAppointments = Appointment::where('client_id', $client->id)
            ->where('status', 'completed')
            ->doesntHave('payment')
            ->with('service')
            ->get();

        return view('Clients.payments.create', compact('unpaidAppointments', 'appointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'method' => 'required|in:stripe,paypal,cash,salon',
        ]);

        $client = Auth::guard('clients')->user();
        $appointment = Appointment::findOrFail($request->appointment_id);

        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        if ($appointment->payment) {
            return back()->with('error', 'Ce rendez-vous a déjà été payé');
        }

        if ($request->method === 'cash' || $request->method === 'salon') {
            Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->price,
                'method' => $request->method,
                'status' => 'pending',
            ]);

            return redirect()->route('payments.index')->with('success', 'Paiement enregistré - à régler au salon');
        }

        return redirect()->route('payments.process', [
            'appointment' => $appointment->id,
            'method' => $request->method
        ]);
    }

    public function show(Payment $payment)
    {
        $client = Auth::guard('clients')->user();
        if ($payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        $payment->load('appointment.service', 'appointment.employee');

        return view('Clients.payments.show', compact('payment'));
    }

    public function showInvoice(Payment $payment)
    {
        $client = Auth::guard('clients')->user();
        if ($payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        $payment->load('appointment.service', 'appointment.client', 'appointment.employee');

        return view('Clients.payments.invoice', compact('payment'));
    }

    public function downloadInvoice(Payment $payment)
    {
        $client = Auth::guard('clients')->user();
        if ($payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        $payment->load('appointment.service', 'appointment.client', 'appointment.employee');

        $pdf = Pdf::loadView('Clients.payments.invoice-pdf', compact('payment'));

        return $pdf->download('facture-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        return response()->json(['status' => 'received']);
    }
}
