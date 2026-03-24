{{--
    Vue : Historique des rendez-vous du client
    Description : Affiche un tableau paginé de tous les rendez-vous passés avec la date, le service, le spécialiste, le prix, le statut et les actions (voir, télécharger facture).
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-history"></i></div>
                <div>
                    <h2 class="beauty-page-title">Historique</h2>
                    <p class="beauty-page-subtitle">Tous vos rendez-vous passés</p>
                </div>
            </div>
            <a href="{{ route('client.appointments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-12">
                @if($appointments->isEmpty())
                    <div class="card" style="border:none;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                        <div class="card-body">
                            <div class="beauty-empty">
                                <i class="fa fa-history"></i>
                                <h5>Aucun historique de rendez-vous</h5>
                                <p>Vous n'avez pas encore de rendez-vous passés</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="beauty-card">
                        <div class="beauty-card-header">
                            <h4><i class="fa fa-list mr-2" style="color:var(--primary);"></i>Tous vos rendez-vous</h4>
                        </div>
                        <div class="beauty-card-body" style="padding:0;">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Spécialiste</th>
                                            <th>Prix</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <strong>{{ $appointment->date->format('d/m/Y') }}</strong><br>
                                                <small style="color:var(--primary);font-weight:500;">{{ $appointment->time }}</small>
                                            </td>
                                            <td><strong>{{ $appointment->service->name ?? '—' }}</strong></td>
                                            <td>{{ $appointment->employee->name ?? '—' }}</td>
                                            <td><strong style="color:var(--primary);">{{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }} FCFA</strong></td>
                                            <td>{!! $appointment->status_badge !!}</td>
                                            <td>
                                                <a href="{{ route('client.appointments.show', $appointment) }}" class="btn btn-sm btn-info" style="border-radius:10px;">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($appointment->payment && in_array($appointment->payment->status, ['paid', 'completed']))
                                                    <a href="{{ route('client.payments.invoice', $appointment->payment) }}" class="btn btn-sm btn-secondary" style="border-radius:10px;">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($appointments->hasPages())
                        <div class="beauty-card-footer">
                            <div class="d-flex justify-content-center">
                                {{ $appointments->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
