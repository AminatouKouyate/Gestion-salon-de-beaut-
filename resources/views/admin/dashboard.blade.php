@extends('layouts.master') {{-- On s'assure d'utiliser le layout principal du template --}}

@section('content')
<div class="content-body">
    <div class="container-fluid mt-3">

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tableau de bord Administrateur</h4>
                    <p class="mb-0">Vue d'ensemble de l'activité du salon</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
               
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-1">
                    <div class="card-body">
                        <h3 class="card-title text-white">Clients</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $stats['total_clients'] }}</h2>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-2">
                    <div class="card-body">
                        <h3 class="card-title text-white">Employés</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $stats['total_employees'] }}</h2>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-user-tie"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-3">
                    <div class="card-body">
                        <h3 class="card-title text-white">Services</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $stats['total_services'] }}</h2>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-cut"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-4">
                    <div class="card-body">
                        <h3 class="card-title text-white">RDV en attente</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $stats['pending_appointments'] }}</h2>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-calendar-check"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Derniers rendez-vous</h4>
                        <div class="table-responsive">
                            @include('admin.appointments.recent_appointments_table', ['appointments' => $recentAppointments])
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
