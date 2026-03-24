@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

        {{-- WELCOME HEADER --}}
        <div class="dash-welcome">
            <div class="dash-welcome-left">
                <div class="dash-avatar">
                    <div class="dash-avatar-placeholder">
                        <i class="fa fa-shield"></i>
                    </div>
                </div>
                <div>
                    <h2 class="dash-greeting">Tableau de bord Admin </h2>
                    <p class="dash-subtitle">Vue d'ensemble de l'activité du salon</p>
                </div>
            </div>

        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:14px;border:none;font-size:14px;">
                <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- QUICK ACTIONS --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.appointments.index') }}" class="quick-action-card qa-rose">
                    <div class="qa-icon"><i class="fa fa-calendar"></i></div>
                    <div>
                        <h6>Rendez-vous</h6>
                        <small>Gérer les RDV</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.services.index') }}" class="quick-action-card qa-pink">
                    <div class="qa-icon"><i class="fa fa-scissors"></i></div>
                    <div>
                        <h6>Services</h6>
                        <small>Gérer les prestations</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.employees.index') }}" class="quick-action-card qa-gold">
                    <div class="qa-icon"><i class="fa fa-users"></i></div>
                    <div>
                        <h6>Employés</h6>
                        <small>Gérer l'équipe</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.clients.index') }}" class="quick-action-card qa-plum">
                    <div class="qa-icon"><i class="fa fa-heart"></i></div>
                    <div>
                        <h6>Clients</h6>
                        <small>Base clientèle</small>
                    </div>
                    <i class="fa fa-angle-right qa-arrow"></i>
                </a>
            </div>
        </div>

        {{-- STATS ROW --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-rose"><i class="fa fa-users"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stats['total_clients'] }}</h3>
                        <p>Clients</p>
                        <span class="stat-label">Total inscrits</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-gold"><i class="fa fa-user-secret"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stats['total_employees'] }}</h3>
                        <p>Employés</p>
                        <span class="stat-label">Équipe active</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-plum"><i class="fa fa-scissors"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stats['total_services'] }}</h3>
                        <p>Services</p>
                        <span class="stat-label">Prestations</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-pink"><i class="fa fa-clock-o"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stats['pending_appointments'] }}</h3>
                        <p>RDV en attente</p>
                        <span class="stat-label">À traiter</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- REVENUE ROW --}}
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="revenue-card revenue-total">
                    <div class="revenue-icon"><i class="fa fa-line-chart"></i></div>
                    <div class="revenue-info">
                        <p>Chiffre d'affaires total</p>
                        <h3>{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} <span>FCFA</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="revenue-card revenue-month">
                    <div class="revenue-icon"><i class="fa fa-calendar-o"></i></div>
                    <div class="revenue-info">
                        <p>CA du mois en cours</p>
                        <h3>{{ number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') }} <span>FCFA</span></h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- RECENT APPOINTMENTS --}}
        <div class="row">
            <div class="col-12">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Derniers Rendez-vous</h4>
                        <a href="{{ route('admin.appointments.index') }}" class="beauty-link">Voir tout <i class="fa fa-angle-right ml-1"></i></a>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            @include('admin.appointments.recent_appointments_table', ['appointments' => $recentAppointments])
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
.qa-icon { font-size: 22px; width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; }
.quick-action-card h6 { margin: 0; font-size: 15px; font-weight: 600; color: white !important; }
.quick-action-card small { opacity: 0.85; font-size: 12px; color: rgba(255,255,255,0.9) !important; }
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

/* Revenue cards */
.revenue-card {
    border-radius: 18px; padding: 28px; display: flex; align-items: center; gap: 20px;
    color: white; transition: all 0.3s;
}
.revenue-card:hover { transform: translateY(-3px); }
.revenue-total { background: linear-gradient(135deg, var(--primary), var(--dark-light)); box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
.revenue-month { background: linear-gradient(135deg, var(--dark), var(--dark-light)); box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
.revenue-icon {
    width: 56px; height: 56px; border-radius: 16px; background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;
}
.revenue-info p { margin: 0 0 4px; font-size: 14px; opacity: 0.85; }
.revenue-info h3 { margin: 0; font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; }
.revenue-info h3 span { font-size: 16px; font-weight: 400; opacity: 0.8; }

/* Dark mode */
.dark-theme .stat-card,
.dark-theme .beauty-card { background: #252540; border-color: #333355; }
.dark-theme .stat-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
.dark-theme .stat-info h3, .dark-theme .beauty-card-header h4,
.dark-theme .dash-greeting { color: #F0F0F0; }
.dark-theme .stat-info p, .dark-theme .stat-label { color: #B8B8B8; }
.dark-theme .dash-subtitle { color: #C0C0C0; }
.dark-theme .beauty-card-header { border-bottom-color: #333355; }
.dark-theme .beauty-table th { color: #B0B0B0; border-bottom-color: #333355; }
.dark-theme .beauty-table td { color: #E0E0E0; border-bottom-color: #2a2040; }
.dark-theme .beauty-table tbody tr:hover { background: #2a2040; }
.dark-theme .stat-icon-rose,
.dark-theme .stat-icon-gold,
.dark-theme .stat-icon-plum,
.dark-theme .stat-icon-pink { background: rgba(255,255,255,0.1); }
.dark-theme .quick-action-card { box-shadow: 0 2px 12px rgba(0,0,0,0.2); }
.dark-theme .quick-action-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
.dark-theme .revenue-total,
.dark-theme .revenue-month { box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.dark-theme .revenue-info p { color: #E0E0E0; }
.dark-theme .revenue-info h3, .dark-theme .revenue-info h3 span { color: #FFFFFF; }
.dark-theme .alert-success { background: linear-gradient(135deg, #1a3a2a, #1e4035) !important; color: #6ee7b7 !important; }

/* Entrance animations */
@keyframes dashFadeUp {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes dashScaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
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
.revenue-card { animation: dashFadeUp 0.6s ease both; animation-delay: 0.3s; }
.beauty-card { animation: dashFadeUp 0.6s ease both; animation-delay: 0.4s; }

/* Hover micro-interactions */
.stat-icon { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.stat-card:hover .stat-icon { transform: scale(1.15) rotate(-8deg); }
.qa-icon { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.quick-action-card:hover .qa-icon { transform: scale(1.12) rotate(5deg); }
.quick-action-card:hover .qa-arrow { opacity: 1; transform: translateX(4px); }
.qa-arrow { transition: all 0.3s ease; }
.revenue-icon { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.revenue-card:hover .revenue-icon { transform: scale(1.1) rotate(-5deg); }

@media (max-width: 768px) {
    .dash-welcome { flex-direction: column; align-items: flex-start; }
    .dash-greeting { font-size: 22px; }
    .revenue-info h3 { font-size: 22px; }
}
</style>
@endsection
