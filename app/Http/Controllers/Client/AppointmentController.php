<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    public function index()
    {
        $client = Auth::guard('clients')->user();
        $appointments = Appointment::where('client_id', $client->id)
            ->with(['service', 'employee'])
            ->upcoming()
            ->orderBy('date')
            ->orderBy('time')
            ->paginate(10);

        return view('Clients.appointments.index', compact('appointments'));
    }

    public function history()
    {
        $client = Auth::guard('clients')->user();
        $appointments = Appointment::where('client_id', $client->id)
            ->with(['service', 'employee', 'payment'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('Clients.appointments.history', compact('appointments'));
    }

    public function create()
    {
        $services = Service::active()->get();
        $employees = Employee::all();

        return view('Clients.appointments.create', compact('services', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $client = Auth::guard('clients')->user();

        $existingAppointment = Appointment::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'Ce créneau est déjà réservé. Veuillez choisir un autre horaire.');
        }

        Appointment::create([
            'client_id' => $client->id,
            'service_id' => $request->service_id,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous réservé avec succès');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->load(['service', 'employee', 'payment']);

        return view('Clients.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Impossible de modifier un rendez-vous terminé');
        }

        $services = Service::active()->get();
        $employees = Employee::all();

        return view('Clients.appointments.edit', compact('appointment', 'services', 'employees'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Impossible de modifier un rendez-vous terminé');
        }

        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($data);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous modifié avec succès');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Impossible d\'annuler un rendez-vous terminé');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous annulé');
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        $bookedSlots = Appointment::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('time')
            ->toArray();

        $allSlots = [];
        for ($hour = 9; $hour < 18; $hour++) {
            $allSlots[] = sprintf('%02d:00', $hour);
            $allSlots[] = sprintf('%02d:30', $hour);
        }

        $availableSlots = array_diff($allSlots, $bookedSlots);

        return response()->json(array_values($availableSlots));
    }

    private function authorizeAppointment(Appointment $appointment)
    {
        $client = Auth::guard('clients')->user();
        if ($appointment->client_id !== $client->id) {
            abort(403, 'Accès non autorisé');
        }
    }
}
