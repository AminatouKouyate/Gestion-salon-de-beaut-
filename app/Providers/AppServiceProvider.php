<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Events\PaymentSimulated;
use App\Listeners\SendAdminPaymentNotification;
use App\Models\Setting;

/**
 * Fournisseur de services principal de l'application.
 *
 * Configure les services globaux de l'application : pagination Bootstrap,
 * localisation française de Carbon, partage des notifications dans le header,
 * et enregistrement des écouteurs d'événements.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistre les services dans le conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Initialise les services de l'application après l'enregistrement.
     *
     * Configure la pagination, la locale, les View Composers pour les notifications
     * dans le header, et l'écouteur d'événements de paiement simulé.
     *
     * @return void
     */
    public function boot(): void
    {
        // Forcer HTTPS en production (Render est derrière un proxy HTTPS)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Utiliser Bootstrap 4 pour la pagination
        Paginator::useBootstrap();

        // Partager les paramètres de thème global avec toutes les vues
        View::composer('*', function ($view) {
            try {
                $settings = Setting::instance();
                $view->with('globalColorTheme', $settings->color_theme ?? 'rose-gold');
                $view->with('globalDarkMode', $settings->dark_mode ?? false);
            } catch (\Exception $e) {
                $view->with('globalColorTheme', 'rose-gold');
                $view->with('globalDarkMode', false);
            }
        });

        // Configurer Carbon pour afficher les dates en français
        Carbon::setLocale('fr');

        // Partager les notifications avec le header via un View Composer
        // Utilise un cache court (30 secondes) pour éviter de requêter la base à chaque page
        View::composer('partials.header', function ($view) {
            // Récupérer les notifications pour les clients connectés
            if (Auth::guard('clients')->check()) {
                $client = Auth::guard('clients')->user();
                $cacheKey = 'client_notif_' . $client->id;

                try {
                    // Cache de 30 secondes pour éviter les requêtes répétitives
                    $notifData = Cache::remember($cacheKey, 30, function () use ($client) {
                        return [
                            'notifications' => \App\Models\ClientNotification::where('client_id', $client->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get(),
                            'unreadCount' => \App\Models\ClientNotification::where('client_id', $client->id)
                                ->where('read', false)
                                ->count(),
                        ];
                    });

                    $view->with('headerNotifications', $notifData['notifications']);
                    $view->with('unreadNotificationsCount', $notifData['unreadCount']);
                } catch (\Exception $e) {
                    $view->with('headerNotifications', collect());
                    $view->with('unreadNotificationsCount', 0);
                }
            }

            // Récupérer les notifications pour les employés connectés
            if (Auth::guard('employees')->check()) {
                $employee = Auth::guard('employees')->user();
                $cacheKey = 'employee_notif_' . $employee->id;

                try {
                    $notifData = Cache::remember($cacheKey, 30, function () use ($employee) {
                        return [
                            'notifications' => \App\Models\EmployeeNotification::where('employee_id', $employee->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get(),
                            'unreadCount' => \App\Models\EmployeeNotification::where('employee_id', $employee->id)
                                ->where('is_read', false)
                                ->count(),
                        ];
                    });

                    $view->with('employeeNotifications', $notifData['notifications']);
                    $view->with('employeeUnreadCount', $notifData['unreadCount']);
                } catch (\Exception $e) {
                    $view->with('employeeNotifications', collect());
                    $view->with('employeeUnreadCount', 0);
                }
            }

            // Récupérer les notifications pour l'administrateur (guard web)
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                $cacheKey = 'admin_notif_' . $user->id;

                try {
                    $notifData = Cache::remember($cacheKey, 30, function () use ($user) {
                        return [
                            'notifications' => $user->notifications()->orderBy('created_at', 'desc')->take(5)->get(),
                            'unreadCount' => $user->unreadNotifications()->count(),
                        ];
                    });

                    $view->with('headerNotifications', $notifData['notifications']);
                    $view->with('unreadNotificationsCount', $notifData['unreadCount']);
                } catch (\Exception $e) {
                    $view->with('headerNotifications', collect());
                    $view->with('unreadNotificationsCount', 0);
                }
            }
        });

        // Enregistrer l'écouteur d'événements pour les paiements simulés
        Event::listen(PaymentSimulated::class, [SendAdminPaymentNotification::class, 'handle']);
    }
}
