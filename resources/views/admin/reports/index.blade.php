@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Titre --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Rapports & Statistiques</h1>
        </div>

        {{-- KPI --}}
        <div class="row mb-4">

            {{-- Chiffre d'affaires --}}
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Chiffre d’affaires</h6>
                        <h3 class="fw-bold">
                            {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Top services --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Top services</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($topServices as $service)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $service->name }}
                                    <span class="badge bg-primary">
                                        {{ $service->appointments_count }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">
                                    Aucun service
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Performance employés --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Performance employés</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($employeePerformance as $employee)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $employee->name }}
                                    <span class="badge bg-success">
                                        {{ $employee->appointments_count }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">
                                    Aucun employé
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
