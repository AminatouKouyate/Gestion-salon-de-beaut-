{{--
    Vue : Liste des paiements (employé)
    Description : Vue des paiements récents avec filtres et statistiques pour l'employé.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Gestion des Paiements</h2>
                    <p class="beauty-page-subtitle">Encaissez et consultez les paiements</p>
                </div>
            </div>
            <a href="{{ route('employee.payments.create') }}" class="beauty-btn-primary"><i class="fa fa-money mr-2"></i>Encaisser</a>
        </div>

        @include('partials.success')
        @include('partials.error')

        @if($unpaidAppointments->isNotEmpty())
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fa fa-exclamation-triangle mr-2"></i>Rendez-vous a encaisser ({{ $unpaidAppointments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th class="text-right">Montant</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaidAppointments as $apt)
                                    <tr>
                                        <td>{{ $apt->client->name ?? 'N/A' }}</td>
                                        <td>{{ $apt->service->name ?? 'N/A' }}</td>
                                        <td>{{ $apt->scheduled_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($apt->service->getCurrentPrice(), 0, ',', ' ') }} FCFA</td>
                                        <td class="text-center">
                                            <a href="{{ route('employee.payments.create', ['appointment' => $apt->id]) }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-money mr-1"></i>Encaisser
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Historique des paiements</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Methode</th>
                                        <th class="text-right">Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->client->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->appointment->service->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->method_label }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{!! $payment->status_badge !!}</td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fa fa-credit-card fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun paiement enregistre</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
