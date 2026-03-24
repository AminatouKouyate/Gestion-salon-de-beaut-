<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des paiements pour le panneau d'administration.
 * 
 * Ce contrôleur gère l'ensemble des opérations CRUD pour les paiements
 * du salon de beauté. Chaque paiement est associé à un client et à un
 * rendez-vous. Les statuts possibles sont 'pending' (en attente) et 'paid' (payé).
 * 
 * @package App\Http\Controllers\Admin
 */
class PaymentController extends Controller
{
    /**
     * Affiche la liste paginée de tous les paiements.
     * 
     * Récupère les paiements avec leurs relations (client, rendez-vous)
     * triés du plus récent au plus ancien, avec pagination de 10 par page.
     *
     * @return \Illuminate\View\View Vue de la liste des paiements
     */
    public function index()
    {
        // Chargement eager des relations client et rendez-vous pour éviter le N+1
        $payments = Payment::with(['client','appointment'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau paiement.
     * 
     * Prépare les listes déroulantes nécessaires :
     * - Clients triés par nom (id et nom uniquement pour optimiser la requête)
     * - Rendez-vous triés par date décroissante
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Récupération optimisée : seuls les champs nécessaires aux listes déroulantes
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $appointments = Appointment::orderBy('scheduled_at', 'desc')->get(['id', 'scheduled_at']);
        return view('admin.payments.create', compact('clients', 'appointments'));
    }

    /**
     * Enregistre un nouveau paiement dans la base de données.
     * 
     * Valide les données du formulaire avec des messages d'erreur
     * personnalisés en français, puis crée le paiement.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Validation des données avec messages d'erreur en français
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,paid,completed,failed,canceled',
            'method' => 'nullable|string|max:50',
        ], [
            'client_id.required' => 'Le client est obligatoire.',
            'appointment_id.required' => 'Le rendez-vous est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être valide.',
        ]);

        // Création du paiement avec les données validées
        Payment::create($request->only(['client_id', 'appointment_id', 'amount', 'status', 'method']));

        return redirect()->route('admin.payments.index')->with('success', 'Paiement ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un paiement.
     * 
     * Charge le paiement avec ses relations (client, rendez-vous avec service et employé)
     * et affiche la vue de détail.
     *
     * @param  \App\Models\Payment  $payment Instance du paiement à afficher
     * @return \Illuminate\View\View Vue de détail du paiement
     */
    public function show(Payment $payment)
    {
        $payment->load(['client', 'appointment.service', 'appointment.employee']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Affiche le formulaire de modification d'un paiement existant.
     * 
     * Charge le paiement à modifier ainsi que les listes de clients
     * et rendez-vous pour les listes déroulantes du formulaire.
     *
     * @param  \App\Models\Payment  $payment Instance du paiement à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Payment $payment)
    {
        // Récupération des données pour les listes déroulantes
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $appointments = Appointment::orderBy('scheduled_at', 'desc')->get(['id', 'scheduled_at']);
        return view('admin.payments.edit', compact('payment', 'clients', 'appointments'));
    }

    /**
     * Met à jour un paiement existant dans la base de données.
     * 
     * Valide les données avec les mêmes règles que la création,
     * puis met à jour le paiement correspondant.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant les nouvelles données
     * @param  \App\Models\Payment  $payment Instance du paiement à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Payment $payment)
    {
        // Validation identique à la création
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,paid,completed,failed,canceled',
            'method' => 'nullable|string|max:50',
        ], [
            'client_id.required' => 'Le client est obligatoire.',
            'appointment_id.required' => 'Le rendez-vous est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être valide.',
        ]);

        // Mise à jour du paiement avec les données validées
        $payment->update($request->only(['client_id', 'appointment_id', 'amount', 'status', 'method']));

        return redirect()->route('admin.payments.index')->with('success', 'Paiement mis à jour.');
    }

    /**
     * Met à jour le statut d'un paiement.
     * 
     * Permet de modifier uniquement le statut d'un paiement existant
     * sans toucher aux autres champs. Les statuts possibles sont :
     * pending, processing, paid, completed, failed, canceled.
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant le nouveau 'status'
     * @param  \App\Models\Payment  $payment Instance du paiement à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers le détail du paiement avec message de succès
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        // Validation du statut avec les valeurs autorisées
        $request->validate([
            'status' => 'required|in:pending,processing,paid,completed,failed,canceled',
        ], [
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné est invalide.',
        ]);

        // Mise à jour du statut uniquement
        $payment->update(['status' => $request->status]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Statut du paiement mis à jour avec succès.');
    }

    /**
     * Supprime un paiement de la base de données.
     * 
     * Effectue une suppression définitive du paiement.
     *
     * @param  \App\Models\Payment  $payment Instance du paiement à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Paiement supprimé.');
    }
}
