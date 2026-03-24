<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour la gestion des demandes de congé des employés.
 * 
 * Permet aux employés de soumettre des demandes de congé,
 * de consulter leur historique et de suivre le statut de leurs demandes.
 */
class LeaveRequestController extends Controller
{
    /**
     * Affiche la liste des demandes de congé de l'employé.
     * 
     * Les demandes sont triées par date de création décroissante et paginées.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        $leaveRequests = $employee->leaveRequests()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.leaves.index', compact('leaveRequests', 'employee'));
    }

    /**
     * Affiche le formulaire de création d'une demande de congé.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.leaves.create', compact('employee'));
    }

    /**
     * Enregistre une nouvelle demande de congé.
     * 
     * Validations effectuées :
     * - La date de début doit être aujourd'hui ou ultérieure
     * - La date de fin doit être égale ou postérieure à la date de début
     * - Aucun chevauchement avec des congés déjà approuvés
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant les dates et la raison
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Vérification des chevauchements avec les congés déjà approuvés
        $hasOverlap = $employee->leaveRequests()
            ->where('status', 'approved')
            ->where(function($query) use ($request) {
                // Cas 1 : la nouvelle date de début tombe dans une période existante
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      // Cas 2 : la nouvelle date de fin tombe dans une période existante
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      // Cas 3 : la nouvelle période englobe entièrement une période existante
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['start_date' => 'Vous avez déjà un congé approuvé pour cette période.'])->withInput();
        }

        // Création de la demande avec statut en attente
        LeaveRequest::create([
            'employee_id' => $employee->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'Demande de congé soumise avec succès. En attente d\'approbation.');
    }
}
