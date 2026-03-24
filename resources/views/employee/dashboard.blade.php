{{--
    Vue : Tableau de bord employé
    Description : Page d'accueil de l'employé connecté : statistiques, rendez-vous du jour, planning de la semaine et notifications récentes.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

        {{-- WELCOME HEADER --}}
        <div class="dash-welcome">
            <div class="dash-welcome-left">
                <div class="dash-avatar">
                    <div class="dash-avatar-placeholder">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <h2 class="dash-greeting">Bonjour, {{ $employee->name }}</h2>
                    <p class="dash-subtitle">Bienvenue dans votre espace employé</p>
                </div>
            </div>
            <a href="{{ route('employee.appointments.index') }}" class="btn-beauty-primary">
                <i class="fa fa-calendar mr-2"></i>Mes Rendez-vous
            </a>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('employee.appointments.index') }}" class="quick-action-card qa-rose">
                    <div class="qa-icon"><i class="fa fa-calendar"></i></div>
                    <div>
                        <h6>Mes RDV</h6>
                        <small>Voir mes rendez-vous</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('employee.schedules.index') }}" class="quick-action-card qa-pink">
                    <div class="qa-icon"><i class="fa fa-clock-o"></i></div>
                    <div>
                        <h6>Mon Planning</h6>
                        <small>Horaires de travail</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('employee.leaves.create') }}" class="quick-action-card qa-gold">
                    <div class="qa-icon"><i class="fa fa-plane"></i></div>
                    <div>
                        <h6>Congé</h6>
                        <small>Faire une demande</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('employee.messages.create') }}" class="quick-action-card qa-plum">
                    <div class="qa-icon">
                        <i class="fa fa-envelope"></i>
                        @if($unreadCount > 0)
                            <span class="qa-badge">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    <div>
                        <h6>Messages</h6>
                        <small>Contacter l'admin</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
        </div>

        {{-- STATS ROW --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-rose"><i class="fa fa-calendar-check-o"></i></div>
                    <div class="stat-info">
                        <h3>{{ $todayAppointments->count() }}</h3>
                        <p>RDV aujourd'hui</p>
                        <span class="stat-label">Rendez-vous du jour</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-gold"><i class="fa fa-calendar"></i></div>
                    <div class="stat-info">
                        <h3>{{ $upcomingAppointments->count() }}</h3>
                        <p>RDV à venir</p>
                        <span class="stat-label">Planifiés</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-plum"><i class="fa fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3>{{ $completedAppointments }}</h3>
                        <p>RDV terminés</p>
                        <span class="stat-label">sur {{ $totalAppointments }} au total</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-pink"><i class="fa fa-money"></i></div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }}</h3>
                        <p>FCFA générés</p>
                        <span class="stat-label">Chiffre d'affaires</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">
                {{-- TODAY'S APPOINTMENTS --}}
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Rendez-vous d'aujourd'hui</h4>
                        <a href="{{ route('employee.appointments.index') }}" class="beauty-link">Voir tout <i class="fa fa-angle-right ml-1"></i></a>
                    </div>
                    <div class="beauty-card-body">
                        @if($todayAppointments->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon"></div>
                                <h5>Aucun rendez-vous aujourd'hui</h5>
                                <p>Profitez de votre journée !</p>
                            </div>
                        @else
                            <div class="appointment-list">
                                @foreach($todayAppointments as $appointment)
                                <a href="{{ route('employee.appointments.show', $appointment) }}" class="appointment-item" style="text-decoration:none;">
                                    <div class="appt-time-badge">
                                        <i class="fa fa-clock-o"></i>
                                        <span>{{ $appointment->time }}</span>
                                    </div>
                                    <div class="appt-info">
                                        <h6>{{ $appointment->service->name ?? '—' }}</h6>
                                        <p><i class="fa fa-user mr-1"></i>{{ $appointment->client->name ?? '—' }}</p>
                                    </div>
                                    <div class="appt-status">{!! $appointment->status_badge !!}</div>
                                </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- UPCOMING APPOINTMENTS --}}
                @if($upcomingAppointments->isNotEmpty())
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar-o mr-2" style="color:var(--accent);"></i>Prochains Rendez-vous</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="appointment-list">
                            @foreach($upcomingAppointments as $appointment)
                            <div class="appointment-item">
                                <div class="appt-date-badge">
                                    <span class="appt-day">{{ $appointment->date->format('d') }}</span>
                                    <span class="appt-month">{{ $appointment->date->translatedFormat('M') }}</span>
                                </div>
                                <div class="appt-info">
                                    <h6>{{ $appointment->service->name ?? '—' }}</h6>
                                    <p>
                                        <i class="fa fa-clock-o mr-1"></i>{{ $appointment->time }}
                                        <span class="mx-2">•</span>
                                        <i class="fa fa-user mr-1"></i>{{ $appointment->client->name ?? '—' }}
                                    </p>
                                </div>
                                <div class="appt-status">{!! $appointment->status_badge !!}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- RECENT PAYMENTS --}}
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-credit-card mr-2" style="color:#10b981;"></i>Derniers Paiements</h4>
                    </div>
                    <div class="beauty-card-body">
                        @if(($recentPayments ?? collect())->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon"></div>
                                <h5>Aucun paiement récent</h5>
                                <p>Les paiements de vos clients apparaîtront ici</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="beauty-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $payment->client->name ?? '—' }}</td>
                                            <td>{{ $payment->appointment->service->name ?? '—' }}</td>
                                            <td><strong style="color:var(--dark);">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                            <td>{!! $payment->status_badge !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">
                {{-- PENDING LEAVES --}}
                @if($pendingLeaves > 0)
                <div class="info-card info-warning mb-4">
                    <div class="info-icon"><i class="fa fa-clock-o"></i></div>
                    <h5>Congés en attente</h5>
                    <p>{{ $pendingLeaves }} demande(s) en cours</p>
                    <a href="{{ route('employee.leaves.index') }}" class="info-link">Voir les demandes <i class="fa fa-angle-right ml-1"></i></a>
                </div>
                @endif

                {{-- NOTIFICATIONS --}}
                @if($unreadNotifications->isNotEmpty())
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header" style="background:linear-gradient(135deg, var(--primary), var(--dark));border-radius:18px 18px 0 0;">
                        <h4 style="color:white;"><i class="fa fa-bell mr-2"></i>Notifications ({{ $unreadCount }})</h4>
                    </div>
                    <div class="beauty-card-body" style="padding:0;">
                        @foreach($unreadNotifications as $notification)
                        <div class="notif-list-item">
                            <div class="notif-dot"></div>
                            <div>
                                <strong>{{ $notification->title }}</strong>
                                <p>{{ Str::limit($notification->message, 60) }}</p>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="padding:12px 20px;border-top:1px solid rgba(183,110,121,0.08);">
                        <a href="{{ route('employee.notifications.index') }}" class="beauty-link">
                            Voir toutes les notifications <i class="fa fa-angle-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endif

                {{-- QUICK LINKS --}}
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-bolt mr-2" style="color:var(--accent);"></i>Raccourcis</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="shortcut-list">
                            <a href="{{ route('employee.services.index') }}" class="shortcut-item">
                                <div class="shortcut-icon" style="background:var(--primary-soft);color:var(--primary);"><i class="fa fa-scissors"></i></div>
                                <span>Mes Services</span>
                                <i class="fa fa-angle-right shortcut-arrow"></i>
                            </a>
                            <a href="{{ route('employee.profile') }}" class="shortcut-item">
                                <div class="shortcut-icon" style="background:var(--primary-soft);color:var(--accent);"><i class="fa fa-user"></i></div>
                                <span>Mon Profil</span>
                                <i class="fa fa-angle-right shortcut-arrow"></i>
                            </a>
                            <a href="{{ route('employee.payments.index') }}" class="shortcut-item">
                                <div class="shortcut-icon" style="background:#E8F5E9;color:#2D8B61;"><i class="fa fa-credit-card"></i></div>
                                <span>Historique paiements</span>
                                <i class="fa fa-angle-right shortcut-arrow"></i>
                            </a>
                            <a href="{{ route('employee.notifications.index') }}" class="shortcut-item">
                                <div class="shortcut-icon" style="background:var(--primary-soft);color:var(--dark);"><i class="fa fa-bell"></i></div>
                                <span>Notifications</span>
                                @if($unreadCount > 0)
                                    <span class="badge badge-danger" style="margin-left:auto;margin-right:8px;">{{ $unreadCount }}</span>
                                @endif
                                <i class="fa fa-angle-right shortcut-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Welcome header */
.dash-welcome {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px 0 30px; flex-wrap: wrap; gap: 16px;
}
.dash-welcome-left { display: flex; align-items: center; gap: 16px; }
.dash-avatar-placeholder {
    width: 64px; height: 64px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 26px; font-weight: 700;
    border: 3px solid var(--primary); box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}
.dash-greeting {
    font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700;
    color: var(--dark); margin: 0;
}
.dash-subtitle { color: #8E8E8E; font-size: 14px; margin: 4px 0 0; }

/* Primary button */
.btn-beauty-primary {
    display: inline-flex; align-items: center; padding: 12px 28px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; border: none; border-radius: 14px; font-size: 14px; font-weight: 600;
    text-decoration: none !important; transition: all 0.3s; cursor: pointer;
    box-shadow: 0 4px 18px rgba(0,0,0,0.15);
}
.btn-beauty-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(0,0,0,0.2); color: white; }

/* Quick action cards */
.quick-action-card {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 20px; border-radius: 16px;
    text-decoration: none !important; color: white; transition: all 0.3s;
    position: relative; overflow: hidden;
}
.quick-action-card:hover { transform: translateY(-4px); color: white; box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
.qa-rose { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
.qa-pink { background: linear-gradient(135deg, var(--dark-light), var(--primary)); }
.qa-gold { background: linear-gradient(135deg, var(--dark), var(--dark-light)); }
.qa-plum { background: linear-gradient(135deg, color-mix(in srgb, var(--dark) 80%, black), var(--dark)); }
.qa-icon { font-size: 22px; width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; position: relative; }
.qa-badge { position: absolute; top: -6px; right: -6px; background: #E74C5F; font-size: 10px; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; }
.quick-action-card h6 { margin: 0; font-size: 15px; font-weight: 600; }
.quick-action-card small { opacity: 0.8; font-size: 12px; }
.qa-arrow { margin-left: auto; font-size: 18px; opacity: 0.5; }

/* Stat cards */
.stat-card {
    background: white; border-radius: 18px; padding: 24px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid rgba(0,0,0,0.04);
    transition: all 0.3s; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
}
.stat-icon-rose { background: var(--primary-soft); color: var(--primary); }
.stat-icon-gold { background: var(--primary-soft); color: var(--dark-light); }
.stat-icon-plum { background: var(--primary-soft); color: var(--dark); }
.stat-icon-pink { background: var(--primary-soft); color: var(--primary-light); }
.stat-info h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin: 0; color: var(--dark); }
.stat-info p { font-size: 13px; color: #8E8E8E; margin: 2px 0 4px; }
.stat-label { font-size: 11px; color: #8E8E8E; }

/* Beauty cards */
.beauty-card {
    background: white; border-radius: 18px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.beauty-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid rgba(0,0,0,0.06);
}
.beauty-card-header h4 {
    font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark);
}
.beauty-card-body { padding: 20px 24px; }
.beauty-link {
    font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; transition: color 0.3s;
}
.beauty-link:hover { color: var(--dark); text-decoration: none; }

/* Appointment list */
.appointment-list { display: flex; flex-direction: column; gap: 12px; }
.appointment-item {
    display: flex; align-items: center; gap: 16px;
    padding: 14px; border-radius: 14px; background: var(--bg);
    transition: all 0.3s; color: inherit;
}
.appointment-item:hover { background: var(--primary-soft); transform: translateX(4px); color: inherit; }
.appt-time-badge {
    min-width: 80px; padding: 8px 12px; border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; justify-content: center;
}
.appt-date-badge {
    width: 52px; min-width: 52px; height: 56px; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 0;
}
.appt-day { font-size: 20px; font-weight: 700; line-height: 1; }
.appt-month { font-size: 11px; text-transform: uppercase; opacity: 0.8; }
.appt-info { flex: 1; }
.appt-info h6 { margin: 0 0 4px; font-size: 15px; font-weight: 600; color: var(--dark); }
.appt-info p { margin: 0; font-size: 12px; color: #8E8E8E; }
.appt-status { white-space: nowrap; }

/* Beauty table */
.beauty-table { width: 100%; border-collapse: collapse; }
.beauty-table th {
    padding: 10px 14px; font-size: 12px; font-weight: 600; color: #8E8E8E;
    text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--primary-soft);
}
.beauty-table td { padding: 14px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.04); }
.beauty-table tbody tr:hover { background: var(--bg); }

/* Info card */
.info-card {
    border-radius: 18px; padding: 24px; text-align: center;
    border: 1px solid rgba(0,0,0,0.08);
}
.info-warning { background: #FFF8E1; border-color: #FFE082; }
.info-icon { font-size: 28px; color: #F5A623; margin-bottom: 10px; }
.info-card h5 { font-family: 'Playfair Display', serif; font-size: 16px; color: #F5A623; margin-bottom: 6px; }
.info-card p { font-size: 13px; color: #8E8E8E; margin-bottom: 12px; }
.info-link { font-size: 13px; font-weight: 600; color: #F5A623; text-decoration: none; }
.info-link:hover { color: #c48400; text-decoration: none; }

/* Empty state */
.empty-state { text-align: center; padding: 30px 0; }
.empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state h5 { font-family: 'Playfair Display', serif; color: var(--dark); margin-bottom: 6px; }
.empty-state p { font-size: 14px; color: #8E8E8E; margin-bottom: 16px; }

/* Notification list */
.notif-list-item {
    display: flex; gap: 12px; padding: 14px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    transition: background 0.3s;
}
.notif-list-item:hover { background: var(--bg); }
.notif-dot {
    width: 8px; height: 8px; border-radius: 50%; background: var(--primary);
    margin-top: 6px; flex-shrink: 0;
}
.notif-list-item strong { font-size: 14px; color: var(--dark); display: block; }
.notif-list-item p { font-size: 13px; color: #8E8E8E; margin: 3px 0; }
.notif-list-item small { font-size: 11px; color: var(--primary); }

/* Shortcut list */
.shortcut-list { display: flex; flex-direction: column; gap: 8px; }
.shortcut-item {
    display: flex; align-items: center; gap: 12px; padding: 12px 14px;
    border-radius: 12px; text-decoration: none !important; color: #2D2D2D;
    transition: all 0.25s;
}
.shortcut-item:hover { background: var(--primary-soft); color: var(--primary); transform: translateX(4px); }
.shortcut-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
}
.shortcut-item span { font-size: 14px; font-weight: 500; }
.shortcut-arrow { margin-left: auto; color: #8E8E8E; font-size: 14px; }

/* Dark mode */
.dark-theme .stat-card,
.dark-theme .beauty-card { background: #252540; border-color: #333355; }
.dark-theme .stat-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
.dark-theme .stat-info h3, .dark-theme .beauty-card-header h4,
.dark-theme .dash-greeting, .dark-theme .appt-info h6,
.dark-theme .empty-state h5, .dark-theme .notif-list-item strong,
.dark-theme .shortcut-item { color: #F0F0F0; }
.dark-theme .stat-info p, .dark-theme .stat-label { color: #B8B8B8; }
.dark-theme .dash-subtitle, .dark-theme .appt-info p,
.dark-theme .shortcut-arrow { color: #B8B8B8; }
.dark-theme .stat-icon-rose,
.dark-theme .stat-icon-gold,
.dark-theme .stat-icon-plum,
.dark-theme .stat-icon-pink { background: rgba(255,255,255,0.1); }
.dark-theme .appointment-item { background: #2a2040; }
.dark-theme .appointment-item:hover { background: #332850; }
.dark-theme .beauty-table th { color: #B0B0B0; border-bottom-color: #333355; }
.dark-theme .beauty-table td { color: #E0E0E0; border-bottom-color: #2a2040; }
.dark-theme .beauty-table tbody tr:hover { background: #2a2040; }
.dark-theme .beauty-card-header { border-bottom-color: #333355; }
.dark-theme .notif-list-item { border-bottom-color: #333355; }
.dark-theme .notif-list-item:hover { background: #2a2040; }
.dark-theme .notif-list-item p { color: #B8B8B8; }
.dark-theme .notif-list-item small { color: #D4979F; }
.dark-theme .shortcut-item:hover { background: #2a2040; }
.dark-theme .shortcut-icon { background: rgba(255,255,255,0.1) !important; }
.dark-theme .shortcut-item span { color: #E0E0E0; }
.dark-theme .info-warning { background: #3a3520; border-color: #665c30; }
.dark-theme .info-card h5 { color: #FFD54F; }
.dark-theme .info-card p { color: #B8B8B8; }
.dark-theme .info-link { color: #FFD54F; }
.dark-theme .empty-state p { color: #B8B8B8; }
.dark-theme .quick-action-card { box-shadow: 0 2px 12px rgba(0,0,0,0.2); }
.dark-theme .quick-action-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.3); }

@media (max-width: 768px) {
    .dash-welcome { flex-direction: column; align-items: flex-start; }
    .dash-greeting { font-size: 22px; }
}
</style>
@endsection
