<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class EmployeeServiceController extends Controller
{
    /**
     * Affiche la liste des services à réaliser aujourd'hui pour l'employé.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = auth('employees')->user();

        $todayAppointments = $employee->appointments()
            ->with(['client', 'service'])
            ->whereDate('date', now()->toDateString())
            ->orderBy('time')
            ->get();

        return view('employee.services.index', compact('todayAppointments', 'employee'));
    }

    /**
     * Affiche les détails d'un service spécifique.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function show(Service $service)
    {
        return view('employee.services.show', compact('service'));
    }
}
