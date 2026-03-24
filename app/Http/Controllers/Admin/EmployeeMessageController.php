<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeMessage;
use App\Models\EmployeeNotification;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion de la messagerie interne entre l'administration et les employés.
 * 
 * Ce contrôleur permet à l'administrateur de :
 * - Consulter la liste des messages envoyés par les employés
 * - Filtrer les messages par statut (en attente, répondu)
 * - Lire le détail d'un message spécifique
 * - Répondre aux messages avec notification automatique à l'employé
 * 
 * @package App\Http\Controllers\Admin
 */
class EmployeeMessageController extends Controller
{
    /**
     * Affiche la liste paginée des messages envoyés par les employés.
     * 
     * Les messages sont triés du plus récent au plus ancien et peuvent
     * être filtrés par statut via un paramètre GET. Des compteurs
     * par statut sont également calculés pour l'affichage des badges.
     *
     * @param  \Illuminate\Http\Request  $request Requête pouvant contenir un filtre 'status'
     * @return \Illuminate\View\View Vue de la liste des messages avec compteurs
     */
    public function index(Request $request)
    {
        // Chargement des messages avec la relation employé pour éviter le N+1
        $query = EmployeeMessage::with('employee')->orderBy('created_at', 'desc');

        // Application du filtre par statut si spécifié (ex: 'pending', 'answered')
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(15);

        // Calcul des compteurs pour les badges de l'interface
        $pendingCount = EmployeeMessage::pending()->count();
        $answeredCount = EmployeeMessage::answered()->count();

        return view('admin.employee-messages.index', compact('messages', 'pendingCount', 'answeredCount'));
    }

    /**
     * Affiche le détail d'un message spécifique envoyé par un employé.
     * 
     * Charge la relation employé pour afficher les informations
     * de l'expéditeur dans la vue détaillée.
     *
     * @param  \App\Models\EmployeeMessage  $message Instance du message à afficher
     * @return \Illuminate\View\View Vue du détail du message
     */
    public function show(EmployeeMessage $message)
    {
        // Chargement eager de l'employé expéditeur
        $message->load('employee');
        return view('admin.employee-messages.show', compact('message'));
    }

    /**
     * Enregistre la réponse de l'administrateur à un message d'employé.
     * 
     * Cette méthode :
     * - Valide la réponse (obligatoire, max 2000 caractères)
     * - Met à jour le message avec la réponse et le statut 'answered'
     * - Crée une notification automatique pour informer l'employé
     *
     * @param  \Illuminate\Http\Request  $request Requête contenant la réponse 'admin_response'
     * @param  \App\Models\EmployeeMessage  $message Instance du message auquel répondre
     * @return \Illuminate\Http\RedirectResponse Redirection vers le message avec confirmation
     */
    public function reply(Request $request, EmployeeMessage $message)
    {
        // Validation de la réponse de l'administrateur
        $request->validate([
            'admin_response' => 'required|string|max:2000',
        ]);

        // Mise à jour du message : enregistrement de la réponse et changement de statut
        $message->update([
            'admin_response' => $request->admin_response,
            'status' => 'answered',
            'responded_at' => now(),
        ]);

        // Création d'une notification pour alerter l'employé de la réponse reçue
        EmployeeNotification::create([
            'employee_id' => $message->employee_id,
            'title' => 'Réponse à votre message',
            'message' => 'L\'administration a répondu à votre message : "' . $message->subject . '"',
            'type' => 'info',
        ]);

        return redirect()->route('admin.employee-messages.show', $message)
            ->with('success', 'Réponse envoyée avec succès.');
    }
}
