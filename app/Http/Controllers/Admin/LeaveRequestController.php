<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\EmployeeNotification;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des demandes de congé depuis le panneau d'administration.
 * 
 * Ce contrôleur permet à l'administrateur de :
 * - Consulter et filtrer les demandes de congé des employés
 * - Voir le détail d'une demande spécifique
 * - Approuver ou refuser les demandes en attente
 * - Notifier automatiquement l'employé de la décision prise
 * 
 * Chaque action (approbation/refus) génère une notification
 * dans l'espace de l'employé concerné via le modèle EmployeeNotification.
 * 
 * @package App\Http\Controllers\Admin
 */
class LeaveRequestController extends Controller
{
    /**
     * Affiche la liste paginée des demandes de congé avec filtrage par statut.
     * 
     * Les demandes sont triées du plus récent au plus ancien.
     * Un filtre optionnel par statut peut être appliqué via le paramètre GET 'status'.
     * Des compteurs par statut sont calculés pour l'affichage des badges dans l'interface.
     *
     * @param  \Illuminate\Http\Request  $request Requête pouvant contenir un filtre 'status'
     * @return \Illuminate\View\View Vue de la liste des demandes de congé
     */
    public function index(Request $request)
    {
        // Chargement des demandes avec la relation employé (évite le N+1)
        $query = LeaveRequest::with('employee')->orderBy('created_at', 'desc');

        // Application du filtre par statut si spécifié ('pending', 'approved', 'rejected')
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->paginate(15);

        // Calcul des compteurs par statut pour les badges de l'interface
        $pendingCount = LeaveRequest::pending()->count();
        $approvedCount = LeaveRequest::approved()->count();
        $rejectedCount = LeaveRequest::rejected()->count();

        return view('admin.leaves.index', compact(
            'leaveRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Affiche le détail d'une demande de congé spécifique.
     * 
     * Charge la relation employé pour afficher les informations
     * du demandeur dans la vue détaillée.
     *
     * @param  \App\Models\LeaveRequest  $leave Instance de la demande de congé à afficher
     * @return \Illuminate\View\View Vue du détail de la demande de congé
     */
    public function show(LeaveRequest $leave)
    {
        // Chargement eager de l'employé demandeur
        $leave->load('employee');
        return view('admin.leaves.show', compact('leave'));
    }

    /**
     * Approuve une demande de congé en attente.
     * 
     * Cette méthode :
     * - Vérifie que la demande est bien en statut 'pending'
     * - Met à jour le statut à 'approved' avec la réponse optionnelle de l'admin
     * - Envoie une notification de type 'success' à l'employé concerné
     *
     * @param  \Illuminate\Http\Request  $request Requête pouvant contenir 'admin_response'
     * @param  \App\Models\LeaveRequest  $leave Instance de la demande à approuver
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de confirmation
     */
    public function approve(Request $request, LeaveRequest $leave)
    {
        // Garde-fou : empêche le traitement d'une demande déjà traitée
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // Mise à jour du statut et enregistrement de la réponse de l'administrateur
        $leave->update([
            'status' => 'approved',
            'admin_response' => $request->input('admin_response'),
            'responded_at' => now(),
        ]);

        // Notification automatique à l'employé avec les dates de congé
        EmployeeNotification::create([
            'employee_id' => $leave->employee_id,
            'title' => 'Demande de congé approuvée',
            'message' => 'Votre demande de congé du ' . $leave->start_date->format('d/m/Y') . ' au ' . $leave->end_date->format('d/m/Y') . ' a été approuvée.',
            'type' => 'success',
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Demande de congé approuvée avec succès.');
    }

    /**
     * Refuse une demande de congé en attente.
     * 
     * Cette méthode :
     * - Vérifie que la demande est bien en statut 'pending'
     * - Exige un motif de refus obligatoire (max 500 caractères)
     * - Met à jour le statut à 'rejected' avec le motif
     * - Envoie une notification de type 'warning' à l'employé avec le motif du refus
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant obligatoirement 'admin_response'
     * @param  \App\Models\LeaveRequest  $leave Instance de la demande à refuser
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de confirmation
     */
    public function reject(Request $request, LeaveRequest $leave)
    {
        // Garde-fou : empêche le traitement d'une demande déjà traitée
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // Le motif de refus est obligatoire pour informer l'employé
        $request->validate([
            'admin_response' => 'required|string|max:500',
        ]);

        // Mise à jour du statut et enregistrement du motif de refus
        $leave->update([
            'status' => 'rejected',
            'admin_response' => $request->input('admin_response'),
            'responded_at' => now(),
        ]);

        // Notification automatique à l'employé avec le motif du refus
        EmployeeNotification::create([
            'employee_id' => $leave->employee_id,
            'title' => 'Demande de congé refusée',
            'message' => 'Votre demande de congé du ' . $leave->start_date->format('d/m/Y') . ' au ' . $leave->end_date->format('d/m/Y') . ' a été refusée. Motif : ' . $request->admin_response,
            'type' => 'warning',
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Demande de congé refusée.');
    }
}
