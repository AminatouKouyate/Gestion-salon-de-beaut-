<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur de génération de rapports et de statistiques pour le panneau d'administration.
 * 
 * Ce contrôleur centralise toutes les fonctionnalités de reporting du salon :
 * - Tableau de bord avec indicateurs clés de performance (KPIs)
 * - Graphiques d'évolution sur 12 mois (revenus, rendez-vous, clients)
 * - Classement des services les plus populaires (top 10)
 * - Tableau de performance individuelle des employés
 * - Export des statistiques au format CSV
 * 
 * Les revenus sont calculés en combinant :
 * - Les paiements enregistrés avec statut 'paid' ou 'completed'
 * - Les rendez-vous terminés sans paiement enregistré (basé sur le prix du service)
 * 
 * @package App\Http\Controllers\Admin
 */
class ReportController extends Controller
{
    /**
     * Affiche la page principale des rapports avec les KPIs et graphiques.
     * 
     * Cette méthode agrège plusieurs sources de données pour construire
     * un tableau de bord complet :
     * 
     * **KPIs globaux :**
     * - Chiffre d'affaires total (paiements + RDV terminés sans paiement)
     * - Nombre total de rendez-vous
     * - Nombre total de clients
     * - Nombre d'employés actifs
     * 
     * **Graphiques sur 12 mois :**
     * - Évolution du chiffre d'affaires mensuel
     * - Évolution du nombre de rendez-vous par mois
     * - Évolution du nombre de nouveaux clients par mois
     * 
     * **Statistiques détaillées :**
     * - Top 10 des services les plus réservés
     * - Performance de chaque employé (RDV terminés, revenus générés)
     * - Nombre de paiements en attente
     * - Rendez-vous du jour et revenus du mois en cours
     *
     * @return \Illuminate\View\View Vue du tableau de bord des rapports
     */
    public function index()
    {
        $now = Carbon::now();
        // Définition de la période d'analyse : 12 derniers mois complets
        $startOf12Months = $now->copy()->subMonths(11)->startOfMonth();

        // ======================================================================
        // SECTION 1 : INDICATEURS CLÉS DE PERFORMANCE (KPIs)
        // ======================================================================

        // Calcul des revenus issus des paiements enregistrés (statut payé ou complété)
        $paidPayments = Payment::whereIn('status', ['paid', 'completed'])->sum('amount');
        
        // Calcul des revenus des RDV terminés sans paiement via jointure SQL
        // (utilise le prix promotionnel si la promotion est active, sinon le prix normal)
        $completedWithoutPayment = (float) Appointment::where('appointments.status', 'completed')
            ->doesntHave('payment')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');
        
        // Chiffre d'affaires total = paiements enregistrés + revenus implicites des RDV terminés
        $totalRevenue = $paidPayments + $completedWithoutPayment;
        $totalAppointments = Appointment::count();
        $totalClients = Client::count();
        $totalEmployees = Employee::where('is_active', true)->count();

        // ======================================================================
        // SECTION 2 : DONNÉES POUR LES GRAPHIQUES (12 DERNIERS MOIS)
        // Adaptation du format de date SQL selon le driver de base de données
        // ======================================================================
        $driver = DB::getDriverName();

        // Sélection du format SQL de regroupement par mois selon le SGBD (PostgreSQL, SQLite ou MySQL)
        if ($driver === 'pgsql') {
            $dateFormat = "TO_CHAR(created_at, 'YYYY-MM')";
            $scheduledFormat = "TO_CHAR(scheduled_at, 'YYYY-MM')";
        } elseif ($driver === 'sqlite') {
            $dateFormat = "strftime('%Y-%m', created_at)";
            $scheduledFormat = "strftime('%Y-%m', scheduled_at)";
        } else {
            $dateFormat = "DATE_FORMAT(created_at, '%Y-%m')";
            $scheduledFormat = "DATE_FORMAT(scheduled_at, '%Y-%m')";
        }

        // Requête : chiffre d'affaires mensuel (paiements payés/complétés sur 12 mois)
        $revenueByMonth = Payment::whereIn('status', ['paid', 'completed'])
            ->where('created_at', '>=', $startOf12Months)
            ->selectRaw("{$dateFormat} as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Requête : nombre de rendez-vous par mois (basé sur la date planifiée)
        $appointmentsByMonth = Appointment::where('scheduled_at', '>=', $startOf12Months)
            ->selectRaw("{$scheduledFormat} as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Requête : nombre de nouveaux clients inscrits par mois
        $newClientsByMonth = Client::where('created_at', '>=', $startOf12Months)
            ->selectRaw("{$dateFormat} as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Construction des tableaux de données pour les graphiques JavaScript
        // Itération sur les 12 derniers mois (du plus ancien au plus récent)
        $monthLabels = [];
        $revenueData = [];
        $appointmentsData = [];
        $newClientsData = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthKey = $now->copy()->subMonths($i)->format('Y-m');
            // Format traduit du mois pour l'affichage (ex: "Jan 2026")
            $monthLabel = $now->copy()->subMonths($i)->translatedFormat('M Y');
            $monthLabels[] = $monthLabel;
            // Valeur par défaut à 0 si aucune donnée pour ce mois
            $revenueData[] = (float) ($revenueByMonth[$monthKey] ?? 0);
            $appointmentsData[] = (int) ($appointmentsByMonth[$monthKey] ?? 0);
            $newClientsData[] = (int) ($newClientsByMonth[$monthKey] ?? 0);
        }

        // ======================================================================
        // SECTION 3 : STATISTIQUES DÉTAILLÉES
        // ======================================================================

        // Top 10 des services les plus populaires (triés par nombre de rendez-vous)
        $topServices = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(10)
            ->get();

        // Performance des employés calculée en une seule requête SQL avec sous-requêtes
        // - Nombre de rendez-vous terminés et total
        // - Chiffre d'affaires généré (paiements + RDV terminés sans paiement)
        $employeePerformance = Employee::where('is_active', true)
            ->withCount(['appointments as completed_appointments_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withCount('appointments as total_appointments_count')
            ->addSelect(['paid_revenue' => Payment::selectRaw('COALESCE(SUM(payments.amount), 0)')
                ->whereIn('payments.status', ['paid', 'completed'])
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->whereColumn('appointments.employee_id', 'employees.id')
            ])
            ->addSelect(['unpaid_revenue' => Appointment::selectRaw('COALESCE(SUM(services.price), 0)')
                ->whereColumn('appointments.employee_id', 'employees.id')
                ->where('appointments.status', 'completed')
                ->leftJoin('payments', 'payments.appointment_id', '=', 'appointments.id')
                ->whereNull('payments.id')
                ->join('services', 'appointments.service_id', '=', 'services.id')
            ])
            ->orderByDesc('completed_appointments_count')
            ->get()
            ->each(function ($employee) {
                // Calculer le revenu total à partir des sous-requêtes
                $employee->revenue = (float) $employee->paid_revenue + (float) $employee->unpaid_revenue;
            });

        // ======================================================================
        // SECTION 4 : INDICATEURS SUPPLÉMENTAIRES (TEMPS RÉEL)
        // ======================================================================

        // Nombre de paiements en attente de traitement
        $pendingPayments = Payment::where('status', 'pending')->count();
        // Nombre de rendez-vous planifiés aujourd'hui
        $todayAppointments = Appointment::whereDate('scheduled_at', $now->toDateString())->count();
        // Chiffre d'affaires du mois en cours
        $monthRevenue = Payment::whereIn('status', ['paid', 'completed'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalAppointments',
            'totalClients',
            'totalEmployees',
            'monthLabels',
            'revenueData',
            'appointmentsData',
            'newClientsData',
            'topServices',
            'employeePerformance',
            'pendingPayments',
            'todayAppointments',
            'monthRevenue'
        ));
    }

    /**
     * Exporte un rapport complet des statistiques au format CSV.
     * 
     * Génère un fichier CSV structuré en sections contenant :
     * - Les indicateurs clés (chiffre d'affaires, nombre de RDV, clients, employés)
     * - L'évolution des revenus mensuels sur les 12 derniers mois
     * 
     * Le fichier est encodé en UTF-8 avec le point-virgule comme séparateur
     * (format standard pour les fichiers CSV français compatibles avec Excel).
     * Le nom du fichier inclut la date du jour pour un archivage facile.
     *
     * @return \Illuminate\Http\Response Réponse HTTP contenant le fichier CSV à télécharger
     */
    public function exportCsv()
    {
        $now = Carbon::now();
        $startOf12Months = $now->copy()->subMonths(11)->startOfMonth();

        // Adapter le format SQL selon le driver de base de données
        $driver = DB::getDriverName();
        $dateFormat = $driver === 'pgsql'
            ? "TO_CHAR(created_at, 'YYYY-MM')"
            : ($driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')");

        // Utiliser un téléchargement en streaming pour éviter de charger tout en mémoire
        $filename = 'rapport_statistiques_' . $now->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Pré-calculer les données agrégées avec des requêtes SQL optimisées
        $totalRevenue = Payment::whereIn('status', ['paid', 'completed'])->sum('amount');
        $totalAppointments = Appointment::count();
        $totalClients = Client::count();
        $totalActiveEmployees = Employee::where('is_active', true)->count();

        $revenueByMonth = Payment::whereIn('status', ['paid', 'completed'])
            ->where('created_at', '>=', $startOf12Months)
            ->selectRaw("{$dateFormat} as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Générer le CSV en streaming pour économiser la mémoire
        $callback = function () use ($now, $totalRevenue, $totalAppointments, $totalClients, $totalActiveEmployees, $revenueByMonth) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour compatibilité Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Rapport de statistiques - ' . $now->format('d/m/Y')], ';');
            fputcsv($handle, [], ';');
            fputcsv($handle, ['=== INDICATEURS CLÉS ==='], ';');
            fputcsv($handle, ['Métrique', 'Valeur'], ';');
            fputcsv($handle, ['Chiffre d\'affaires total', $totalRevenue . ' FCFA'], ';');
            fputcsv($handle, ['Total rendez-vous', $totalAppointments], ';');
            fputcsv($handle, ['Total clients', $totalClients], ';');
            fputcsv($handle, ['Employés actifs', $totalActiveEmployees], ';');
            fputcsv($handle, [], ';');
            fputcsv($handle, ['=== REVENUS PAR MOIS ==='], ';');
            fputcsv($handle, ['Mois', 'Revenus (FCFA)'], ';');

            for ($i = 11; $i >= 0; $i--) {
                $monthKey = $now->copy()->subMonths($i)->format('Y-m');
                fputcsv($handle, [$now->copy()->subMonths($i)->translatedFormat('F Y'), $revenueByMonth[$monthKey] ?? 0], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
