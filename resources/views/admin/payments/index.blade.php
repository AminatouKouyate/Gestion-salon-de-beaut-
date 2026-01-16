@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Gestion des Paiements</h4>
                    <p class="mb-0">Liste de toutes les transactions</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Paiements</li>
                </ol>
            </div>
        </div>

        {{-- Affichage des erreurs --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @include('partials.success')

       

        {{-- Liste des paiements --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des paiements</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Rendez-vous</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>{{ $payment->client->name ?? 'N/A' }}</td>
                                        <td>#{{ $payment->appointment_id }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <span class="badge badge-pill badge-primary">{{ ucfirst($payment->status) }}</span>
                                        </td>
                                        <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            {{-- Ici tu peux ajouter les boutons edit/delete --}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun paiement trouvé.</td>
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
