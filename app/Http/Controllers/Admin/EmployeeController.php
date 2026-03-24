<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * Contrôleur de gestion des employés pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'ensemble des opérations liées aux employés du salon :
 * - Opérations CRUD (création, lecture, modification, suppression)
 * - Gestion des services assignés à chaque employé
 * - Suivi des performances et statistiques mensuelles
 * - Activation/désactivation des comptes employés
 * 
 * Les employés peuvent avoir deux rôles : "employee" (employé standard)
 * ou "manager" (responsable avec accès étendu).
 * 
 * @package App\Http\Controllers\Admin
 * @author Système de gestion Salon de Beauté
 */
class EmployeeController extends Controller
{
    // ==========================================================================
    // SECTION CRUD - OPÉRATIONS DE BASE
    // ==========================================================================

    /**
     * Affiche la liste paginée de tous les employés.
     * 
     * Récupère les employés avec leurs services associés pour affichage.
     * La pagination est fixée à 10 éléments par page.
     *
     * @return \Illuminate\View\View Vue de la liste des employés
     */
    public function index()
    {
        // Chargement eager de la relation 'services' pour éviter le N+1
        $employees = Employee::with('services')->paginate(10);
        
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel employé.
     * 
     * Prépare la liste des services actifs qui peuvent être
     * assignés au nouvel employé.
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Récupération des services actifs triés par nom
        $services = Service::active()->orderBy('name')->get();
        
        return view('admin.employees.create', compact('services'));
    }

    /**
     * Enregistre un nouvel employé dans la base de données.
     * 
     * Cette méthode :
     * - Valide les données du formulaire
     * - Crée l'employé avec mot de passe hashé
     * - Synchronise les services assignés
     * 
     * Champs validés :
     * - Informations personnelles (nom, email, téléphone)
     * - Identifiants de connexion (email unique, mot de passe confirmé)
     * - Rôle (employee ou manager)
     * - Horaires de travail par défaut
     * - Services proposés
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:employee,manager',
            'phone' => 'nullable|string|max:20',
            'specialties' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i|after:work_start_time',
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $employee = Employee::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'specialties' => $request->specialties,
                    'is_active' => $request->boolean('is_active', true),
                    'work_start_time' => $request->work_start_time,
                    'work_end_time' => $request->work_end_time,
                    'work_days' => $request->work_days ?? [],
                ]);

                if ($request->has('services')) {
                    $employee->services()->sync($request->services);
                }
            });

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employé ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'un employé existant.
     * 
     * Charge l'employé avec ses services actuels pour pré-remplir
     * le formulaire d'édition.
     *
     * @param  \App\Models\Employee  $employee Instance de l'employé à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Employee $employee)
    {
        $services = Service::active()->orderBy('name')->get();
        $employee->load('services'); // Chargement des services actuels
        
        return view('admin.employees.edit', compact('employee', 'services'));
    }

    /**
     * Met à jour les informations d'un employé existant.
     * 
     * Cette méthode permet de modifier toutes les informations de l'employé.
     * Le mot de passe n'est mis à jour que s'il est explicitement fourni
     * dans le formulaire (champ optionnel lors de l'édition).
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données
     * @param  \App\Models\Employee  $employee Instance de l'employé à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'role' => 'required|in:employee,manager',
            'phone' => 'nullable|string|max:20',
            'specialties' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i|after:work_start_time',
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::transaction(function () use ($request, $employee) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                    'phone' => $request->phone,
                    'specialties' => $request->specialties,
                    'is_active' => $request->boolean('is_active'),
                    'work_start_time' => $request->work_start_time,
                    'work_end_time' => $request->work_end_time,
                    'work_days' => $request->work_days ?? [],
                ];

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $employee->update($data);

                $employee->services()->sync($request->services ?? []);
            });

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employé mis à jour.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un employé de la base de données.
     * 
     * Cette méthode effectue une suppression complète :
     * - Détache d'abord les services pour éviter les erreurs de contrainte
     * - Puis supprime l'employé
     * 
     * Note : Les rendez-vous associés ne sont pas supprimés automatiquement
     * pour conserver l'historique.
     *
     * @param  \App\Models\Employee  $employee Instance de l'employé à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('admin.employees.index')
                ->with('success', 'Employé supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer : ' . $e->getMessage());
        }
    }

    // ==========================================================================
    // SECTION PROFIL ET STATISTIQUES
    // ==========================================================================

    /**
     * Affiche le profil détaillé d'un employé avec ses statistiques de performance.
     * 
     * Cette méthode calcule et affiche :
     * - Nombre total de rendez-vous (historique complet)
     * - Rendez-vous du mois en cours
     * - Rendez-vous complétés et annulés
     * - Chiffre d'affaires mensuel généré
     * - Liste des prochains rendez-vous
     * - Rendez-vous du jour
     *
     * @param  \App\Models\Employee  $employee Instance de l'employé à afficher
     * @return \Illuminate\View\View Vue du profil avec les statistiques
     */
    public function show(Employee $employee)
    {
        // Chargement eager des relations pour l'affichage
        $employee->load('services', 'appointments.service', 'appointments.client');
        
        // Définition de la période du mois en cours
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // ======================================================================
        // CALCUL DES STATISTIQUES MENSUELLES
        // ======================================================================
        $monthlyAppointments = $employee->appointments()
            ->whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
            ->get();
        
        // ======================================================================
        // COMPILATION DES INDICATEURS DE PERFORMANCE
        // ======================================================================
        $performance = [
            // Statistiques globales
            'total_appointments' => $employee->appointments()->count(),
            'monthly_appointments' => $monthlyAppointments->count(),
            'completed_appointments' => $employee->appointments()
                ->where('status', 'completed')
                ->count(),
            'cancelled_appointments' => $employee->appointments()
                ->where('status', 'canceled')
                ->count(),
            
            // Chiffre d'affaires mensuel (basé sur les RDV complétés)
            'monthly_revenue' => $monthlyAppointments
                ->where('status', 'completed')
                ->sum(function ($apt) {
                    return $apt->service ? $apt->service->getCurrentPrice() : 0;
                }),
            
            // Rendez-vous à venir (5 prochains)
            'upcoming_appointments' => $employee->upcomingAppointments()
                ->limit(5)
                ->get(),
            
            // Rendez-vous du jour
            'today_appointments' => $employee->todayAppointments()->get(),
        ];
        
        return view('admin.employees.show', compact('employee', 'performance'));
    }

    // ==========================================================================
    // SECTION GESTION DU STATUT
    // ==========================================================================

    /**
     * Active ou désactive le statut d'un employé.
     * 
     * Cette méthode bascule le statut is_active de l'employé.
     * Un employé désactivé :
     * - N'apparaît plus dans les sélections de rendez-vous
     * - Ne peut plus se connecter à son espace
     * - Conserve son historique de rendez-vous
     *
     * @param  \App\Models\Employee  $employee Instance de l'employé à activer/désactiver
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page précédente avec message
     */
    public function toggleActive(Employee $employee)
    {
        try {
            $employee->update(['is_active' => !$employee->is_active]);

            $status = $employee->is_active ? 'activé' : 'désactivé';

            return redirect()->back()
                ->with('success', "Employé {$status} avec succès.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
