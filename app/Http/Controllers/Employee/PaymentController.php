<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur pour la gestion des paiements côté employé.
 * 
 * Permet aux employés de consulter les paiements liés à leurs rendez-vous,
 * de visualiser les rendez-vous terminés non encore payés et d'enregistrer
 * les paiements en espèces ou par carte bancaire.
 */
class PaymentController extends Controller
{
    /**
     * Affiche la liste des paiements liés aux rendez-vous de l'employé.
     * 
     * Récupère également les rendez-vous terminés sans paiement
     * pour permettre l'encaissement en espèces.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        // Récupération des paiements liés aux rendez-vous de cet employé avec pagination
        $payments = Payment::with(['client', 'appointment.service'])
            ->whereHas('appointment', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->latest()
            ->paginate(10);

        // Rendez-vous terminés sans paiement (pour encaissement espèces ou carte)
        $unpaidAppointments = Appointment::where('employee_id', $employee->id)
            ->where('status', AppointmentStatus::Completed)
            ->doesntHave('payment')
            ->with(['client', 'service'])
            ->latest()
            ->get();

        return view('employee.payments.index', compact('payments', 'unpaidAppointments'));
    }

    /**
     * Affiche les détails d'un paiement spécifique.
     * 
     * Vérifie que le paiement est bien lié à un rendez-vous de l'employé connecté.
     *
     * @param  \App\Models\Payment  $payment  Le paiement à afficher
     * @return \Illuminate\View\View
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function show(Payment $payment)
    {
        $employee = Auth::guard('employees')->user();

        // Vérification que le paiement est lié à un rendez-vous de l'employé connecté
        if (! $payment->appointment || $payment->appointment->employee_id !== $employee->id) {
            abort(403);
        }

        // Chargement des relations nécessaires pour l'affichage des détails
        $payment->load('appointment.service', 'client');

        return view('employee.payments.show', compact('payment'));
    }

    /**
     * Affiche le formulaire d'enregistrement d'un paiement (espèces ou carte).
     * 
     * Si un identifiant de rendez-vous est passé en paramètre, il est pré-sélectionné.
     * Sinon, la liste de tous les rendez-vous terminés non payés est affichée.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant éventuellement l'ID du rendez-vous
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        // Si un rendez-vous est spécifié, vérifier qu'il appartient à l'employé et qu'il est éligible
        $appointment = null;
        if ($request->has('appointment')) {
            $appointment = Appointment::where('id', $request->appointment)
                ->where('employee_id', $employee->id)
                ->where('status', AppointmentStatus::Completed)
                ->doesntHave('payment')
                ->with(['client', 'service'])
                ->first();

            if (!$appointment) {
                return back()->with('error', 'Rendez-vous introuvable ou déjà payé.');
            }
        }

        // Liste de tous les rendez-vous terminés non payés pour le menu déroulant
        $unpaidAppointments = Appointment::where('employee_id', $employee->id)
            ->where('status', AppointmentStatus::Completed)
            ->doesntHave('payment')
            ->with(['client', 'service'])
            ->latest()
            ->get();

        return view('employee.payments.create', compact('unpaidAppointments', 'appointment'));
    }

    /**
     * Enregistre un paiement (espèces ou carte) pour un rendez-vous terminé.
     * 
     * Vérifie que le rendez-vous appartient à l'employé connecté et qu'il n'a pas
     * déjà été payé. Le paiement est créé dans une transaction pour garantir l'intégrité.
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant l'ID du rendez-vous et le mode de paiement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire de paiement
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'method' => 'required|in:cash,card',
        ], [
            'appointment_id.required' => 'Veuillez sélectionner un rendez-vous.',
            'method.required' => 'Veuillez sélectionner un mode de paiement.',
        ]);

        $employee = Auth::guard('employees')->user();

        // Vérification que le rendez-vous appartient bien à cet employé
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('employee_id', $employee->id)
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Ce rendez-vous ne vous est pas assigné.');
        }

        // Vérification qu'aucun paiement n'existe déjà pour ce rendez-vous
        if ($appointment->payment) {
            return back()->with('error', 'Ce rendez-vous a déjà été payé.');
        }

        if (!$appointment->service) {
            return back()->with('error', 'Service introuvable pour ce rendez-vous.');
        }

        // Création du paiement dans une transaction pour garantir l'intégrité des données
        DB::transaction(function () use ($appointment, $request) {
            Payment::create([
                'client_id' => $appointment->client_id,
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->getCurrentPrice(),
                'method' => $request->method,
                'status' => 'paid',
            ]);
        });

        return redirect()->route('employee.payments.index')
            ->with('success', 'Paiement en ' . ($request->method === 'cash' ? 'espèces' : 'carte') . ' enregistré avec succès.');
    }
}
