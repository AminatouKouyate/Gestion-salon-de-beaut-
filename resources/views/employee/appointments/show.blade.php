@extends('layouts.dashboard') {{-- Assurez-vous d'avoir un layout adapté --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Détails du Rendez-vous</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong>Client :</strong> {{ $appointment->client->name }}</h6>
                            <p>Email: {{ $appointment->client->email }} | Tél: {{ $appointment->client->phone ?? 'N/A' }}</p>

                            <hr>

                            <h6><strong>Date :</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l d F Y') }}</h6>
                            <h6><strong>Heure :</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</h6>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Service :</strong> {{ $appointment->service->name }}</h6>
                            <p class="text-muted">{{ $appointment->service->description }}</p>
                            <p><strong>Durée :</strong> {{ $appointment->service->duration }} minutes</p>

                            <h6><strong>Prix :</strong> <span class="text-success font-weight-bold">{{ $appointment->total_price }} FCFA</span></h6>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Actions</h5>
                </div>
                <div class="card-body">
                    <h6><strong>Statut actuel :</strong> <span class="badge badge-{{ $appointment->status_badge }}">{{ $appointment->status_text }}</span></h6>
                    <hr>
                    <p>Changer le statut :</p>
                    <form action="{{ route('employee.appointments.updateStatus', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="in_progress" @if($appointment->status == 'in_progress') selected @endif>En cours</option>
                                <option value="completed" @if($appointment->status == 'completed') selected @endif>Terminé</option>
                                <option value="no_show" @if($appointment->status == 'no_show') selected @endif>Client absent</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                    </form>
                    <hr>
                    <form action="{{ route('employee.appointments.addNote', $appointment) }}" method="POST">
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

    <div class="row mt-3">
        <div class="col-lg-12">
            <a href="{{ route('employee.appointments.index') }}" class="btn btn-secondary">Retour à mes RDV</a>
        </div>
    </div>
</div>
@endsection
