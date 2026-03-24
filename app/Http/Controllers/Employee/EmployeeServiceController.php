<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la consultation des services côté employé.
 * 
 * Permet aux employés de voir les services qu'ils doivent réaliser
 * aujourd'hui ainsi que les détails de chaque service proposé.
 */
class EmployeeServiceController extends Controller
{
    /**
     * Affiche la liste des services à réaliser aujourd'hui pour l'employé.
     * 
     * Récupère tous les rendez-vous du jour avec les informations
     * du client et du service associé, triés par heure.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = auth('employees')->user();

        // Récupération des rendez-vous du jour avec relations client et service
        $todayAppointments = $employee->appointments()
            ->with(['client', 'service'])
            ->whereDate('scheduled_at', now()->toDateString())
            ->orderBy('scheduled_at')
            ->get();

        return view('employee.services.index', compact('todayAppointments', 'employee'));
    }

    /**
     * Affiche les détails d'un service spécifique.
     * 
     * Permet à l'employé de consulter la description, la durée
     * et le prix d'un service proposé par le salon.
     *
     * @param  \App\Models\Service  $service  Le service à afficher
     * @return \Illuminate\View\View
     */
    public function show(Service $service)
    {
        return view('employee.services.show', compact('service'));
    }
}
