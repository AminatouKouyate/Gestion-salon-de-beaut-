@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Détails du paiement</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations du paiement</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-credit-card mr-2"></i>Paiement
                                </h5>
                                <p><strong>Montant:</strong> <span class="h4 text-primary">{{ $payment->amount }}€</span></p>
                                <p><strong>Méthode:</strong> 
                                    @switch($payment->method)
                                        @case('stripe')
                                            <i class="fa fa-cc-stripe text-primary"></i> Carte bancaire
                                            @break
                                        @case('paypal')
                                            <i class="fa fa-paypal text-info"></i> PayPal
                                            @break
                                        @case('cash')
                                            <i class="fa fa-money text-success"></i> Espèces
                                            @break
                                        @case('salon')
                                            <i class="fa fa-building text-secondary"></i> Au salon
                                            @break
                                    @endswitch
                                </p>
                                <p><strong>Status:</strong> 
                                    @switch($payment->status)
                                        @case('pending')
                                            <span class="badge badge-warning p-2">En attente</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-success p-2">Complété</span>
                                            @break
                                        @case('failed')
                                            <span class="badge badge-danger p-2">Échoué</span>
                                            @break
                                        @case('refunded')
                                            <span class="badge badge-info p-2">Remboursé</span>
                                            @break
                                    @endswitch
                                </p>
                                @if($payment->transaction_id)
                                    <p><strong>Transaction ID:</strong> <code>{{ $payment->transaction_id }}</code></p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-calendar mr-2"></i>Rendez-vous associé
                                </h5>
                                <p><strong>Service:</strong> {{ $payment->appointment->service->name ?? 'N/A' }}</p>
                                <p><strong>Date:</strong> {{ $payment->appointment->date->format('d/m/Y') }}</p>
                                <p><strong>Heure:</strong> {{ $payment->appointment->time }}</p>
                                @if($payment->appointment->employee)
                                    <p><strong>Employé:</strong> {{ $payment->appointment->employee->name }}</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <p><strong>Date de création:</strong> {{ $payment->created_at->format('d/m/Y à H:i') }}</p>
                                @if($payment->updated_at != $payment->created_at)
                                    <p><strong>Dernière mise à jour:</strong> {{ $payment->updated_at->format('d/m/Y à H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions</h4>
                    </div>
                    <div class="card-body">
                        @if($payment->status == 'completed')
                            <a href="{{ route('payments.invoice', $payment) }}" class="btn btn-primary btn-block">
                                <i class="fa fa-download mr-2"></i>Télécharger la facture
                            </a>
                        @endif
                        
                        <a href="{{ route('appointments.show', $payment->appointment) }}" class="btn btn-outline-secondary btn-block mt-2">
                            <i class="fa fa-eye mr-2"></i>Voir le rendez-vous
                        </a>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-center">
                        <i class="fa fa-question-circle fa-2x text-info mb-2"></i>
                        <h5>Besoin d'aide ?</h5>
                        <p class="text-muted mb-2">Pour toute question sur votre paiement</p>
                        <p class="h5">01 23 45 67 89</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
