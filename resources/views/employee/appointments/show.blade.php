{{--
    Vue : Détails d'un rendez-vous employé
    Description : Affiche les informations complètes d'un rendez-vous pour l'employé : client, service, horaire, statut et actions de gestion.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
    <div class="beauty-page-header">
        <div class="beauty-page-header-left">
            <div class="beauty-page-icon"><i class="fa fa-eye"></i></div>
            <div>
                <h2 class="beauty-page-title">Détails du Rendez-vous</h2>
                <p class="beauty-page-subtitle">Informations complètes du rendez-vous</p>
            </div>
        </div>
        <a href="{{ route('employee.appointments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
    </div>

    @include('partials.success')
    @include('partials.error')

    <div class="row">
        <div class="col-lg-8">
            <div class="beauty-card">
                <div class="beauty-card-header">
                    <h4><i class="fa fa-info-circle mr-2" style="color:var(--primary);"></i>Détails du Rendez-vous</h4>
                </div>
                <div class="beauty-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong>Client :</strong> {{ $appointment->client->name }}</h6>
                            <p>Email: {{ $appointment->client->email }} | Tél: {{ $appointment->client->phone ?? '?' }}</p>

                            <hr>

                            <h6><strong>Date :</strong> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->translatedFormat('l d F Y') }}</h6>
                            <h6><strong>Heure :</strong> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}</h6>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Service :</strong> {{ $appointment->service->name }}</h6>
                            <p class="text-muted">{{ $appointment->service->description }}</p>
                            <p><strong>Durée :</strong> {{ $appointment->service->duration }} minutes</p>

                            <h6><strong>Prix :</strong> <span class="text-success font-weight-bold">{{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }} FCFA</span></h6>
                        </div>
                    </div>

                    @if($appointment->notes)
                        <div class="mt-3">
                            <h6><strong>Notes du client :</strong></h6>
                            <p>{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="beauty-card">
                <div class="beauty-card-header">
                    <h4><i class="fa fa-cogs mr-2" style="color:var(--primary);"></i>Actions</h4>
                </div>
                <div class="beauty-card-body">
                    <h6><strong>Statut actuel :</strong> {!! $appointment->status_badge !!}</h6>
                    <hr>
                    <p>Changer le statut :</p>
                    <form action="{{ route('employee.appointments.updateStatus', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="pending" @if($appointment->status->value == 'pending') selected @endif>En attente</option>
                                <option value="confirmed" @if($appointment->status->value == 'confirmed') selected @endif>Confirmé</option>
                                <option value="completed" @if($appointment->status->value == 'completed') selected @endif>Terminé</option>
                                <option value="canceled" @if($appointment->status->value == 'canceled') selected @endif>Annulé</option>
                                <option value="no-show" @if($appointment->status->value == 'no-show') selected @endif>Non présenté</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                    </form>
                    @if($appointment->status->value == 'completed' && !$appointment->payment)
                    <hr>
                    <h6 class="mb-3"><strong><i class="fa fa-money mr-1" style="color:var(--accent);"></i> Paiement</strong></h6>
                    <a href="{{ route('employee.payments.create', ['appointment' => $appointment->id]) }}" class="btn btn-success btn-block mb-3" style="border-radius:12px;">
                        <i class="fa fa-credit-card mr-2"></i>Encaisser ce rendez-vous
                    </a>
                    @elseif($appointment->payment)
                    <hr>
                    <h6 class="mb-2"><strong><i class="fa fa-check-circle mr-1" style="color:#059669;"></i> Paiement</strong></h6>
                    <p class="mb-1"><strong>Montant :</strong> {{ number_format($appointment->payment->amount, 0, ',', ' ') }} FCFA</p>
                    <p class="mb-1"><strong>Méthode :</strong> {{ $appointment->payment->method_label }}</p>
                    <p class="mb-0"><strong>Statut :</strong> {!! $appointment->payment->status_badge !!}</p>
                    @endif

                    <hr>
                    <form action="{{ route('employee.appointments.addNote', ['appointment' => $appointment->id]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="post_note">Ajouter une note post-RDV</label>
                            <textarea name="note" id="post_note" class="form-control" rows="3" placeholder="Note sur la prestation, produits utilisés..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block">Ajouter la note</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
