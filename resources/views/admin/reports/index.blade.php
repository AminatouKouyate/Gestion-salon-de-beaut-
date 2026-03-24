@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- WELCOME HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-bar-chart"></i></div>
                <div>
                    <h2 class="beauty-page-title">Rapports & Statistiques </h2>
                    <p class="beauty-page-subtitle">Vue d'ensemble des performances du salon</p>
                </div>
            </div>
            <a href="{{ route('admin.reports.export') }}" class="beauty-btn-primary">
                <i class="fa fa-download mr-2"></i>Exporter CSV
            </a>
        </div>

        {{-- KPI PRINCIPALES --}}
        <div class="row mb-4">
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-gradient-card green">
                    <div class="beauty-gradient-icon"><i class="fa fa-money"></i></div>
                    <div class="info">
                        <p>Chiffre d'affaires total</p>
                        <h3>{{ number_format($totalRevenue, 0, ',', ' ') }} <span>FCFA</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-gradient-card purple">
                    <div class="beauty-gradient-icon"><i class="fa fa-calendar-check-o"></i></div>
                    <div class="info">
                        <p>Total rendez-vous</p>
                        <h3>{{ number_format($totalAppointments, 0, ',', ' ') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="beauty-gradient-card primary">
                    <div class="beauty-gradient-icon"><i class="fa fa-clock-o"></i></div>
                    <div class="info">
                        <p>RDV aujourd'hui</p>
                        <h3>{{ number_format($todayAppointments ?? 0, 0, ',', ' ') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI SECONDAIRES --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon rose"><i class="fa fa-users"></i></div>
                    <div>
                        <h3>{{ number_format($totalClients, 0, ',', ' ') }}</h3>
                        <p>Total clients</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon green"><i class="fa fa-user"></i></div>
                    <div>
                        <h3>{{ number_format($totalEmployees, 0, ',', ' ') }}</h3>
                        <p>Employés actifs</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon gold"><i class="fa fa-clock-o"></i></div>
                    <div>
                        <h3>{{ number_format($pendingPayments ?? 0, 0, ',', ' ') }}</h3>
                        <p>Paiements en attente</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="beauty-stat">
                    <div class="beauty-stat-icon purple"><i class="fa fa-check-circle"></i></div>
                    <div>
                        <h3>
                            @if($totalAppointments > 0)
                                {{ round((\App\Models\Appointment::where('status', 'completed')->count() / $totalAppointments) * 100) }}%
                            @else
                                0%
                            @endif
                        </h3>
                        <p>Taux complétion</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-line-chart mr-2" style="color:var(--primary);"></i>Revenus mensuels</h4>
                    </div>
                    <div class="beauty-card-body">
                        <canvas id="revenueChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-bar-chart mr-2" style="color:#059669;"></i>Rendez-vous mensuels</h4>
                    </div>
                    <div class="beauty-card-body">
                        <canvas id="appointmentsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- NEW CLIENTS CHART --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-user-plus mr-2" style="color:var(--primary);"></i>Nouveaux clients par mois</h4>
                    </div>
                    <div class="beauty-card-body">
                        <canvas id="newClientsChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLES --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-star mr-2" style="color:#d97706;"></i>Services les plus populaires</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" style="margin:0;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service</th>
                                        <th class="text-center">RDV</th>
                                        <th class="text-right">Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topServices as $index => $service)
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                            <span class="badge badge-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }}">
                                                {{ $index + 1 }}
                                            </span>
                                            @else
                                            {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td><strong style="color:var(--dark);">{{ $service->name }}</strong></td>
                                        <td class="text-center"><span class="badge badge-primary">{{ $service->appointments_count }}</span></td>
                                        <td class="text-right">{{ number_format($service->price ?? 0, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center py-4">
                                                <i class="fa fa-scissors fa-2x" style="color:var(--primary-light);opacity:0.4;"></i>
                                                <p style="color:#8E8E8E;margin-top:8px;">Aucun service trouvé</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-trophy mr-2" style="color:#059669;"></i>Performance des employés</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" style="margin:0;">
                                <thead>
                                    <tr>
                                        <th>Employé</th>
                                        <th class="text-center">Complétés</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-right">Revenus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employeePerformance as $employee)
                                    <tr>
                                        <td><strong style="color:var(--dark);">{{ $employee->name }}</strong></td>
                                        <td class="text-center"><span class="badge badge-success">{{ $employee->completed_appointments_count }}</span></td>
                                        <td class="text-center"><span class="badge badge-info">{{ $employee->total_appointments_count }}</span></td>
                                        <td class="text-right"><strong style="color:var(--primary);">{{ number_format($employee->revenue, 0, ',', ' ') }} FCFA</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center py-4">
                                                <i class="fa fa-users fa-2x" style="color:var(--primary-light);opacity:0.4;"></i>
                                                <p style="color:#8E8E8E;margin-top:8px;">Aucun employé trouvé</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthLabels = @json($monthLabels ?? []);
    const revenueData = @json($revenueData ?? []);
    const appointmentsData = @json($appointmentsData ?? []);
    const newClientsData = @json($newClientsData ?? []);

    // Revenue Chart
    if (document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: revenueData,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#B76E79',
                    backgroundColor: (function(){ var c = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#B76E79'; var r = parseInt(c.slice(1,3),16), g = parseInt(c.slice(3,5),16), b = parseInt(c.slice(5,7),16); return 'rgba('+r+','+g+','+b+',0.08)'; })(),
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Appointments Chart
    if (document.getElementById('appointmentsChart')) {
        new Chart(document.getElementById('appointmentsChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Rendez-vous',
                    data: appointmentsData,
                    backgroundColor: 'rgba(16,185,129,0.7)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // New Clients Chart
    if (document.getElementById('newClientsChart')) {
        new Chart(document.getElementById('newClientsChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Nouveaux clients',
                    data: newClientsData,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
