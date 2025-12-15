@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Détails du rendez-vous</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations du rendez-vous</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-cut mr-2"></i>Service
                                </h5>
                                <p class="mb-1"><strong>{{ $appointment->service->name ?? 'N/A' }}</strong></p>
                                <p class="text-muted">{{ $appointment->service->description ?? '' }}</p>
                                <p><strong>Prix:</strong> {{ $appointment->service->price ?? 0 }}€</p>
                                <p><strong>Durée:</strong> {{ $appointment->service->duration ?? 0 }} min</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-calendar mr-2"></i>Date & Heure
                                </h5>
                                <p class="mb-1">
                                    <strong>{{ $appointment->date->format('l d F Y') }}</strong>
                                </p>
                                <p class="h4">{{ $appointment->time }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-user mr-2"></i>Employé
                                </h5>
                                <p>{{ $appointment->employee->name ?? 'Non assigné' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-info-circle mr-2"></i>Status
                                </h5>
                                @switch($appointment->status)
                                    @case('pending')
                                        <span class="badge badge-warning badge-lg p-2">En attente de confirmation</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge badge-success badge-lg p-2">Confirmé</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-info badge-lg p-2">Terminé</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger badge-lg p-2">Annulé</span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        @if($appointment->notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fa fa-sticky-note mr-2"></i>Notes
                                </h5>
                                <p>{{ $appointment->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($appointment->payment)
                <div class="card mt-3">
                    <div class="card-header bg-success">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-credit-card mr-2"></i>Paiement
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Montant:</strong> {{ $appointment->payment->amount }}€</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Méthode:</strong> {{ ucfirst($appointment->payment->method) }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Status:</strong> 
                                    <span class="badge badge-{{ $appointment->payment->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($appointment->payment->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @if($appointment->payment->status == 'completed')
                        <a href="{{ route('payments.invoice', $appointment->payment) }}" class="btn btn-outline-primary">
                            <i class="fa fa-download mr-2"></i>Télécharger la facture
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions</h4>
                    </div>
                    <div class="card-body">
                        @if(!in_array($appointment->status, ['completed', 'cancelled']))
                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning btn-block mb-2">
                                <i class="fa fa-edit mr-2"></i>Modifier
                            </a>
                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fa fa-times mr-2"></i>Annuler le rendez-vous
                                </button>
                            </form>
                        @endif

                        @if($appointment->status == 'completed' && !$appointment->payment)
                            <a href="{{ route('payments.create', ['appointment' => $appointment->id]) }}" class="btn btn-success btn-block">
                                <i class="fa fa-credit-card mr-2"></i>Effectuer le paiement
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-center">
                        <i class="fa fa-phone fa-2x text-primary mb-2"></i>
                        <h5>Besoin d'aide ?</h5>
                        <p class="text-muted mb-2">Contactez-nous</p>
                        <p class="h5">01 23 45 67 89</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
