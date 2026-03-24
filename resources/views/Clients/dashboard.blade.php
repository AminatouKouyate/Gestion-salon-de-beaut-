{{--
    Vue : Tableau de bord client
    Description : Page d'accueil du client connecté : message de bienvenue, actions rapides (RDV, services, paiements, profil), statistiques (points fidélité, RDV à venir, total, dépenses), prochains rendez-vous, derniers paiements, programme fidélité et assistant IA.
--}}
    @extends('layouts.client-master')

@section('content')
<div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

    @php
        $loyaltyLevel = $client->getLoyaltyLevel();
        $loyaltyBadgeClass = match($loyaltyLevel) {
            'Platine' => 'primary',
            'Or' => 'warning',
            'Argent' => 'info',
            default => 'secondary',
        };
        $currentPoints = $client->loyalty_points ?? 0;
        $nextLevel = match($loyaltyLevel) {
            'Bronze' => ['name' => 'Argent', 'points' => 100],
            'Argent' => ['name' => 'Or', 'points' => 200],
            'Or' => ['name' => 'Platine', 'points' => 500],
            default => null,
        };
        $levelStart = match($loyaltyLevel) {
            'Bronze' => 0,
            'Argent' => 100,
            'Or' => 200,
            'Platine' => 500,
            default => 0,
        };
        $progressPercent = $nextLevel
            ? round((($currentPoints - $levelStart) / ($nextLevel['points'] - $levelStart)) * 100)
            : 100;
    @endphp

    {{-- WELCOME HEADER --}}
    <div class="dash-welcome">
        <div class="dash-welcome-left">
            <div class="dash-avatar">
                @if(auth('clients')->user()->photo)
                    <img src="{{ asset('storage/' . auth('clients')->user()->photo) }}" alt="Photo">
                @else
                    <div class="dash-avatar-placeholder">
                        {{ strtoupper(substr(auth('clients')->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <h2 class="dash-greeting">Bonjour, {{ Auth::guard('clients')->user()->name ?? 'Client' }}</h2>
                <p class="dash-subtitle">Bienvenue dans votre espace beauté</p>
            </div>
        </div>
        <a href="{{ route('client.appointments.create') }}" class="btn-beauty-primary">
            <i class="fa fa-calendar-plus-o mr-2"></i>Prendre RDV
        </a>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('client.appointments.create') }}" class="quick-action-card qa-rose">
                <div class="qa-icon"><i class="fa fa-calendar-plus-o"></i></div>
                <div>
                    <h6>Prendre RDV</h6>
                    <small>Réserver maintenant</small>
                </div>
                <i class="fa fa-angle-right qa-arrow"></i>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('client.services') }}" class="quick-action-card qa-pink">
                <div class="qa-icon"><i class="fa fa-scissors"></i></div>
                <div>
                    <h6>Nos Services</h6>
                    <small>Découvrir les soins</small>
                </div>
                <i class="fa fa-angle-right qa-arrow"></i>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('client.payments.index') }}" class="quick-action-card qa-gold">
                <div class="qa-icon">
                    <i class="fa fa-credit-card"></i>
                    @if(($pendingPayments ?? 0) > 0)
                        <span class="qa-badge">{{ $pendingPayments }}</span>
                    @endif
                </div>
                <div>
                    <h6>Paiements</h6>
                    <small>Gérer mes factures</small>
                </div>
                <i class="fa fa-angle-right qa-arrow"></i>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('client.profile') }}" class="quick-action-card qa-plum">
                <div class="qa-icon"><i class="fa fa-user"></i></div>
                <div>
                    <h6>Mon Profil</h6>
                    <small>Infos personnelles</small>
                </div>
                <i class="fa fa-angle-right qa-arrow"></i>
            </a>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-rose"><i class="fa fa-star"></i></div>
                <div class="stat-info">
                    <h3>{{ $currentPoints }}</h3>
                    <p>Points Fidélité</p>
                    <span class="stat-badge badge-{{ $loyaltyBadgeClass }}">{{ $loyaltyLevel }}</span>
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
                    <h3>{{ $totalAppointments }}</h3>
                    <p>Total RDV</p>
                    <span class="stat-label">{{ $completedAppointments }} terminé(s)</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-pink"><i class="fa fa-money"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalPaid ?? 0, 0, ',', ' ') }}</h3>
                    <p>FCFA dépensés</p>
                    <span class="stat-label">Total</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            {{-- UPCOMING APPOINTMENTS --}}
            <div class="beauty-card mb-4">
                <div class="beauty-card-header">
                    <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Prochains Rendez-vous</h4>
                    <a href="{{ route('client.appointments.index') }}" class="beauty-link">Voir tout <i class="fa fa-angle-right ml-1"></i></a>
                </div>
                <div class="beauty-card-body">
                    @if($upcomingAppointments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"></div>
                            <h5>Aucun rendez-vous à venir</h5>
                            <p>Réservez votre prochain soin beauté</p>
                            <a href="{{ route('client.appointments.create') }}" class="btn-beauty-primary btn-sm">
                                <i class="fa fa-plus mr-1"></i>Prendre RDV
                            </a>
                        </div>
                    @else
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
                                        <i class="fa fa-user mr-1"></i>{{ $appointment->employee->name ?? 'Non assigné' }}
                                    </p>
                                </div>
                                <div class="appt-status">{!! $appointment->status_badge !!}</div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- RECENT PAYMENTS --}}
            <div class="beauty-card mb-4">
                <div class="beauty-card-header">
                    <h4><i class="fa fa-credit-card mr-2" style="color:var(--accent);"></i>Derniers Paiements</h4>
                    <a href="{{ route('client.payments.index') }}" class="beauty-link">Voir tout <i class="fa fa-angle-right ml-1"></i></a>
                </div>
                <div class="beauty-card-body">
                    @if($recentPayments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"></div>
                            <h5>Aucun paiement</h5>
                            <p>Vos paiements apparaîtront ici</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="beauty-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Service</th>
                                        <th>Montant</th>
                                        <th>Méthode</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $payment->appointment->service->name ?? '—' }}</td>
                                        <td><strong style="color:var(--dark);">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>{{ $payment->method_label }}</td>
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
            {{-- LOYALTY PROGRAM --}}
            <div class="loyalty-card mb-4">
                <div class="loyalty-header">
                    <i class="fa fa-star"></i>
                    <h4>Programme Fidélité</h4>
                </div>
                <div class="loyalty-body">
                    <div class="loyalty-points">{{ $currentPoints }}</div>
                    <p class="loyalty-label">Points de fidélité</p>
                    <div class="loyalty-level-badge badge-{{ $loyaltyBadgeClass }}">
                        <i class="fa fa-trophy mr-1"></i> Niveau {{ $loyaltyLevel }}
                    </div>
                    @if($client->getLoyaltyDiscount() > 0)
                        <div class="loyalty-discount">
                            <i class="fa fa-percent"></i> {{ $client->getLoyaltyDiscount() }}% de réduction
                        </div>
                    @endif

                    @if($nextLevel)
                        <div class="loyalty-progress-section">
                            <small>Progression vers {{ $nextLevel['name'] }}</small>
                            <div class="loyalty-progress">
                                <div class="loyalty-progress-bar" style="width:{{ $progressPercent }}%;"></div>
                            </div>
                            <small class="loyalty-remaining">Plus que {{ $nextLevel['points'] - $currentPoints }} points</small>
                        </div>
                    @else
                        <div class="loyalty-max">
                            <i class="fa fa-trophy mr-1"></i> Niveau maximum atteint !
                        </div>
                    @endif
                </div>
            </div>

            {{-- NOTIFICATIONS --}}
            @if($unreadNotifications->isNotEmpty())
            <div class="beauty-card mb-4">
                <div class="beauty-card-header" style="background:linear-gradient(135deg,var(--primary),var(--dark));border-radius:16px 16px 0 0;">
                    <h4 style="color:white;"><i class="fa fa-bell mr-2"></i>Notifications ({{ $unreadNotifications->count() }})</h4>
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
                    <div style="padding:12px 20px;">
                        <a href="{{ route('client.notifications.index') }}" class="beauty-link">
                            Voir toutes les notifications <i class="fa fa-angle-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- AI ASSISTANT --}}
            <div class="beauty-card mb-4">
                <div class="beauty-card-body text-center" style="padding:30px;">
                    <div style="font-size:40px;margin-bottom:12px;"></div>
                    <h5 style="font-family:'Playfair Display',serif;margin-bottom:8px;">Assistant IA</h5>
                    <p style="font-size:13px;color:#8E8E8E;margin-bottom:16px;">Besoin d'aide ? Notre assistant virtuel est là pour vous</p>
                    <a href="{{ route('client.chatbot.index') }}" class="btn-beauty-outline">
                        <i class="fa fa-comments mr-2"></i>Démarrer une conversation
                    </a>
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
.dash-avatar img, .dash-avatar-placeholder {
    width: 64px; height: 64px; border-radius: 18px; object-fit: cover;
    border: 3px solid var(--primary); box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.dash-avatar-placeholder {
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 26px; font-weight: 700;
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
.btn-beauty-primary.btn-sm { padding: 8px 20px; font-size: 13px; }

.btn-beauty-outline {
    display: inline-flex; align-items: center; padding: 10px 24px;
    background: transparent; color: var(--primary);
    border: 2px solid var(--primary); border-radius: 12px;
    font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.3s;
}
.btn-beauty-outline:hover {
    background: var(--primary); color: white; text-decoration: none;
    transform: translateY(-2px);
}

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
.quick-action-card h6 { margin: 0; font-size: 15px; font-weight: 600; color: white !important; }
.quick-action-card small { opacity: 0.85; font-size: 12px; color: rgba(255,255,255,0.9) !important; }
.qa-arrow { margin-left: auto; font-size: 18px; opacity: 0.5; }

/* Stat cards */
.stat-card {
    background: white; border-radius: 18px; padding: 24px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    transition: all 0.3s; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
}
.stat-icon-rose { background: var(--primary-soft); color: var(--primary); }
.stat-icon-gold { background: var(--primary-soft); color: var(--dark-light); }
.stat-icon-plum { background: var(--primary-soft); color: var(--dark); }
.stat-icon-pink { background: var(--primary-soft); color: var(--primary-light); }
.stat-info h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin: 0; color: var(--dark); }
.stat-info p { font-size: 13px; color: #8E8E8E; margin: 2px 0 4px; }
.stat-badge { font-size: 11px; padding: 3px 10px; border-radius: 6px; font-weight: 600; }
.stat-label { font-size: 11px; color: #8E8E8E; }

/* Beauty cards */
.beauty-card {
    background: white; border-radius: 18px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.06);
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
    transition: all 0.3s;
}
.appointment-item:hover { background: var(--primary-soft); transform: translateX(4px); }
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

/* Loyalty card */
.loyalty-card {
    background: linear-gradient(135deg, var(--dark), color-mix(in srgb, var(--dark) 70%, black));
    border-radius: 18px; overflow: hidden; color: white;
}
.loyalty-header {
    padding: 20px 24px; display: flex; align-items: center; gap: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.loyalty-header i { font-size: 20px; color: var(--accent); }
.loyalty-header h4 { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: white; }
.loyalty-body { padding: 24px; text-align: center; }
.loyalty-points {
    font-family: 'Playfair Display', serif; font-size: 48px; font-weight: 700;
    color: var(--accent); line-height: 1;
}
.loyalty-label { opacity: 0.7; font-size: 14px; margin: 6px 0 16px; }
.loyalty-level-badge {
    display: inline-block; padding: 6px 20px; border-radius: 20px;
    font-size: 13px; font-weight: 600; background: color-mix(in srgb, var(--accent) 20%, transparent);
    color: var(--accent); border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
}
.loyalty-discount {
    margin-top: 14px; padding: 8px 16px; background: rgba(40,167,69,0.15);
    border-radius: 10px; color: #5CDB95; font-size: 14px; font-weight: 600;
    display: inline-block;
}
.loyalty-progress-section { margin-top: 20px; }
.loyalty-progress-section small { opacity: 0.7; font-size: 12px; }
.loyalty-progress {
    width: 100%; height: 8px; border-radius: 4px; background: rgba(255,255,255,0.15);
    margin: 8px 0;
}
.loyalty-progress-bar {
    height: 100%; border-radius: 4px;
    background: linear-gradient(90deg, var(--accent), var(--primary-light));
    transition: width 1s ease;
}
.loyalty-remaining { color: var(--primary-light); font-size: 12px; }
.loyalty-max {
    margin-top: 16px; padding: 10px; background: rgba(40,167,69,0.15);
    border-radius: 10px; color: #5CDB95; font-weight: 600;
}

/* Empty state */
.empty-state { text-align: center; padding: 30px 0; }
.empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state h5 { font-family: 'Playfair Display', serif; color: var(--dark); margin-bottom: 6px; }
.empty-state p { font-size: 14px; color: #8E8E8E; margin-bottom: 16px; }

/* Notifications */
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

/* Dark mode */
.dark-theme .stat-card,
.dark-theme .beauty-card { background: #252540; border-color: #333355; }
.dark-theme .stat-info h3, .dark-theme .beauty-card-header h4,
.dark-theme .dash-greeting, .dark-theme .appt-info h6,
.dark-theme .empty-state h5, .dark-theme .notif-list-item strong { color: #F0F0F0; }
.dark-theme .appointment-item { background: #2a2040; }
.dark-theme .appointment-item:hover { background: #332850; }
.dark-theme .beauty-table tbody tr:hover { background: #2a2040; }
.dark-theme .beauty-card-header { border-bottom-color: #333355; }
.dark-theme .beauty-table th { border-bottom-color: #333355; color: #B0B0B0; }
.dark-theme .beauty-table td { border-bottom-color: #2a2040; color: #E0E0E0; }
.dark-theme .notif-list-item { border-bottom-color: #333355; }
.dark-theme .notif-list-item:hover { background: #2a2040; }
.dark-theme .stat-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
.dark-theme .stat-info p, .dark-theme .stat-label { color: #B8B8B8; }
.dark-theme .dash-subtitle { color: #C0C0C0; }
.dark-theme .stat-icon-rose, .dark-theme .stat-icon-gold, .dark-theme .stat-icon-plum, .dark-theme .stat-icon-pink { background: rgba(255,255,255,0.1); }
.dark-theme .shortcut-icon { background: rgba(255,255,255,0.1) !important; }
.dark-theme .quick-action-card { box-shadow: 0 2px 12px rgba(0,0,0,0.2); }
.dark-theme .appt-info p { color: #B8B8B8; }
.dark-theme .notif-list-item p { color: #B8B8B8; }
.dark-theme .notif-list-item small { color: #D4979F; }
.dark-theme .empty-state p { color: #B8B8B8; }
.dark-theme .loyalty-label { color: #D0D0D0; }
.dark-theme .stat-badge { color: #F0F0F0; }

/* Entrance animations */
@keyframes dashFadeUp {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes dashScaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.dash-welcome { animation: dashFadeUp 0.6s ease both; }
.quick-action-card { animation: dashFadeUp 0.5s ease both; }
.col-lg-3:nth-child(1) .quick-action-card { animation-delay: 0.05s; }
.col-lg-3:nth-child(2) .quick-action-card { animation-delay: 0.1s; }
.col-lg-3:nth-child(3) .quick-action-card { animation-delay: 0.15s; }
.col-lg-3:nth-child(4) .quick-action-card { animation-delay: 0.2s; }
.stat-card { animation: dashScaleIn 0.5s ease both; }
.col-lg-3:nth-child(1) .stat-card, .col-sm-6:nth-child(1) .stat-card { animation-delay: 0.1s; }
.col-lg-3:nth-child(2) .stat-card, .col-sm-6:nth-child(2) .stat-card { animation-delay: 0.2s; }
.col-lg-3:nth-child(3) .stat-card, .col-sm-6:nth-child(3) .stat-card { animation-delay: 0.3s; }
.col-lg-3:nth-child(4) .stat-card, .col-sm-6:nth-child(4) .stat-card { animation-delay: 0.4s; }
.beauty-card { animation: dashFadeUp 0.6s ease both; animation-delay: 0.3s; }
.loyalty-card { animation: dashFadeUp 0.7s ease both; animation-delay: 0.35s; }
.stat-info h3 { animation: countUp 0.8s ease both; animation-delay: 0.5s; }

/* Hover micro-interactions */
.stat-icon { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.stat-card:hover .stat-icon { transform: scale(1.15) rotate(-8deg); }
.qa-icon { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.quick-action-card:hover .qa-icon { transform: scale(1.12) rotate(5deg); }
.quick-action-card:hover .qa-arrow { opacity: 1; transform: translateX(4px); }
.qa-arrow { transition: all 0.3s ease; }
.appointment-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.appointment-item:hover .appt-date-badge { transform: scale(1.08); }
.appt-date-badge { transition: all 0.3s ease; }
.loyalty-points { animation: countUp 1s ease both; animation-delay: 0.6s; }

@media (max-width: 768px) {
    .dash-welcome { flex-direction: column; align-items: flex-start; }
    .dash-greeting { font-size: 22px; }
    .loyalty-points { font-size: 36px; }
}
</style>
@endsection
