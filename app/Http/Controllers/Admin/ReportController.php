<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Chiffre d'affaires total
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        // Services les plus demandés (top 5)
        $topServices = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get();

        // Performance des employés (nombre de rendez-vous traités)
        $employeePerformance = Employee::withCount('appointments')
            ->get();

        return view('admin.reports.index', compact('totalRevenue', 'topServices', 'employeePerformance'));
    }
}
