<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\ClientNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $client = Auth::guard('clients')->user();
        $notifications = $client->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('Clients.notifications.index', compact('notifications'));
    }

    public function markAsRead(ClientNotification $notification)
    {
        $client = Auth::guard('clients')->user();
        
        if ($notification->client_id !== $client->id) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllAsRead()
    {
        $client = Auth::guard('clients')->user();
        
        $client->notifications()->unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Toutes les notifications marquées comme lues');
    }

    public function getUnreadCount()
    {
        $client = Auth::guard('clients')->user();
        $count = $client->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
