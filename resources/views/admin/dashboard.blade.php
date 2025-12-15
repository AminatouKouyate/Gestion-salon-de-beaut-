@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Tableau de bord Administrateur</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Clients</h5>
                    <p class="card-text fs-3">{{ $stats['total_clients'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Employés</h5>
                    <p class="card-text fs-3">{{ $stats['total_employees'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Services</h5>
                    <p class="card-text fs-3">{{ $stats['total_services'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Rendez-vous en attente</h5>
                    <p class="card-text fs-3">{{ $stats['pending_appointments'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4 mb-3">Derniers rendez-vous</h3>
    <!-- Ici, tu peux inclure un tableau ou une liste des derniers rendez-vous -->
    @include('admin.appointments.partials.recent_appointments_table', ['appointments' => $recentAppointments])
@endsection
