<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class EmployeeServiceController extends Controller
{
    /**
     * Affiche la liste des services pour l'employé.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('employee.services.index', compact('services'));
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
