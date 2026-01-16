@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Paiements</h4>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Historique des paiements</h4>
                    </div>
                    <div class="card-body">
                        @if($payments->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-credit-card fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun paiement enregistré</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Montant</th>
                                            <th>Méthode</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $payment->appointment->service->name ?? 'N/A' }}</td>
                                            <td><strong>{{ $payment->amount }} FCFA</strong></td>
                                            <td>
                                                @switch($payment->method)
                                                    @case('stripe')
                                                        <i class="fa fa-cc-stripe text-primary"></i> Stripe
                                                        @break
                                                    @case('paypal')
                                                        <i class="fa fa-paypal text-info"></i> PayPal
                                                        @break
                                                    @case('cash')
                                                        <i class="fa fa-money text-success"></i> Espèces
                                                        @break
                                                    @case('salon')
                                                        <i class="fa fa-building text-secondary"></i> Salon
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">En attente</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-success">Complété</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge badge-danger">Échoué</span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="badge badge-info">Remboursé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('client.payments.show', $payment) }}" class="btn btn-sm btn-info" title="Voir détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($payment->status == 'completed')
                                                    <a href="{{ route('client.payments.invoice', $payment) }}" class="btn btn-sm btn-secondary" title="Voir facture">
                                                        <i class="fa fa-file-text"></i>
                                                    </a>
                                                    <a href="{{ route('client.payments.invoice.download', $payment) }}" class="btn btn-sm btn-primary" title="Télécharger PDF">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $payments->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
