<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour la gestion des notifications des employés.
 * 
 * Permet aux employés de consulter leurs notifications et
 * de les marquer comme lues (individuellement ou en masse).
 */
class EmployeeNotificationController extends Controller
{
    /**
     * Affiche la liste des notifications de l'employé.
     * 
     * Les notifications sont triées par date décroissante et paginées.
     * Toutes les notifications non lues sont automatiquement marquées comme lues.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        $notifications = $employee->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Marquage automatique de toutes les notifications comme lues lors de l'affichage
        $employee->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return view('employee.notifications.index', compact('notifications', 'employee'));
    }

    /**
     * Marque une notification spécifique comme lue.
     * 
     * Vérifie que la notification appartient bien à l'employé connecté.
     *
     * @param  \App\Models\EmployeeNotification  $notification  La notification à marquer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  Si accès non autorisé (403)
     */
    public function markAsRead(EmployeeNotification $notification)
    {
        $employee = Auth::guard('employees')->user();

        if ($notification->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marque toutes les notifications de l'employé comme lues.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $employee = Auth::guard('employees')->user();

        $employee->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
