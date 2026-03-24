<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour la gestion des messages des employés.
 * 
 * Permet aux employés d'envoyer des messages à l'administration
 * et de consulter l'historique de leurs échanges.
 */
class EmployeeMessageController extends Controller
{
    /**
     * Affiche la liste des messages de l'employé connecté.
     * 
     * Les messages sont triés par date de création décroissante.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();
        
        $messages = EmployeeMessage::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employee.messages.index', compact('messages', 'employee'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau message.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $employee = Auth::guard('employees')->user();
        return view('employee.messages.create', compact('employee'));
    }

    /**
     * Enregistre un nouveau message destiné à l'administration.
     * 
     * Le message est créé avec le statut 'pending' (en attente de lecture).
     *
     * @param  \Illuminate\Http\Request  $request  La requête contenant sujet et contenu du message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $employee = Auth::guard('employees')->user();

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        EmployeeMessage::create([
            'employee_id' => $employee->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.messages.index')
            ->with('success', 'Message envoyé à l\'administration avec succès.');
    }

    /**
     * Affiche les détails d'un message spécifique.
     * 
     * Vérifie que le message appartient bien à l'employé connecté.
     *
     * @param  \App\Models\EmployeeMessage  $message  Le message à afficher
     * @return \Illuminate\View\View
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function show(EmployeeMessage $message)
    {
        $employee = Auth::guard('employees')->user();

        // Vérification de propriété du message
        if ($message->employee_id !== $employee->id) {
            abort(403);
        }

        return view('employee.messages.show', compact('message', 'employee'));
    }
}
