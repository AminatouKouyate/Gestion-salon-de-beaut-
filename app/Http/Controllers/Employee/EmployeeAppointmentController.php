<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAppointmentController extends Controller
{
    /**
     * Affiche la liste des rendez-vous pour l'employé connecté.
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        // Récupérer les rendez-vous à venir
        $appointments = $employee->appointments()
            ->with(['client', 'service'])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->paginate(15);

        return view('employee.appointments.index', compact('appointments', 'employee'));
    }

    /**
     * Affiche les détails d'un rendez-vous spécifique.
     */
    public function show(Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        // Vérifier que le rendez-vous appartient à l'employé
        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $appointment->load(['client', 'service']);

        return view('employee.appointments.show', compact('appointment', 'employee'));
    }

    /**
     * Met à jour le statut d'un rendez-vous.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        // Vérifier que le rendez-vous appartient à l'employé
        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,canceled,no-show',
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Statut du rendez-vous mis à jour avec succès.');
    }

    /**
     * Ajoute des notes internes sur un client/rendez-vous.
     */
    public function addNotes(Request $request, Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        // Vérifier que le rendez-vous appartient à l'employé
        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $currentNotes = $appointment->notes ?? '';
        $newNote = "\n[" . now()->format('d/m/Y H:i') . " - " . $employee->name . "]\n" . $request->notes;

        $appointment->update([
            'notes' => $currentNotes . $newNote
        ]);

        return back()->with('success', 'Note ajoutée avec succès.');
    }
}
