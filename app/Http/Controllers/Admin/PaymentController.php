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
        $payments = Payment::with(['client', 'appointment'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $clients = Client::all();
        $appointments = Appointment::whereDoesntHave('payment')->get(); // On ne montre que les RDV non payés
        return view('admin.payments.create', compact('clients', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id|unique:payments,appointment_id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid'
        ]);

        Payment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Paiement ajouté avec succès.');
    }

    public function edit(Payment $payment)
    {
        $clients = Client::all();
        // Permet de modifier le RDV associé au paiement, ou de le garder
        $appointments = Appointment::whereDoesntHave('payment')->orWhere('id', $payment->appointment_id)->get();
        return view('admin.payments.edit', compact('payment', 'clients', 'appointments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id|unique:payments,appointment_id,' . $payment->id,
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
