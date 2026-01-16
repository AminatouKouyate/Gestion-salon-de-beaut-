<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['client', 'service', 'employee'])->latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $clients = Client::all();
        $services = Service::all();
        $employees = Employee::all();
        return view('admin.appointments.create', compact('clients', 'services', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Rendez-vous ajouté avec succès.');
    }

    public function edit(Appointment $appointment)
    {
        $clients = Client::all();
        $services = Service::all();
        $employees = Employee::all();
        return view('admin.appointments.edit', compact('appointment', 'clients', 'services', 'employees'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }
}