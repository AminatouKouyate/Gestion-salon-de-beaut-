<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeNotificationController extends Controller
{
    /**
     * Affiche la liste des notifications de l'employé.
     */
    public function index()
    {
        $employee = Auth::guard('employees')->user();

        $notifications = $employee->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Marquer toutes les notifications comme lues
        $employee->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return view('employee.notifications.index', compact('notifications', 'employee'));
    }

    /**
     * Marque une notification comme lue.
     */
    public function markAsRead(EmployeeNotification $notification)
    {
        $employee = Auth::guard('employees')->user();

        // Vérifier que la notification appartient à l'employé
        if ($notification->employee_id !== $employee->id) {
            abort(403, 'Accès non autorisé');
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marque toutes les notifications comme lues.
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
