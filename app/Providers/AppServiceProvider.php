<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager les notifications avec le header pour les clients connectés
        View::composer('partials.header', function ($view) {
            if (Auth::guard('clients')->check()) {
                $client = Auth::guard('clients')->user();

                // Récupérer les 5 dernières notifications
                // Assurez-vous que le modèle ClientNotification existe dans App\Models
                $headerNotifications = \App\Models\ClientNotification::where('client_id', $client->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                // Compter les non lues
                $unreadCount = \App\Models\ClientNotification::where('client_id', $client->id)
                    ->where('read', false)
                    ->count();

                $view->with('headerNotifications', $headerNotifications);
                $view->with('unreadNotificationsCount', $unreadCount);
            }
        });
    }
}
