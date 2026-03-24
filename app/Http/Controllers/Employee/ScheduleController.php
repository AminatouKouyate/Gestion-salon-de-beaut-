<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\EmployeeSchedule;
use App\Models\LeaveRequest;
use App\Models\BlockedSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Contrôleur pour la gestion du planning et des horaires des employés.
 *
 * Ce contrôleur permet aux employés de :
 * - Visualiser leur planning complet sous forme de calendrier interactif
 * - Consulter leurs horaires de travail hebdomadaires
 * - Voir leurs jours de congé approuvés (passés et à venir)
 * - Récupérer les événements (rendez-vous, congés, blocages) au format JSON pour FullCalendar
 *
 * @package App\Http\Controllers\Employee
 */
class ScheduleController extends Controller
{
    /**
     * Affiche le planning de l'employé connecté avec calendrier interactif.
     *
     * La vue par défaut est hebdomadaire mais peut être modifiée via le paramètre 'view'.
     * Le calendrier utilise FullCalendar pour l'affichage interactif.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant éventuellement le paramètre 'view'
     * @return \Illuminate\View\View  Vue du planning avec l'employé et le type de vue
     */
    public function index(Request $request)
    {
        // Récupération de l'employé authentifié via le guard dédié
        $employee = Auth::guard('employees')->user();

        // Type de vue : 'week' (par défaut), 'month', 'day'
        $view = $request->get('view', 'week');
        
        return view('employee.schedules.index', compact('employee', 'view'));
    }

    /**
     * Retourne les événements du planning au format JSON pour FullCalendar.
     *
     * Récupère et formate trois types d'événements :
     * 1. **Rendez-vous** : Tous les rendez-vous de l'employé dans la période demandée
     * 2. **Congés approuvés** : Les périodes de congé validées par l'administration
     * 3. **Créneaux bloqués** : Les indisponibilités ponctuelles (réunions, formations, etc.)
     *
     * Chaque événement est coloré selon son type ou son statut pour une lecture rapide.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant 'start' et 'end' (dates au format ISO)
     * @return \Illuminate\Http\JsonResponse  Tableau d'événements au format FullCalendar
     *
     * @example Réponse JSON pour un rendez-vous :
     * {
     *   "id": "apt-15",
     *   "title": "Marie Dupont - Coiffure",
     *   "start": "2024-01-15T10:00:00+00:00",
     *   "end": "2024-01-15T11:00:00+00:00",
     *   "color": "#17a2b8",
     *   "extendedProps": {
     *     "type": "appointment",
     *     "client": "Marie Dupont",
     *     "service": "Coiffure",
     *     "status": "Confirmé",
     *     "price": 15000
     *   }
     * }
     */
    public function getEvents(Request $request)
    {
        // Récupération de l'employé authentifié
        $employee = Auth::guard('employees')->user();

        // Parsing des dates de début et fin de la période affichée
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $events = [];

        // =============================================
        // SECTION 1 : Récupération des rendez-vous
        // =============================================
        // On récupère tous les rendez-vous de l'employé dans la période
        // avec eager loading des relations client et service pour optimiser les requêtes
        $appointments = $employee->appointments()
            ->whereBetween('scheduled_at', [$start, $end])
            ->with(['client', 'service'])
            ->get();

        foreach ($appointments as $apt) {
            // Durée par défaut de 60 minutes si le service n'en définit pas
            $duration = $apt->service->duration ?? 60;

            $events[] = [
                'id' => 'apt-' . $apt->id,  // Préfixe pour différencier des autres types
                'title' => $apt->client->name . ' - ' . $apt->service->name,
                'start' => $apt->scheduled_at->toIso8601String(),
                'end' => $apt->scheduled_at->copy()->addMinutes($duration)->toIso8601String(),
                'color' => $this->getStatusColor($apt->status->value ?? $apt->status),
                'extendedProps' => [
                    'type' => 'appointment',
                    'client' => $apt->client->name,
                    'service' => $apt->service->name,
                    'status' => $apt->status_label ?? $apt->status,
                    'price' => $apt->service->getCurrentPrice(),
                ]
            ];
        }

        // =============================================
        // SECTION 2 : Récupération des congés approuvés
        // =============================================
        // Les congés sont affichés comme événements sur toute la journée
        // On filtre pour inclure les congés qui chevauchent la période affichée
        $leaves = $employee->leaveRequests()
            ->where('status', 'approved')
            ->where(function($q) use ($start, $end) {
                // Cas 1 : Le congé commence dans la période affichée
                $q->whereBetween('start_date', [$start, $end])
                  // Cas 2 : Le congé se termine dans la période affichée
                  ->orWhereBetween('end_date', [$start, $end])
                  // Cas 3 : Le congé englobe toute la période affichée
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            })
            ->get();

        foreach ($leaves as $leave) {
            $events[] = [
                'id' => 'leave-' . $leave->id,
                'title' => 'Congé',
                'start' => $leave->start_date->toDateString(),
                // FullCalendar : la date de fin est exclusive, donc on ajoute un jour
                'end' => $leave->end_date->copy()->addDay()->toDateString(),
                'color' => '#ffc107',  // Jaune pour les congés
                'allDay' => true,      // Événement sur toute la journée
                'extendedProps' => [
                    'type' => 'leave',
                    'reason' => $leave->reason,
                ]
            ];
        }

        // =============================================
        // SECTION 3 : Récupération des créneaux bloqués
        // =============================================
        // Les blocages peuvent être spécifiques à l'employé ou globaux (employee_id = null)
        $blocks = BlockedSlot::where(function($q) use ($employee) {
                // Blocages spécifiques à cet employé OU blocages globaux
                $q->where('employee_id', $employee->id)
                  ->orWhereNull('employee_id');
            })
            ->where(function($q) use ($start, $end) {
                // Filtrage par période
                $q->whereBetween('start_datetime', [$start, $end])
                  ->orWhereBetween('end_datetime', [$start, $end]);
            })
            ->get();

        foreach ($blocks as $block) {
            $events[] = [
                'id' => 'block-' . $block->id,
                'title' => $block->reason ?? 'Indisponible',
                'start' => $block->start_datetime->toIso8601String(),
                'end' => $block->end_datetime->toIso8601String(),
                'color' => '#6c757d',  // Gris pour les blocages
                'extendedProps' => [
                    'type' => 'blocked',
                    'reason' => $block->reason,
                ]
            ];
        }

        return response()->json($events);
    }

    /**
     * Affiche les horaires de travail hebdomadaires de l'employé.
     *
     * Présente un récapitulatif des horaires de travail pour chaque jour de la semaine,
     * incluant les heures de début, de fin et les pauses éventuelles.
     *
     * @return \Illuminate\View\View  Vue avec les horaires indexés par jour de la semaine (0=Dimanche, 6=Samedi)
     */
    public function workingHours()
    {
        $employee = Auth::guard('employees')->user();

        // Récupération des horaires triés par jour et indexés par jour de la semaine
        // pour un accès facile dans la vue (ex: $schedules[1] pour Lundi)
        $schedules = $employee->schedules()->orderBy('day_of_week')->get()->keyBy('day_of_week');
        
        return view('employee.schedules.working-hours', compact('employee', 'schedules'));
    }

    /**
     * Affiche les jours de congé de l'employé (approuvés uniquement).
     *
     * Divise les congés en deux catégories :
     * - **À venir** : Congés dont la date de fin est dans le futur
     * - **Passés** : Les 10 derniers congés terminés (historique limité)
     *
     * @return \Illuminate\View\View  Vue avec les congés à venir et passés
     */
    public function daysOff()
    {
        $employee = Auth::guard('employees')->user();

        // Congés à venir : date de fin >= aujourd'hui, triés par date croissante
        $upcomingLeaves = $employee->leaveRequests()
            ->where('status', 'approved')
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();
        
        // Historique des congés passés : limité aux 10 plus récents
        $pastLeaves = $employee->leaveRequests()
            ->where('status', 'approved')
            ->where('end_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->take(10)
            ->get();
        
        return view('employee.schedules.days-off', compact('employee', 'upcomingLeaves', 'pastLeaves'));
    }

    /**
     * Retourne la couleur associée à un statut de rendez-vous.
     *
     * Utilisée pour colorer les événements du calendrier selon leur statut,
     * permettant une lecture visuelle rapide de l'état des rendez-vous.
     *
     * @param  string  $status  Le statut du rendez-vous (pending, confirmed, completed, canceled, no-show)
     * @return string  Code couleur hexadécimal correspondant au statut
     *
     * @example
     * getStatusColor('pending')   => '#ffc107' (jaune - en attente)
     * getStatusColor('confirmed') => '#17a2b8' (bleu info - confirmé)
     * getStatusColor('completed') => '#28a745' (vert - terminé)
     * getStatusColor('canceled')  => '#dc3545' (rouge - annulé)
     * getStatusColor('no-show')   => '#6c757d' (gris - absent)
     */
    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => '#ffc107',    // Jaune Bootstrap (warning)
            'confirmed' => '#17a2b8',  // Bleu Bootstrap (info)
            'completed' => '#28a745',  // Vert Bootstrap (success)
            'canceled' => '#dc3545',   // Rouge Bootstrap (danger)
            'no-show' => '#6c757d',    // Gris Bootstrap (secondary)
            default => '#007bff',      // Bleu Bootstrap (primary) par défaut
        };
    }
}
