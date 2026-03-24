<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\BlockedSlot;
use App\Models\LeaveRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Contrôleur de gestion des rendez-vous pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'ensemble des opérations CRUD (Create, Read, Update, Delete)
 * pour les rendez-vous du salon de beauté. Il permet également de vérifier
 * la disponibilité des employés en temps réel et de gérer les points de fidélité
 * lors de la complétion d'un rendez-vous.
 * 
 * @package App\Http\Controllers\Admin
 * @author Système de gestion Salon de Beauté
 */
class AppointmentController extends Controller
{
    // ==========================================================================
    // SECTION CRUD - OPÉRATIONS DE BASE
    // ==========================================================================

    /**
     * Affiche la liste paginée de tous les rendez-vous.
     * 
     * Récupère tous les rendez-vous avec leurs relations (client, service, employé)
     * et les affiche dans un tableau paginé pour une navigation optimale.
     * Les rendez-vous sont triés du plus récent au plus ancien.
     *
     * @return \Illuminate\View\View Vue de la liste des rendez-vous
     */
    public function index()
    {
        // Chargement eager des relations pour éviter le problème N+1
        // Pagination à 10 éléments par page pour des performances optimales
        $appointments = Appointment::with(['client', 'service', 'employee'])
            ->latest()
            ->paginate(10);
            
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau rendez-vous.
     * 
     * Prépare les données nécessaires pour le formulaire :
     * - Liste des clients actifs triés par nom
     * - Liste des services actifs triés par catégorie puis par nom
     * - Liste des employés actifs triés par nom
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Récupération des clients actifs uniquement
        $clients = Client::where('active', true)
            ->orderBy('name')
            ->get();
        
        // Récupération des services actifs, groupés par catégorie
        $services = Service::where('active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        
        // Récupération des employés actifs
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.appointments.create', compact('clients', 'services', 'employees'));
    }

    /**
     * Enregistre un nouveau rendez-vous dans la base de données.
     * 
     * Valide les données du formulaire et crée le rendez-vous.
     * La date et l'heure sont combinées pour former le champ scheduled_at.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Validation des données avec messages d'erreur personnalisés en français
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'client_id.required' => 'Le client est obligatoire.',
            'service_id.required' => 'Le service est obligatoire.',
            'employee_id.required' => 'L\'employé est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'time.required' => 'L\'heure est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
        ]);

        try {
            // Création du rendez-vous avec fusion date/heure
            Appointment::create([
                'client_id' => $request->client_id,
                'service_id' => $request->service_id,
                'employee_id' => $request->employee_id,
                'scheduled_at' => $request->date . ' ' . $request->time,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Rendez-vous ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'un rendez-vous spécifique.
     * 
     * Utilise le Route Model Binding de Laravel pour récupérer
     * automatiquement le rendez-vous correspondant à l'ID.
     *
     * @param  \App\Models\Appointment  $appointment Instance du rendez-vous à afficher
     * @return \Illuminate\View\View Vue des détails du rendez-vous
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['client', 'service', 'employee', 'payment']);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Affiche le formulaire de modification d'un rendez-vous existant.
     * 
     * Charge le rendez-vous à modifier ainsi que toutes les données
     * nécessaires pour les listes déroulantes du formulaire.
     *
     * @param  \App\Models\Appointment  $appointment Instance du rendez-vous à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Appointment $appointment)
    {
        // Récupération de toutes les entités pour les listes de sélection
        $clients = Client::all();
        $services = Service::all();
        $employees = Employee::all();
        
        return view('admin.appointments.edit', compact('appointment', 'clients', 'services', 'employees'));
    }

    /**
     * Met à jour un rendez-vous existant dans la base de données.
     * 
     * Cette méthode gère également l'attribution des points de fidélité :
     * si le statut passe à "completed", des points sont automatiquement
     * ajoutés au compte du client (1 point par tranche de 1000 GNF).
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données
     * @param  \App\Models\Appointment  $appointment Instance du rendez-vous à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validation identique à la création
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'client_id.required' => 'Le client est obligatoire.',
            'service_id.required' => 'Le service est obligatoire.',
            'employee_id.required' => 'L\'employé est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'time.required' => 'L\'heure est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
        ]);

        try {
            DB::transaction(function () use ($request, $appointment) {
                // Sauvegarde du statut précédent pour la logique de fidélité
                $previousStatus = $appointment->status;

                // Mise à jour des données du rendez-vous
                $appointment->update([
                    'client_id' => $request->client_id,
                    'service_id' => $request->service_id,
                    'employee_id' => $request->employee_id,
                    'scheduled_at' => $request->date . ' ' . $request->time,
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]);

                // ==============================================================
                // GESTION DES POINTS DE FIDÉLITÉ
                // Attribution automatique des points quand un RDV passe à "completed"
                // Formule : 1 point par tranche de 1000 GNF du prix du service
                // ==============================================================
                if ($request->status === 'completed' && $previousStatus !== 'completed' && $appointment->client && $appointment->service) {
                    $price = $appointment->service->getCurrentPrice();
                    $points = (int) floor($price / 1000);

                    if ($points > 0) {
                        $appointment->client->addLoyaltyPoints($points);
                    }
                }
            });

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Rendez-vous mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprime un rendez-vous de la base de données.
     * 
     * Effectue une suppression définitive (hard delete) du rendez-vous.
     * Note : Les relations sont automatiquement gérées par les contraintes
     * de clé étrangère définies en base de données.
     *
     * @param  \App\Models\Appointment  $appointment Instance du rendez-vous à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Rendez-vous supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer : ' . $e->getMessage());
        }
    }

    // ==========================================================================
    // SECTION DISPONIBILITÉ - VÉRIFICATION DES CRÉNEAUX
    // ==========================================================================

    /**
     * Retourne la liste des employés disponibles pour un créneau donné.
     * 
     * Cette méthode AJAX vérifie la disponibilité de chaque employé qualifié
     * pour le service demandé en analysant plusieurs critères :
     * 
     * 1. Horaires de travail hebdomadaires
     * 2. Congés approuvés
     * 3. Créneaux bloqués (individuels et globaux)
     * 4. Conflits avec d'autres rendez-vous
     * 
     * @param  \Illuminate\Http\Request  $request Requête contenant service_id, date et time
     * @return \Illuminate\Http\JsonResponse Liste des employés avec leur statut de disponibilité
     */
    public function getAvailableEmployees(Request $request)
    {
        // Validation des paramètres requis
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        // Récupération du service et calcul des horaires du créneau demandé
        $service = Service::findOrFail($request->service_id);
        $date = Carbon::parse($request->date);
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes($service->duration);
        $dayOfWeek = $date->dayOfWeek; // 0 = Dimanche, 6 = Samedi

        // ======================================================================
        // RÉCUPÉRATION DES EMPLOYÉS QUALIFIÉS
        // Priorité aux employés assignés au service, sinon tous les actifs
        // ======================================================================
        $qualifiedEmployees = $service->employees()->where('is_active', true)->get();
        
        if ($qualifiedEmployees->isEmpty()) {
            $qualifiedEmployees = Employee::where('is_active', true)->get();
        }

        $availableEmployees = [];

        // ======================================================================
        // ANALYSE DE LA DISPONIBILITÉ POUR CHAQUE EMPLOYÉ
        // ======================================================================
        foreach ($qualifiedEmployees as $employee) {
            $isAvailable = true;
            $reason = '';

            // ------------------------------------------------------------------
            // ÉTAPE 1 : Vérification des horaires de travail hebdomadaires
            // L'employé doit travailler ce jour et le créneau doit être dans ses heures
            // ------------------------------------------------------------------
            $schedule = EmployeeSchedule::where('employee_id', $employee->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_working', true)
                ->first();

            if (!$schedule) {
                $isAvailable = false;
                $reason = 'Ne travaille pas ce jour';
            } else {
                // Vérification que le créneau complet est dans les heures de travail
                $workStart = Carbon::parse($request->date . ' ' . $schedule->start_time);
                $workEnd = Carbon::parse($request->date . ' ' . $schedule->end_time);
                
                if ($startTime->lt($workStart) || $endTime->gt($workEnd)) {
                    $isAvailable = false;
                    $reason = 'Hors horaires';
                }
            }

            // ------------------------------------------------------------------
            // ÉTAPE 2 : Vérification des congés approuvés
            // Un employé en congé n'est pas disponible pour des rendez-vous
            // ------------------------------------------------------------------
            if ($isAvailable) {
                $onLeave = LeaveRequest::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->exists();

                if ($onLeave) {
                    $isAvailable = false;
                    $reason = 'En congé';
                }
            }

            // ------------------------------------------------------------------
            // ÉTAPE 3 : Vérification des créneaux bloqués
            // Inclut les blocages spécifiques à l'employé ET les blocages globaux
            // ------------------------------------------------------------------
            if ($isAvailable) {
                $blocked = BlockedSlot::where(function($q) use ($employee) {
                        $q->where('employee_id', $employee->id)
                          ->orWhereNull('employee_id'); // Blocages globaux
                    })
                    ->where('start_datetime', '<', $endTime)
                    ->where('end_datetime', '>', $startTime)
                    ->exists();

                if ($blocked) {
                    $isAvailable = false;
                    $reason = 'Créneau bloqué';
                }
            }

            // ------------------------------------------------------------------
            // ÉTAPE 4 : Vérification des conflits avec d'autres rendez-vous
            // Utilise une requête SQL brute pour le calcul de chevauchement
            // ------------------------------------------------------------------
            if ($isAvailable) {
                $hasConflict = Appointment::where('employee_id', $employee->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where('scheduled_at', '<', $endTime)
                    ->whereRaw("scheduled_at + (interval '1 minute' * (SELECT duration FROM services WHERE id = appointments.service_id)) > ?", [$startTime])
                    ->exists();

                if ($hasConflict) {
                    $isAvailable = false;
                    $reason = 'Déjà réservé';
                }
            }

            // Ajout de l'employé à la liste avec son statut
            $availableEmployees[] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'available' => $isAvailable,
                'reason' => $reason,
            ];
        }

        // Tri de la liste : employés disponibles en premier pour faciliter la sélection
        usort($availableEmployees, function($a, $b) {
            return $b['available'] <=> $a['available'];
        });

        return response()->json(['employees' => $availableEmployees]);
    }
}
