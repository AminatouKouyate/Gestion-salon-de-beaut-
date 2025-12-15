@extends('admin.layouts.master')

@section('content')
<h2 class="mb-4">Rapports & Statistiques</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Chiffre d'affaires total (paiements payés)</h5>
                <h3 class="card-text">{{ number_format($totalRevenue, 2) }} €</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Services les plus demandés</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($topServices as $service)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $service->name }}
                            <span class="badge bg-primary rounded-pill">{{ $service->appointments_count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucun service demandé pour le moment.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Performance des employés (RDV traités)</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($employeePerformance as $emp)
                        <li class="list-group-item d-flex justify-content-between align-items-center">{{ $emp->name }} <span class="badge bg-success rounded-pill">{{ $emp->appointments_count }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
