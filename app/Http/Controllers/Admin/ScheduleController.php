<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BlockedSlot;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur de gestion des plannings pour le panneau d'administration.
 * 
 * Ce contrôleur centralise toutes les fonctionnalités liées à la planification :
 * - Visualisation du planning global et individuel des employés
 * - Gestion des horaires hebdomadaires de chaque employé
 * - Création et suppression des créneaux bloqués
 * - API JSON pour l'intégration avec FullCalendar
 * 
 * Le planning affiche une vue consolidée des :
 * - Rendez-vous (avec statut coloré)
 * - Congés approuvés
 * - Créneaux bloqués (individuels et globaux)
 * 
 * @package App\Http\Controllers\Admin
 * @author Système de gestion Salon de Beauté
 */
class ScheduleController extends Controller
{
    // ==========================================================================
    // SECTION AFFICHAGE - VUES DU PLANNING
    // ==========================================================================

    /**
     * Affiche le planning global du salon.
     * 
     * Présente une vue d'ensemble de tous les employés actifs avec :
     * - Leurs horaires de travail hebdomadaires
     * - Leurs rendez-vous sur la période sélectionnée
     * - Leurs congés approuvés
     * - Les créneaux bloqués (individuels et globaux)
     * 
     * Modes de vue disponibles :
     * - 'day' : Vue journalière
     * - 'week' : Vue hebdomadaire (par défaut)
     * - 'month' : Vue mensuelle
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les paramètres view et date
     * @return \Illuminate\View\View Vue du planning global
     */
    public function index(Request $request)
    {
        // ======================================================================
        // PARAMÈTRES DE NAVIGATION
        // ======================================================================
        
        // Type de vue : jour, semaine (défaut) ou mois
        $view = $request->input('view', 'week');
        
        // Date de référence : aujourd'hui par défaut
        $date = $request->input('date') 
            ? Carbon::parse($request->input('date')) 
            : Carbon::now();

        // ======================================================================
        // CALCUL DES BORNES DE LA PÉRIODE
        // Définit startDate et endDate selon le type de vue choisi
        // ======================================================================
        switch ($view) {
            case 'day':
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                break;
            case 'month':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                break;
            case 'week':
            default:
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                break;
        }

        // ======================================================================
        // RÉCUPÉRATION DES EMPLOYÉS AVEC LEURS DONNÉES DE PLANNING
        // Chargement optimisé avec eager loading conditionnel
        // ======================================================================
        $employees = Employee::where('is_active', true)
            ->with([
                // Horaires de travail hebdomadaires (7 jours)
                'schedules',
                
                // Rendez-vous sur la période sélectionnée
                'appointments' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('scheduled_at', [$startDate, $endDate])
                          ->with(['client', 'service']);
                },
                
                // Congés approuvés qui chevauchent la période
                'leaveRequests' => function ($query) use ($startDate, $endDate) {
                    $query->approved()
                          ->where(function ($q) use ($startDate, $endDate) {
                              // Congé commence pendant la période
                              $q->whereBetween('start_date', [$startDate, $endDate])
                                // OU congé finit pendant la période
                                ->orWhereBetween('end_date', [$startDate, $endDate])
                                // OU congé englobe toute la période
                                ->orWhere(function ($q2) use ($startDate, $endDate) {
                                    $q2->where('start_date', '<=', $startDate)
                                       ->where('end_date', '>=', $endDate);
                                });
                          });
                },
                
                // Créneaux bloqués sur la période
                'blockedSlots' => function ($query) use ($startDate, $endDate) {
                    $query->betweenDates($startDate, $endDate);
                },
            ])
            ->orderBy('name')
            ->get();

        // Récupération des blocages globaux (sans employé spécifique)
        // Ces blocages s'appliquent à tout le salon
        $globalBlockedSlots = BlockedSlot::global()
            ->betweenDates($startDate, $endDate)
            ->get();

        return view('admin.schedules.index', compact(
            'employees',
            'view',
            'date',
            'startDate',
            'endDate',
            'globalBlockedSlots'
        ));
    }

    /**
     * Affiche le planning détaillé d'un employé spécifique.
     * 
     * Présente une vue complète incluant :
     * - Horaires de travail pour chaque jour de la semaine
     * - Liste des congés approuvés à venir
     * - Créneaux bloqués actifs
     *
     * @param  int  $employeeId Identifiant de l'employé
     * @return \Illuminate\View\View Vue du planning individuel
     */
    public function employeeSchedule($employeeId)
    {
        // ======================================================================
        // RÉCUPÉRATION DE L'EMPLOYÉ AVEC SES DONNÉES DE PLANNING
        // ======================================================================
        $employee = Employee::with([
            // Horaires triés par jour de la semaine (0=Dim -> 6=Sam)
            'schedules' => function ($query) {
                $query->orderBy('day_of_week');
            },
            // Congés approuvés futurs uniquement
            'leaveRequests' => function ($query) {
                $query->approved()
                      ->where('end_date', '>=', now())
                      ->orderBy('start_date');
            },
            // Créneaux bloqués actifs (futurs)
            'blockedSlots' => function ($query) {
                $query->active()
                      ->orderBy('start_datetime');
            },
        ])->findOrFail($employeeId);

        // Jours de la semaine en français pour l'affichage
        $daysOfWeek = EmployeeSchedule::DAYS_FR;

        // Indexation des horaires par jour pour accès rapide dans la vue
        $schedulesByDay = $employee->schedules->keyBy('day_of_week');

        return view('admin.schedules.employee', compact(
            'employee',
            'daysOfWeek',
            'schedulesByDay'
        ));
    }

    // ==========================================================================
    // SECTION HORAIRES - GESTION DES HORAIRES HEBDOMADAIRES
    // ==========================================================================

    /**
     * Met à jour les horaires hebdomadaires d'un employé.
     * 
     * Permet de définir pour chaque jour de la semaine :
     * - Si l'employé travaille ce jour (is_working)
     * - Heure de début et fin de travail
     * - Pause déjeuner optionnelle (break_start, break_end)
     * 
     * Utilise updateOrCreate pour créer ou mettre à jour chaque jour.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les horaires
     * @param  int  $employeeId Identifiant de l'employé
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     */
    public function updateSchedule(Request $request, $employeeId)
    {
        // Vérification que l'employé existe
        $employee = Employee::findOrFail($employeeId);

        // ======================================================================
        // VALIDATION DES HORAIRES
        // Règles conditionnelles selon si l'employé travaille ce jour
        // ======================================================================
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.is_working' => 'nullable|boolean',
            'schedules.*.start_time' => 'required_if:schedules.*.is_working,1|nullable|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.is_working,1|nullable|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.break_start' => 'nullable|date_format:H:i',
            'schedules.*.break_end' => 'nullable|date_format:H:i|after:schedules.*.break_start',
        ], [
            'schedules.*.start_time.required_if' => 'L\'heure de début est obligatoire pour les jours travaillés.',
            'schedules.*.end_time.required_if' => 'L\'heure de fin est obligatoire pour les jours travaillés.',
            'schedules.*.end_time.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'schedules.*.break_end.after' => 'La fin de pause doit être après le début de pause.',
        ]);

        // ======================================================================
        // CRÉATION OU MISE À JOUR DES HORAIRES POUR CHAQUE JOUR
        // ======================================================================
        foreach ($request->input('schedules') as $scheduleData) {
            $isWorking = !empty($scheduleData['is_working']);

            EmployeeSchedule::updateOrCreate(
                [
                    // Clés de recherche (critères d'unicité)
                    'employee_id' => $employee->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                ],
                [
                    // Valeurs à créer/mettre à jour
                    'is_working' => $isWorking,
                    // Les horaires sont null si l'employé ne travaille pas ce jour
                    'start_time' => $isWorking ? $scheduleData['start_time'] : null,
                    'end_time' => $isWorking ? $scheduleData['end_time'] : null,
                    'break_start' => $isWorking ? ($scheduleData['break_start'] ?? null) : null,
                    'break_end' => $isWorking ? ($scheduleData['break_end'] ?? null) : null,
                ]
            );
        }

        return redirect()
            ->route('admin.schedules.employee', $employee->id)
            ->with('success', 'Horaires de ' . $employee->name . ' mis à jour avec succès.');
    }

    // ==========================================================================
    // SECTION BLOCAGES - GESTION DES CRÉNEAUX BLOQUÉS
    // ==========================================================================

    /**
     * Crée un nouveau créneau bloqué.
     * 
     * Permet de bloquer un créneau horaire pour :
     * - Un employé spécifique (ex: formation, absence imprévue)
     * - Globalement pour tout le salon (ex: fermeture exceptionnelle)
     * 
     * La méthode vérifie qu'aucun rendez-vous existant ne conflicte
     * avec le créneau à bloquer avant de le créer.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du blocage
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès ou erreur
     */
    public function storeBlockedSlot(Request $request)
    {
        // ======================================================================
        // VALIDATION DES DONNÉES DU BLOCAGE
        // ======================================================================
        $request->validate([
            'employee_id' => 'nullable|exists:employees,id', // Null = blocage global
            'start_datetime' => 'required|date|after_or_equal:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'reason' => 'nullable|string|max:500',
        ], [
            'start_datetime.required' => 'La date/heure de début est obligatoire.',
            'start_datetime.after_or_equal' => 'La date/heure de début doit être dans le futur.',
            'end_datetime.required' => 'La date/heure de fin est obligatoire.',
            'end_datetime.after' => 'La date/heure de fin doit être après la date/heure de début.',
            'reason.max' => 'La raison ne peut pas dépasser 500 caractères.',
        ]);

        $startDatetime = Carbon::parse($request->input('start_datetime'));
        $endDatetime = Carbon::parse($request->input('end_datetime'));
        $employeeId = $request->input('employee_id');

        // ======================================================================
        // VÉRIFICATION DES CONFLITS AVEC DES RENDEZ-VOUS EXISTANTS
        // On ne peut pas bloquer un créneau qui a déjà des RDV
        // ======================================================================
        $conflictQuery = Appointment::where('scheduled_at', '>=', $startDatetime)
            ->where('scheduled_at', '<', $endDatetime)
            ->whereNotIn('status', ['canceled']);

        // Si blocage spécifique à un employé, filtrer par cet employé
        if ($employeeId) {
            $conflictQuery->where('employee_id', $employeeId);
        }

        $conflictingAppointments = $conflictQuery->with(['client', 'service'])->get();

        // Si des conflits existent, renvoyer une erreur avec la liste des RDV
        if ($conflictingAppointments->isNotEmpty()) {
            $appointmentsList = $conflictingAppointments->map(function ($apt) {
                return $apt->scheduled_at->format('d/m/Y H:i') . ' - ' . ($apt->client->name ?? 'Client');
            })->join(', ');

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'conflict' => 'Impossible de bloquer ce créneau. Des rendez-vous existent : ' . $appointmentsList
                ]);
        }

        // ======================================================================
        // CRÉATION DU CRÉNEAU BLOQUÉ
        // ======================================================================
        BlockedSlot::create([
            'employee_id' => $employeeId, // Null pour blocage global
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'reason' => $request->input('reason'),
            'created_by' => Auth::guard('web')->id(), // ID de l'admin qui crée le blocage
        ]);

        // Message adapté selon le type de blocage
        $message = $employeeId 
            ? 'Créneau bloqué avec succès pour l\'employé.'
            : 'Créneau bloqué globalement avec succès.';

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Supprime un créneau bloqué existant.
     * 
     * Libère le créneau pour permettre à nouveau les réservations.
     *
     * @param  int  $id Identifiant du créneau bloqué
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     */
    public function destroyBlockedSlot($id)
    {
        $blockedSlot = BlockedSlot::findOrFail($id);
        $blockedSlot->delete();

        return redirect()
            ->back()
            ->with('success', 'Créneau bloqué supprimé avec succès.');
    }

    // ==========================================================================
    // SECTION API - DONNÉES JSON POUR FULLCALENDAR
    // ==========================================================================

    /**
     * Retourne les événements au format JSON pour FullCalendar.
     * 
     * Cette API agrège trois types d'événements pour l'affichage
     * dans le calendrier interactif :
     * 
     * 1. RENDEZ-VOUS : Avec couleur selon le statut
     *    - Jaune (#ffc107) : En attente
     *    - Bleu (#17a2b8) : Confirmé
     *    - Vert (#28a745) : Terminé
     *    - Rouge (#dc3545) : Annulé
     *    - Gris (#6c757d) : Absent (no-show)
     * 
     * 2. CONGÉS : En rouge clair (#ff6b6b), événements sur journée entière
     * 
     * 3. CRÉNEAUX BLOQUÉS : En gris (#868e96)
     * 
     * Chaque événement inclut des propriétés étendues (extendedProps)
     * pour afficher des détails dans les popups/modales.
     *
     * @param  \Illuminate\Http\Request  $request Requête avec filtres (employee_id, start, end)
     * @return \Illuminate\Http\JsonResponse Liste des événements au format FullCalendar
     */
    public function getEvents(Request $request)
    {
        // ======================================================================
        // PARAMÈTRES DE FILTRAGE
        // ======================================================================
        $employeeId = $request->input('employee_id'); // Optionnel : filtrer par employé
        $start = $request->input('start') 
            ? Carbon::parse($request->input('start')) 
            : Carbon::now()->startOfMonth();
        $end = $request->input('end') 
            ? Carbon::parse($request->input('end')) 
            : Carbon::now()->endOfMonth();

        $events = [];

        // ======================================================================
        // 1. RÉCUPÉRATION DES RENDEZ-VOUS
        // ======================================================================
        $appointmentsQuery = Appointment::with(['client', 'service', 'employee'])
            ->whereBetween('scheduled_at', [$start, $end]);

        if ($employeeId) {
            $appointmentsQuery->where('employee_id', $employeeId);
        }

        $appointments = $appointmentsQuery->get();

        foreach ($appointments as $appointment) {
            // Calcul de l'heure de fin basée sur la durée du service
            $duration = $appointment->service->duration ?? 30;
            $endTime = $appointment->scheduled_at->copy()->addMinutes($duration);

            // Attribution de la couleur selon le statut
            $color = match ($appointment->status->value ?? $appointment->status) {
                'pending' => '#ffc107',    // Jaune - En attente
                'confirmed' => '#17a2b8',  // Bleu - Confirmé
                'completed' => '#28a745',  // Vert - Terminé
                'canceled' => '#dc3545',  // Rouge - Annulé
                'no_show' => '#6c757d',    // Gris - Absent
                default => '#6c757d',
            };

            $events[] = [
                'id' => 'appointment-' . $appointment->id,
                'title' => ($appointment->client->name ?? 'Client') . ' - ' . ($appointment->service->name ?? 'Service'),
                'start' => $appointment->scheduled_at->toIso8601String(),
                'end' => $endTime->toIso8601String(),
                'color' => $color,
                'type' => 'appointment',
                'resourceId' => $appointment->employee_id,
                'extendedProps' => [
                    'appointment_id' => $appointment->id,
                    'client_name' => $appointment->client->name ?? '',
                    'service_name' => $appointment->service->name ?? '',
                    'employee_name' => $appointment->employee->name ?? '',
                    'status' => $appointment->status_label ?? $appointment->status->value ?? $appointment->status,
                    'notes' => $appointment->notes,
                ],
            ];
        }

        // ======================================================================
        // 2. RÉCUPÉRATION DES CONGÉS APPROUVÉS
        // Les congés sont des événements sur journée entière (allDay: true)
        // ======================================================================
        $leaveQuery = LeaveRequest::with('employee')
            ->approved()
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            });

        if ($employeeId) {
            $leaveQuery->where('employee_id', $employeeId);
        }

        $leaveRequests = $leaveQuery->get();

        foreach ($leaveRequests as $leave) {
            $events[] = [
                'id' => 'leave-' . $leave->id,
                'title' => 'Congé - ' . ($leave->employee->name ?? 'Employé'),
                'start' => $leave->start_date->toDateString(),
                // FullCalendar : end date est exclusive, donc +1 jour
                'end' => $leave->end_date->copy()->addDay()->toDateString(),
                'color' => '#ff6b6b',
                'allDay' => true,
                'type' => 'leave',
                'resourceId' => $leave->employee_id,
                'extendedProps' => [
                    'leave_id' => $leave->id,
                    'employee_name' => $leave->employee->name ?? '',
                    'reason' => $leave->reason,
                ],
            ];
        }

        // ======================================================================
        // 3. RÉCUPÉRATION DES CRÉNEAUX BLOQUÉS
        // Inclut les blocages individuels ET globaux
        // ======================================================================
        $blockedQuery = BlockedSlot::with('employee')
            ->betweenDates($start, $end);

        if ($employeeId) {
            // Pour un employé spécifique : ses blocages + les blocages globaux
            $blockedQuery->where(function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId)
                  ->orWhereNull('employee_id');
            });
        }

        $blockedSlots = $blockedQuery->get();

        foreach ($blockedSlots as $blocked) {
            // Titre adapté selon le type de blocage
            $title = $blocked->is_global 
                ? 'Blocage global' 
                : 'Bloqué - ' . ($blocked->employee->name ?? 'Employé');

            if ($blocked->reason) {
                $title .= ' (' . $blocked->reason . ')';
            }

            $events[] = [
                'id' => 'blocked-' . $blocked->id,
                'title' => $title,
                'start' => $blocked->start_datetime->toIso8601String(),
                'end' => $blocked->end_datetime->toIso8601String(),
                'color' => '#868e96',
                'type' => 'blocked',
                'resourceId' => $blocked->employee_id,
                'extendedProps' => [
                    'blocked_id' => $blocked->id,
                    'employee_name' => $blocked->employee->name ?? '',
                    'reason' => $blocked->reason,
                    'is_global' => $blocked->is_global,
                ],
            ];
        }

        return response()->json($events);
    }
}
