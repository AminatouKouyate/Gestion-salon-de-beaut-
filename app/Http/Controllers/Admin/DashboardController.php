<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Contrôleur du tableau de bord d'administration du salon de beauté.
 * 
 * Ce contrôleur centralise les statistiques clés et les indicateurs
 * de performance du salon : nombre de clients, employés, rendez-vous,
 * chiffre d'affaires total et mensuel, ainsi que les rendez-vous récents.
 * 
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal avec les statistiques et les rendez-vous récents.
     * 
     * Les statistiques calculées comprennent :
     * - Compteurs globaux (clients, employés, rendez-vous, services)
     * - Rendez-vous en attente et confirmés
     * - Chiffre d'affaires total (hors paiements échoués/annulés)
     * - Chiffre d'affaires du mois en cours
     *
     * @return \Illuminate\View\View Vue du tableau de bord avec les données statistiques
     */
    public function index()
    {
        // ======================================================================
        // COMPILATION DES STATISTIQUES DU SALON (avec cache de 60 secondes)
        // Évite de recalculer les stats à chaque chargement de page
        // ======================================================================
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            return [
                // Compteurs globaux des entités principales
                'total_clients' => Client::count(),
                'total_employees' => Employee::count(),
                'total_appointments' => Appointment::count(),
                'total_services' => Service::count(),

                // Rendez-vous filtrés par statut
                'pending_appointments' => Appointment::where('status', 'pending')->count(),
                'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),

                // Chiffre d'affaires total (exclut les paiements échoués et annulés)
                'total_revenue' => Payment::whereNotIn('status', ['failed', 'canceled'])->sum('amount'),

                // Chiffre d'affaires du mois en cours uniquement
                'monthly_revenue' => Payment::whereNotIn('status', ['failed', 'canceled'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
            ];
        });

        // Récupération des 5 derniers rendez-vous avec chargement eager des relations
        $recentAppointments = Appointment::with(['client', 'service', 'employee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments'));
    }
}

