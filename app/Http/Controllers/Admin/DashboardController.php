<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer les statistiques pour le tableau de bord
        $stats = [
            'total_clients' => Client::count(),
            'total_employees' => Employee::count(),
            'total_appointments' => Appointment::count(),
            'total_services' => Service::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
        ];

        // Récupérer les derniers rendez-vous
        $recentAppointments = Appointment::with(['client', 'service', 'employee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments'));
    }
}
