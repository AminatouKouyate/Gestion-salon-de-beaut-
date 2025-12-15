<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        // Statistiques
        $todayAppointments = $employee->todayAppointments()->get();
        $upcomingAppointments = $employee->upcomingAppointments()->take(5)->get();
        $totalAppointments = $employee->appointments()->count();
        $completedAppointments = $employee->appointments()->where('status', 'completed')->count();

        // Notifications non lues
        $unreadNotifications = $employee->notifications()->unread()->latest()->take(5)->get();
        $unreadCount = $employee->unreadNotificationsCount();

        // Demandes de congés en attente
        $pendingLeaves = $employee->leaveRequests()->pending()->count();

        return view('employee.dashboard', compact(
            'employee',
            'todayAppointments',
            'upcomingAppointments',
            'totalAppointments',
            'completedAppointments',
            'unreadNotifications',
            'unreadCount',
            'pendingLeaves'
        ));
    }
}
