<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Affiche la liste des demandes de congé de l'employé.
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
     */
    public function create()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.leaves.create', compact('employee'));
    }

    /**
     * Enregistre une nouvelle demande de congé.
     */
    public function store(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Vérifier qu'il n'y a pas de chevauchement avec d'autres demandes approuvées
        $hasOverlap = $employee->leaveRequests()
            ->where('status', 'approved')
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['start_date' => 'Vous avez déjà un congé approuvé pour cette période.'])->withInput();
        }

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
