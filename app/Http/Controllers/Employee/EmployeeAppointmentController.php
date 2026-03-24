<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour la gestion des rendez-vous côté employé.
 * 
 * Permet aux employés de consulter, filtrer et gérer leurs rendez-vous assignés,
 * incluant la vue calendrier, la mise à jour des statuts et l'ajout de notes.
 */
class EmployeeAppointmentController extends Controller
{
    /**
     * Affiche la liste des rendez-vous pour l'employé connecté.
     * 
     * Supporte plusieurs vues : à venir (upcoming), journalière (daily) et hebdomadaire (weekly).
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant le paramètre 'view'
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $employee = Auth::guard('employees')->user();
        $view = $request->get('view', 'upcoming');

        $query = $employee->appointments()
            ->with(['client', 'service', 'payment']);

        // Filtrage selon le type de vue demandé
        if ($view === 'daily') {
            $query->whereDate('scheduled_at', now()->toDateString());
        } elseif ($view === 'weekly') {
            $query->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            // Vue par défaut : rendez-vous à venir
            $query->where('scheduled_at', '>=', now());
        }

        $appointments = $query->orderBy('scheduled_at')
            ->paginate(15);

        return view('employee.appointments.index', compact('appointments', 'employee', 'view'));
    }

    /**
     * Affiche l'historique des rendez-vous passés de l'employé.
     *
     * Récupère les rendez-vous dont la date est antérieure à maintenant,
     * triés du plus récent au plus ancien.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $employee = Auth::guard('employees')->user();

        $appointments = $employee->appointments()
            ->with(['client', 'service', 'payment'])
            ->where('scheduled_at', '<', now())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('employee.appointments.history', compact('appointments', 'employee'));
    }

    /**
     * Affiche le calendrier interactif FullCalendar pour l'employé.
     *
     * @return \Illuminate\View\View
     */
    public function calendar()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.appointments.calendar', compact('employee'));
    }

    /**
     * Retourne les rendez-vous de l'employé formatés pour FullCalendar en JSON.
     * 
     * Chaque événement contient l'ID, le titre (client + service), les dates de début/fin,
     * l'URL de détail et le statut du rendez-vous.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function events(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $appointments = $employee->appointments()->with(['service', 'client'])->get();

        $events = $appointments->map(function ($apt) {
            $start = $apt->scheduled_at;
            $end = $start ? $start->copy() : null;

            // Calcul de la durée selon le service ou durée par défaut de 30 minutes
            if ($apt->service && isset($apt->service->duration)) {
                $end = $start->copy()->addMinutes((int)$apt->service->duration);
            } elseif ($end) {
                $end = $start->copy()->addMinutes(30);
            }

            return [
                'id' => $apt->id,
                'title' => trim(($apt->client?->name ? $apt->client->name . ' - ' : '') . ($apt->service?->name ?? 'Rendez-vous')),
                'start' => $start?->toIso8601String(),
                'end' => $end?->toIso8601String(),
                'url' => route('employee.appointments.show', $apt->id),
                'status' => $apt->status,
            ];
        });

        return response()->json($events);
    }

    /**
     * Affiche les détails d'un rendez-vous spécifique.
     * 
     * Vérifie que le rendez-vous appartient bien à l'employé connecté.
     *
     * @param  \App\Models\Appointment  $appointment  Le rendez-vous à afficher
     * @return \Illuminate\View\View
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function show(Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $appointment->load(['client', 'service', 'payment']);

        return view('employee.appointments.show', compact('appointment', 'employee'));
    }

    /**
     * Met à jour le statut d'un rendez-vous.
     * 
     * Statuts possibles : pending, confirmed, completed, canceled, no-show.
     *
     * @param  \Illuminate\Http\Request  $request     La requête contenant le nouveau statut
     * @param  \App\Models\Appointment   $appointment Le rendez-vous à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled,no-show',
        ]);

        $previousStatus = $appointment->status;

        $appointment->update(['status' => $request->status]);

        // Si le statut passe à completed (et n'était pas déjà completed), ajouter les points de fidélité
        if ($request->status === 'completed' && $previousStatus !== 'completed' && $appointment->client && $appointment->service) {
            $price = $appointment->service->getCurrentPrice();
            $points = (int) floor($price / 1000);
            if ($points > 0) {
                $appointment->client->addLoyaltyPoints($points);
            }
        }

        return back()->with('success', 'Statut du rendez-vous mis à jour avec succès.');
    }

    /**
     * Ajoute des notes internes sur un client/rendez-vous.
     * 
     * Les notes sont horodatées et signées par l'employé.
     *
     * @param  \Illuminate\Http\Request  $request     La requête contenant les notes
     * @param  \App\Models\Appointment   $appointment Le rendez-vous concerné
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function addNotes(Request $request, Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        // Concaténation de la nouvelle note avec horodatage et signature
        $currentNotes = $appointment->notes ?? '';
        $newNote = "\n[" . now()->format('d/m/Y H:i') . " - " . $employee->name . "]\n" . $request->notes;

        $appointment->update([
            'notes' => $currentNotes . $newNote
        ]);

        return back()->with('success', 'Note ajoutée avec succès.');
    }

    /**
     * Ajoute une note post-rendez-vous.
     * 
     * Similaire à addNotes mais utilise le champ 'note' au lieu de 'notes'.
     *
     * @param  \Illuminate\Http\Request  $request     La requête contenant la note
     * @param  \App\Models\Appointment   $appointment Le rendez-vous concerné
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function addNote(Request $request, Appointment $appointment)
    {
        $employee = Auth::guard('employees')->user();

        if ($appointment->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        // Concaténation de la nouvelle note avec horodatage et signature
        $currentNotes = $appointment->notes ?? '';
        $newNote = "\n[" . now()->format('d/m/Y H:i') . " - " . $employee->name . "]\n" . $request->note;

        $appointment->update([
            'notes' => $currentNotes . $newNote
        ]);

        return back()->with('success', 'Note ajoutée avec succès.');
    }
}