{{--
    Vue : Fiche détaillée d'un client
    Route : admin.clients.show
    Contrôleur : ClientController@show
    Description : Affiche les informations personnelles, les rendez-vous récents
                  et les paiements récents d'un client.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-heart"></i></div>
                <div>
                    <h2 class="beauty-page-title">{{ $client->name }}</h2>
                    <p class="beauty-page-subtitle">Fiche client détaillée</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <a href="{{ route('admin.clients.edit', $client) }}" class="beauty-btn-primary ml-2">
                    <i class="fa fa-pencil mr-2"></i>Modifier
                </a>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        {{-- Informations du client --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-user mr-2" style="color:var(--primary);"></i>Informations personnelles</h4>
            </div>
            <div class="beauty-card-body">
                <div class="row">
                    @if($client->photo)
                    <div class="col-md-3 text-center mb-3">
                        <img src="{{ asset($client->photo) }}" alt="{{ $client->name }}" class="rounded-circle" style="width:120px; height:120px; object-fit:cover;">
                    </div>
                    @endif
                    <div class="{{ $client->photo ? 'col-md-9' : 'col-md-12' }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Nom :</strong><br>{{ $client->name }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email :</strong><br>{{ $client->email }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Téléphone :</strong><br>{{ $client->phone ?? '—' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Statut :</strong><br>
                                @if($client->active)
                                    <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Actif</span>
                                @else
                                    <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Inactif</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Points de fidélité :</strong><br>
                                {{ $client->loyalty_points ?? 0 }} pts
                                <span class="badge badge-info ml-1">{{ $client->getLoyaltyLevel() }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Allergies :</strong><br>{{ $client->allergies ?? 'Aucune' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rendez-vous récents --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Rendez-vous récents</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Service</th>
                                <th>Employé</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->formatted_date }}</td>
                                <td>{{ $appointment->formatted_time }}</td>
                                <td>{{ $appointment->service->name ?? '—' }}</td>
                                <td>{{ $appointment->employee->name ?? '—' }}</td>
                                <td>{!! $appointment->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-calendar"></i></div>
                                        <p>Aucun rendez-vous</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paiements récents --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-credit-card mr-2" style="color:var(--primary);"></i>Paiements récents</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                            @forelse($client->payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td>{{ $payment->appointment->service->name ?? '—' }}</td>
                                <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $payment->method_label }}</td>
                                <td>{!! $payment->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-credit-card"></i></div>
                                        <p>Aucun paiement</p>
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
@endsection
