@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Paiement #{{ $payment->id }}</h2>
                    <p class="beauty-page-subtitle">Détails de la transaction</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        {{-- Informations du paiement --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-money mr-2" style="color:var(--primary);"></i>Informations du paiement</h4>
            </div>
            <div class="beauty-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Montant :</strong><br>
                        <span class="text-primary" style="font-size:18px;font-weight:700;">{{ number_format($payment->amount, 0, ',', ' ') }}</span> <small>FCFA</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Méthode :</strong><br>{{ $payment->method_label }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Statut :</strong><br>{!! $payment->status_badge !!}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date :</strong><br>
                        <i class="fa fa-clock-o mr-1" style="color:var(--primary-light);"></i>{{ $payment->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Informations du client --}}
            <div class="col-lg-6">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-user mr-2" style="color:var(--primary);"></i>Client</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Nom :</strong><br>{{ $payment->client->name ?? '—' }}
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Email :</strong><br>{{ $payment->client->email ?? '—' }}
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Téléphone :</strong><br>{{ $payment->client->phone ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informations du rendez-vous --}}
            <div class="col-lg-6">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Rendez-vous</h4>
                    </div>
                    <div class="beauty-card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Service :</strong><br>{{ $payment->appointment->service->name ?? '—' }}
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Date :</strong><br>
                                <i class="fa fa-clock-o mr-1" style="color:var(--primary-light);"></i>{{ $payment->appointment ? $payment->appointment->scheduled_at->format('d/m/Y H:i') : '—' }}
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Employé :</strong><br>{{ $payment->appointment->employee->name ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modifier le statut --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-pencil mr-2" style="color:var(--primary);"></i>Modifier le statut</h4>
            </div>
            <div class="beauty-card-body">
                <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label"><strong>Nouveau statut</strong></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="processing" {{ $payment->status === 'processing' ? 'selected' : '' }}>Traitement en cours</option>
                                    <option value="paid" {{ $payment->status === 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="completed" {{ $payment->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="failed" {{ $payment->status === 'failed' ? 'selected' : '' }}>Échoué</option>
                                    <option value="canceled" {{ $payment->status === 'canceled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <button type="submit" class="beauty-btn-primary">
                                    <i class="fa fa-check mr-2"></i>Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
