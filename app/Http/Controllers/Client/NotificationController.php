<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\ClientNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

/**
 * Contrôleur pour la gestion des notifications côté client.
 * 
 * Permet aux clients de consulter, marquer comme lues et gérer leurs notifications.
 */
class NotificationController extends Controller
{
    /**
     * Affiche la liste des notifications du client.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $client = Auth::guard('clients')->user();
        $notifications = $client->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('clients.notifications.index', compact('notifications'));
    }

    /**
     * Marque une notification spécifique comme lue.
     *
     * @param ClientNotification $notification La notification à marquer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Si le client n'est pas autorisé
     */
    public function markAsRead(ClientNotification $notification)
    {
        $client = Auth::guard('clients')->user();
        
        if ($notification->client_id !== $client->id) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marque toutes les notifications du client comme lues.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $client = Auth::guard('clients')->user();
        
        $client->notifications()->unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Toutes les notifications marquées comme lues');
    }

    /**
     * Retourne le nombre de notifications non lues (API JSON).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $client = Auth::guard('clients')->user();
        $count = $client->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
