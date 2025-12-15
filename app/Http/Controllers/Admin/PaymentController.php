<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['client','appointment'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $appointments = Appointment::orderBy('scheduled_at', 'desc')->get(['id', 'scheduled_at']);
        return view('admin.payments.create', compact('clients', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid'
        ]);

        Payment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Paiement ajouté avec succès.');
    }

    public function edit(Payment $payment)
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $appointments = Appointment::orderBy('scheduled_at', 'desc')->get(['id', 'scheduled_at']);
        return view('admin.payments.edit', compact('payment', 'clients', 'appointments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid'
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Paiement mis à jour.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Paiement supprimé.');
    }
}
