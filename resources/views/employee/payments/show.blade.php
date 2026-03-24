{{--
    Vue : Détails d'un paiement (employé)
    Description : Affiche les informations complètes d'un paiement : montant, méthode, statut et rendez-vous associé.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Détails du Paiement</h2>
                    <p class="beauty-page-subtitle">Informations complètes du paiement</p>
                </div>
            </div>
            <a href="{{ route('employee.payments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-6">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-money mr-2" style="color:var(--primary);"></i>Paiement</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:180px;">Montant</td>
                                    <td><strong class="text-success">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Méthode</td>
                                    <td>{{ $payment->method_label }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Statut</td>
                                    <td>{!! $payment->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date</td>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Rendez-vous associé</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:180px;">Client</td>
                                    <td><strong>{{ $payment->client->name ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Service</td>
                                    <td>{{ $payment->appointment->service->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date du RDV</td>
                                    <td>{{ $payment->appointment->scheduled_at ? \Carbon\Carbon::parse($payment->appointment->scheduled_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Statut du RDV</td>
                                    <td>{!! $payment->appointment->status_badge !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection