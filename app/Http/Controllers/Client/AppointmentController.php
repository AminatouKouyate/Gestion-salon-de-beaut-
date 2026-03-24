<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\ClientNotificationService;

/**
 * Contrôleur pour la gestion des rendez-vous côté client.
 * 
 * Ce contrôleur gère toutes les opérations liées aux rendez-vous pour les clients :
 * - Consultation de la liste des rendez-vous à venir
 * - Historique des rendez-vous passés
 * - Création, modification et annulation de rendez-vous
 * - Récupération des créneaux disponibles (API AJAX)
 * - Affichage du calendrier des rendez-vous
 * 
 * @package App\Http\Controllers\Client
 */
class AppointmentController extends Controller
{
    /**
     * Service de notification pour les clients.
     *
     * @var ClientNotificationService
     */
    protected ClientNotificationService $notificationService;

    /**
     * Constructeur du contrôleur.
     *
     * Injecte le service de notification qui sera utilisé pour envoyer
     * des confirmations par email/SMS lors de la création de rendez-vous.
     *
     * @param ClientNotificationService $notificationService Service de notification pour les clients
     */
    public function __construct(ClientNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Affiche la liste des rendez-vous à venir du client connecté.
     *
     * Récupère tous les rendez-vous futurs du client avec les informations
     * du service et de l'employé associés, triés par date croissante.
     *
     * @return \Illuminate\View\View La vue contenant la liste paginée des rendez-vous à venir
     */
    public function index()
    {
        // Récupérer le client actuellement connecté via le guard 'clients'
        $client = Auth::guard('clients')->user();
        
        // Charger les rendez-vous à venir avec eager loading des relations
        $appointments = Appointment::where('client_id', $client->id)
            ->with(['service', 'employee'])
            ->upcoming() // Scope pour filtrer les RDV futurs uniquement
            ->orderBy('scheduled_at')
            ->paginate(10);

        return view('clients.appointments.index', compact('appointments'));
    }

    /**
     * Affiche l'historique complet des rendez-vous du client.
     *
     * Récupère tous les rendez-vous (passés et futurs) du client avec
     * les informations complètes incluant le paiement associé.
     *
     * @return \Illuminate\View\View La vue contenant l'historique paginé des rendez-vous
     */
    public function history()
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Charger tous les rendez-vous avec leurs relations (service, employé, paiement)
        $appointments = Appointment::where('client_id', $client->id)
            ->with(['service', 'employee', 'payment'])
            ->orderBy('scheduled_at', 'desc') // Les plus récents en premier
            ->paginate(15);

        return view('clients.appointments.history', compact('appointments'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau rendez-vous.
     *
     * Cette méthode récupère la liste des services actifs et des employés
     * pour les afficher dans le formulaire. Si un service est passé en
     * paramètre URL (?service=ID), il sera pré-sélectionné dans le formulaire.
     *
     * @param Request $request La requête HTTP contenant éventuellement l'ID du service à pré-sélectionner
     * @return \Illuminate\View\View La vue du formulaire de création de rendez-vous
     */
    public function create(Request $request)
    {
        // Récupérer tous les services actifs pour le menu déroulant
        $services = Service::active()->get();
        
        // Récupérer tous les employés disponibles
        $employees = Employee::all();
        
        // Vérifier si un service est pré-sélectionné via l'URL (ex: ?service=5)
        $selectedServiceId = $request->get('service');
        $selectedService = $selectedServiceId ? Service::find($selectedServiceId) : null;

        return view('clients.appointments.create', compact('services', 'employees', 'selectedServiceId', 'selectedService'));
    }

    /**
     * Enregistre un nouveau rendez-vous dans la base de données.
     *
     * Cette méthode gère le processus complet de réservation :
     * 1. Valide les données du formulaire (service, date, heure)
     * 2. Vérifie la disponibilité de l'employé sélectionné
     * 3. Si aucun employé n'est choisi, en sélectionne un automatiquement
     * 4. Crée le rendez-vous avec le statut "pending"
     * 5. Envoie une notification de confirmation au client
     *
     * @param Request $request Les données du formulaire de réservation
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des rendez-vous avec message de succès ou d'erreur
     * @throws \Illuminate\Validation\ValidationException Si les données sont invalides
     */
    public function store(Request $request)
    {
        // Validation des champs obligatoires et optionnels du formulaire
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        // Récupérer le client connecté via le guard 'clients'
        $client = Auth::guard('clients')->user();

        // Construire la date/heure complète du rendez-vous au format "Y-m-d H:i"
        $scheduledAt = $request->date . ' ' . $request->time;
        if (!$scheduledAt) {
            return back()->with('error', 'Impossible de définir la date et l\'heure du rendez-vous.');
        }

        // Gestion de l'attribution de l'employé
        $employeeId = $request->employee_id;
        
        if (!$employeeId) {
            // ATTRIBUTION AUTOMATIQUE : aucun employé sélectionné par le client
            // Recherche en une seule requête SQL un employé qui :
            // - Peut effectuer le service demandé
            // - Est actif
            // - N'a pas de rendez-vous au même créneau horaire
            $employee = Employee::whereHas('services', function($q) use ($request) {
                    $q->where('services.id', $request->service_id);
                })
                ->where('is_active', true)
                ->whereDoesntHave('appointments', function($q) use ($scheduledAt) {
                    $q->where('scheduled_at', $scheduledAt)
                      ->whereIn('status', ['pending', 'confirmed']);
                })
                ->first();

            // Aucun employé disponible pour ce créneau horaire
            if (!$employee) {
                return back()->with('error', 'Aucun employé disponible pour ce créneau. Veuillez choisir un autre horaire.');
            }

            $employeeId = $employee->id;
        } else {
            // EMPLOYÉ SÉLECTIONNÉ : vérifier qu'il est disponible à ce créneau
            $existingAppointment = Appointment::where('employee_id', $employeeId)
                ->where('scheduled_at', $scheduledAt)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            // Conflit détecté : le créneau est déjà pris
            if ($existingAppointment) {
                return back()->with('error', 'Ce créneau est déjà réservé. Veuillez choisir un autre horaire.');
            }
        }

        // Création du rendez-vous en base de données avec statut initial "pending"
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'service_id' => $request->service_id,
            'employee_id' => $employeeId,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
            'notes' => $request->notes ?? '',
        ]);

        // Envoyer une notification de confirmation au client (email/SMS selon config)
        $this->notificationService->notifyAppointmentBooked($appointment);

        return redirect()->route('client.appointments.index')->with('success', 'Rendez-vous réservé avec succès');
    }

    /**
     * Affiche les détails d'un rendez-vous spécifique.
     *
     * Vérifie que le client connecté est bien le propriétaire du rendez-vous
     * avant d'afficher les informations détaillées.
     *
     * @param Appointment $appointment Le rendez-vous à afficher (injecté via Route Model Binding)
     * @return \Illuminate\View\View La vue des détails du rendez-vous
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le client n'est pas autorisé
     */
    public function show(Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété : le client ne peut voir que ses propres RDV
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Charger les relations pour afficher toutes les informations
        $appointment->load(['service', 'employee', 'payment']);

        return view('clients.appointments.show', compact('appointment'));
    }

    /**
     * Affiche le formulaire de modification d'un rendez-vous.
     *
     * Seuls les rendez-vous en attente ("pending") ou confirmés ("confirmed")
     * peuvent être modifiés. Les rendez-vous terminés ou annulés sont verrouillés.
     *
     * @param Appointment $appointment Le rendez-vous à modifier (injecté via Route Model Binding)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse La vue du formulaire ou redirection si non modifiable
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le client n'est pas autorisé
     */
    public function edit(Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier que le statut permet la modification
        // On gère à la fois les enums et les valeurs string
        if (!in_array($appointment->status->value ?? $appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Ce rendez-vous ne peut plus être modifié.');
        }

        // Charger les données pour le formulaire
        $services = Service::active()->get();
        $employees = Employee::where('is_active', true)->get();

        return view('clients.appointments.edit', compact('appointment', 'services', 'employees'));
    }

    /**
     * Met à jour un rendez-vous existant.
     *
     * Permet de modifier le service, l'employé, la date/heure et les notes
     * d'un rendez-vous appartenant au client connecté.
     *
     * @param Request $request Les données du formulaire de modification
     * @param Appointment $appointment Le rendez-vous à mettre à jour (injecté via Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le client n'est pas autorisé
     * @throws \Illuminate\Validation\ValidationException Si les données sont invalides
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Validation des données du formulaire
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Reconstruire la date/heure complète
        $scheduledAt = $request->date . ' ' . $request->time;

        // Gestion de l'attribution de l'employé
        $employeeId = $request->employee_id;

        if (!$employeeId) {
            $employee = Employee::whereHas('services', function($q) use ($request) {
                    $q->where('services.id', $request->service_id);
                })
                ->where('is_active', true)
                ->whereDoesntHave('appointments', function($q) use ($scheduledAt, $appointment) {
                    $q->where('scheduled_at', $scheduledAt)
                      ->whereIn('status', ['pending', 'confirmed'])
                      ->where('id', '!=', $appointment->id);
                })
                ->first();

            if (!$employee) {
                return back()->with('error', 'Aucun employé disponible pour ce créneau. Veuillez choisir un autre horaire.');
            }

            $employeeId = $employee->id;
        }

        // Mise à jour du rendez-vous
        $appointment->update([
            'service_id' => $request->service_id,
            'employee_id' => $employeeId,
            'scheduled_at' => $scheduledAt,
            'notes' => $request->notes,
        ]);

        return redirect()->route('client.appointments.show', $appointment)
            ->with('success', 'Rendez-vous modifié avec succès.');
    }

    /**
     * Annule un rendez-vous.
     *
     * Change le statut du rendez-vous à "canceled". Seuls les rendez-vous
     * en attente ou confirmés peuvent être annulés.
     *
     * @param Appointment $appointment Le rendez-vous à annuler (injecté via Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès ou d'erreur
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le client n'est pas autorisé
     */
    public function cancel(Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier que le statut permet l'annulation
        if (!in_array($appointment->status->value ?? $appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Ce rendez-vous ne peut plus être annulé.');
        }

        // Mettre à jour le statut
        $appointment->update(['status' => 'canceled']);

        return redirect()->route('client.appointments.index')
            ->with('success', 'Rendez-vous annulé avec succès.');
    }

    /**
     * Supprime définitivement un rendez-vous.
     *
     * Seuls les rendez-vous en attente ("pending") ou annulés ("canceled")
     * peuvent être supprimés. Les rendez-vous confirmés ou terminés sont protégés.
     *
     * @param Appointment $appointment Le rendez-vous à supprimer (injecté via Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès ou d'erreur
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le client n'est pas autorisé
     */
    public function destroy(Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier si le rendez-vous peut être supprimé
        if (!in_array($appointment->status->value ?? $appointment->status, ['pending', 'canceled'])) {
            return back()->with('error', 'Ce rendez-vous ne peut pas être supprimé car il est déjà confirmé ou terminé.');
        }

        // Suppression définitive du rendez-vous
        $appointment->delete();

        return redirect()->route('client.appointments.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }

    /**
     * Retourne les créneaux horaires disponibles pour une date et un service donnés.
     * 
     * Cette méthode API (AJAX) calcule les créneaux disponibles en tenant compte de :
     * - Horaires de travail des employés
     * - Pauses déjeuner et autres
     * - Congés approuvés
     * - Créneaux bloqués manuellement
     * - Rendez-vous déjà existants
     * - Durée du service demandé
     *
     * @param Request $request Requête contenant la date, le service et optionnellement l'employé
     * @return \Illuminate\Http\JsonResponse Liste des créneaux disponibles au format JSON
     * @throws \Illuminate\Validation\ValidationException Si les paramètres sont invalides
     */
    public function getAvailableSlots(Request $request)
    {
        // Validation des paramètres de la requête
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        // Parser la date et récupérer le service avec sa durée
        $date = \Carbon\Carbon::parse($request->date);
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration ?? 60; // Durée par défaut : 60 minutes

        $slots = [];

        if ($request->employee_id) {
            // CAS 1 : Un employé spécifique est demandé
            $employee = Employee::findOrFail($request->employee_id);
            
            // Vérifier que l'employé propose bien ce service
            if (!$employee->services()->where('services.id', $service->id)->exists()) {
                return response()->json(['slots' => [], 'message' => 'Cet employe ne propose pas ce service.']);
            }
            
            // Récupérer les créneaux disponibles pour cet employé
            $employeeSlots = $employee->getAvailableSlotsForDate($date, $duration);
            foreach ($employeeSlots as $slot) {
                $slots[] = [
                    'time' => $slot['start']->format('H:i'),
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                ];
            }
        } else {
            // CAS 2 : Tous les employés pouvant réaliser ce service
            $employees = Employee::where('is_active', true)
                ->whereHas('services', function($q) use ($service) {
                    $q->where('services.id', $service->id);
                })
                ->get();

            // Agréger les créneaux de tous les employés
            $allSlots = [];
            foreach ($employees as $employee) {
                $employeeSlots = $employee->getAvailableSlotsForDate($date, $duration);
                foreach ($employeeSlots as $slot) {
                    $time = $slot['start']->format('H:i');
                    if (!isset($allSlots[$time])) {
                        $allSlots[$time] = [];
                    }
                    $allSlots[$time][] = [
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->name,
                    ];
                }
            }

            // Trier les créneaux par ordre chronologique
            ksort($allSlots);
            
            // Formatter la réponse avec le nombre d'employés disponibles par créneau
            foreach ($allSlots as $time => $empList) {
                $slots[] = [
                    'time' => $time,
                    'employee_count' => count($empList),
                ];
            }
        }

        return response()->json(['slots' => $slots]);
    }

    /**
     * Retourne la liste des employés pouvant réaliser un service donné.
     *
     * Cette méthode API (AJAX) est utilisée pour mettre à jour dynamiquement
     * la liste des employés dans le formulaire de réservation.
     *
     * @param Request $request Requête contenant l'ID du service
     * @return \Illuminate\Http\JsonResponse Liste des employés au format JSON
     * @throws \Illuminate\Validation\ValidationException Si le service_id est invalide
     */
    public function getEmployeesForService(Request $request)
    {
        // Validation du paramètre
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        // Récupérer le service
        $service = Service::findOrFail($request->service_id);
        
        // Récupérer les employés actifs pouvant réaliser ce service
        $employees = Employee::where('is_active', true)
            ->whereHas('services', function($q) use ($service) {
                $q->where('services.id', $service->id);
            })
            ->get(['id', 'name', 'phone']);

        return response()->json(['employees' => $employees]);
    }

    /**
     * Affiche la vue calendrier des rendez-vous du client.
     *
     * @return \Illuminate\View\View La vue du calendrier (utilise FullCalendar côté frontend)
     */
    public function calendar()
    {
        return view('clients.appointments.calendar');
    }

    /**
     * Retourne les événements du calendrier pour une période donnée.
     *
     * Cette méthode API (AJAX) est appelée par FullCalendar pour charger
     * les rendez-vous à afficher dans le calendrier.
     *
     * @param Request $request Requête contenant les dates de début et fin de la période
     * @return \Illuminate\Http\JsonResponse Liste des événements au format FullCalendar
     */
    public function calendarEvents(Request $request)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Parser les dates de la période demandée par FullCalendar
        $start = \Carbon\Carbon::parse($request->get('start'));
        $end = \Carbon\Carbon::parse($request->get('end'));

        // Récupérer les rendez-vous de la période
        $appointments = Appointment::where('client_id', $client->id)
            ->whereBetween('scheduled_at', [$start, $end])
            ->with(['service', 'employee'])
            ->get();

        // Transformer les rendez-vous en événements FullCalendar
        $events = [];
        foreach ($appointments as $apt) {
            $duration = $apt->service->duration ?? 60;
            
            // Définir la couleur selon le statut du rendez-vous
            $color = match ($apt->status->value ?? $apt->status) {
                'pending' => '#ffc107',    // Jaune : en attente
                'confirmed' => '#17a2b8',  // Bleu : confirmé
                'completed' => '#28a745',  // Vert : terminé
                'canceled' => '#dc3545',  // Rouge : annulé
                default => '#6c757d',      // Gris : statut inconnu
            };

            // Construire l'objet événement au format FullCalendar
            $events[] = [
                'id' => $apt->id,
                'title' => $apt->service->name,
                'start' => $apt->scheduled_at->toIso8601String(),
                'end' => $apt->scheduled_at->copy()->addMinutes($duration)->toIso8601String(),
                'color' => $color,
                'extendedProps' => [
                    'service' => $apt->service->name,
                    'employee' => $apt->employee->name ?? 'Non assigné',
                    'status' => $apt->status->value ?? $apt->status,
                    'price' => $apt->service->getCurrentPrice(),
                ]
            ];
        }

        return response()->json($events);
    }
}
