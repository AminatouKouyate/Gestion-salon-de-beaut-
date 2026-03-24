<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour le tableau de bord de l'employé.
 * 
 * Affiche les statistiques, rendez-vous du jour, notifications
 * et autres informations pertinentes pour l'employé connecté.
 */
class EmployeeDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'employé connecté.
     * 
     * Récupère et compile les données suivantes :
     * - Rendez-vous du jour et à venir
     * - Statistiques des rendez-vous (total et complétés)
     * - Notifications non lues
     * - Demandes de congés en attente
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        // Récupération des rendez-vous du jour et à venir
        $todayAppointments = $employee->todayAppointments()->get();
        $upcomingAppointments = $employee->upcomingAppointments()->take(5)->get();
        
        // Statistiques des rendez-vous
        $totalAppointments = $employee->appointments()->count();
        $completedAppointments = $employee->appointments()->where('status', 'completed')->count();

        // Récupération des notifications non lues (5 dernières)
        $unreadNotifications = $employee->notifications()->unread()->latest()->take(5)->get();
        $unreadCount = $employee->unreadNotificationsCount();

        // Comptage des demandes de congés en attente
        $pendingLeaves = $employee->leaveRequests()->pending()->count();

        // Calcul du chiffre d'affaires total généré par l'employé (hors paiements échoués/annulés)
        $totalRevenue = \App\Models\Payment::whereHas('appointment', function($q) use ($employee) {
            $q->where('employee_id', $employee->id);
        })->whereNotIn('status', ['failed', 'canceled'])->sum('amount');

        // Récupération des 5 derniers paiements liés aux rendez-vous de l'employé
        $recentPayments = \App\Models\Payment::whereHas('appointment', function($q) use ($employee) {
            $q->where('employee_id', $employee->id);
        })->with('appointment.service', 'client')->orderBy('created_at', 'desc')->take(5)->get();

        return view('employee.dashboard', compact(
            'employee',
            'todayAppointments',
            'upcomingAppointments',
            'totalAppointments',
            'completedAppointments',
            'unreadNotifications',
            'unreadCount',
            'pendingLeaves',
            'totalRevenue',
            'recentPayments'
        ));
    }
}
